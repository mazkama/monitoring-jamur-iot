<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\Device;
use App\Models\SensorLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttSubscribe extends Command
{
    protected $signature   = 'mqtt:subscribe';
    protected $description = 'Subscribe ke MQTT broker dan simpan data sensor ke database (daemon)';

    /** Cooldown alert dalam menit – tidak buat alert baru jika kondisi sama masih aktif */
    protected int $alertCooldownMinutes = 30;

    public function handle(): int
    {
        $host     = config('mqtt.host');
        $port     = config('mqtt.port');
        $clientId = config('mqtt.client_id') . '-subscriber';
        $topic    = config('mqtt.topics.subscribe_status');

        $this->info("[MQTT] Menghubungkan ke {$host}:{$port} ...");

        $settings = (new ConnectionSettings())
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(15)
            ->setReconnectAutomatically(true);

        if (config('mqtt.tls_enabled')) {
            $settings = $settings->setUseTls(true)
                                 ->setTlsVerifyPeer(true)
                                 ->setTlsVerifyPeerName(true);
        }

        $client = new MqttClient($host, $port, $clientId);

        try {
            $client->connect($settings);
            $this->info("[MQTT] Terhubung! Mendengarkan topic: {$topic}");
            Log::info('[MQTT Subscriber] Started', ['topic' => $topic]);
        } catch (\Throwable $e) {
            $this->error("[MQTT] Gagal terhubung: " . $e->getMessage());
            Log::error('[MQTT Subscriber] Connection failed', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }

        $client->subscribe($topic, function (string $topic, string $rawMessage) {
            $this->processMessage($topic, $rawMessage);
        }, MqttClient::QOS_AT_LEAST_ONCE);

        // Loop selamanya – ini adalah daemon
        $client->loop(true);

        return self::SUCCESS;
    }

    /**
     * Proses pesan MQTT yang masuk dari topic jamur/device/{id}/status
     */
    protected function processMessage(string $topic, string $rawMessage): void
    {
        $this->line("[MQTT] Pesan diterima dari: {$topic}");
        Log::debug('[MQTT] Raw message', ['topic' => $topic, 'payload' => $rawMessage]);

        // Parse JSON
        $data = json_decode($rawMessage, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
            $this->warn("[MQTT] Payload tidak valid (bukan JSON): {$rawMessage}");
            Log::warning('[MQTT] Invalid JSON payload', ['raw' => $rawMessage]);
            return;
        }

        // Ekstrak device_id dari topic  →  jamur/device/{device_id}/status
        $parts    = explode('/', $topic);
        $deviceId = $parts[2] ?? ($data['device_id'] ?? null);

        if (!$deviceId) {
            $this->warn("[MQTT] device_id tidak ditemukan di topic/payload");
            return;
        }

        // ── Simpan device ke cache sebagai "pernah terdeteksi" (TTL 24 jam) ──
        // Digunakan untuk validasi saat mendaftarkan device baru di form
        Cache::put("mqtt_seen:{$deviceId}", [
            'device_name' => $data['device_name'] ?? $deviceId,
            'firmware'    => $data['firmware']    ?? '-',
            'ip'          => $data['ip']          ?? '-',
            'rssi'        => $data['rssi']        ?? null,
            'last_seen'   => now()->toDateTimeString(),
        ], now()->addHours(24));

        try {
            // 1. Cek apakah device sudah terdaftar di sistem
            $device = Device::find($deviceId);

            if (!$device) {
                $this->warn("[MQTT] Device '{$deviceId}' TIDAK terdaftar. Data diabaikan.");
                Log::warning('[MQTT] Data dari device tidak dikenal diabaikan', [
                    'device_id' => $deviceId,
                    'topic'     => $topic,
                ]);
                return;
            }

            // 2. Jika device dimatikan (inactive), abaikan data — hormati pilihan user
            if ($device->status === 'inactive') {
                $this->line("[MQTT] Device '{$deviceId}' sedang OFF. Data sensor dilewati.");
                return;
            }

            // 3. Simpan SensorLog
            $now = Carbon::now();
            SensorLog::create([
                'device_id'   => $deviceId,
                'temperature' => $data['temperature'] ?? 0,
                'humidity'    => $data['humidity']    ?? 0,
                'co2'         => $data['co2']         ?? 0,
                'created_at'  => $now,
            ]);

            $this->info(sprintf(
                "[MQTT] Log disimpan | Device: %s | Temp: %.1f°C | Hum: %.1f%% | CO2: %d ppm",
                $deviceId,
                $data['temperature'] ?? 0,
                $data['humidity']    ?? 0,
                $data['co2']         ?? 0
            ));

            // 4. Proses Alert dari payload device (dengan anti-spam cooldown)
            $this->processAlerts($deviceId, $data, $now);

        } catch (\Throwable $e) {
            $this->error("[MQTT] Error memproses pesan: " . $e->getMessage());
            Log::error('[MQTT] Failed to process message', [
                'device_id' => $deviceId,
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Buat Alert dengan logik anti-spam:
     * Hanya buat alert baru jika tidak ada alert UNRESOLVED untuk
     * device + sensor_type + condition yang sama dalam cooldown terakhir.
     */
    protected function processAlerts(string $deviceId, array $data, Carbon $now): void
    {
        $alertMap = [
            'temp_alert' => [
                'sensor_type' => 'temperature',
                'value'       => $data['temperature'] ?? 0,
                'min_key'     => 'temp_min',
                'max_key'     => 'temp_max',
            ],
            'hum_alert'  => [
                'sensor_type' => 'humidity',
                'value'       => $data['humidity']    ?? 0,
                'min_key'     => 'hum_min',
                'max_key'     => 'hum_max',
            ],
            'co2_alert'  => [
                'sensor_type' => 'co2',
                'value'       => $data['co2']         ?? 0,
                'min_key'     => 'co2_min',
                'max_key'     => 'co2_max',
            ],
        ];

        foreach ($alertMap as $flag => $info) {
            // Lewati jika flag alert tidak aktif dari device
            if (!($data[$flag] ?? false)) {
                continue;
            }

            $sensorType = $info['sensor_type'];
            $value      = $info['value'];
            $min        = $data[$info['min_key']] ?? null;
            $max        = $data[$info['max_key']] ?? null;

            // Tentukan kondisi berdasarkan nilai min/max
            $condition = 'out_of_range';
            if ($max !== null && $value > $max) {
                $condition = 'above_max';
            } elseif ($min !== null && $value < $min) {
                $condition = 'below_min';
            }

            // ── Anti-spam: jangan buat alert baru selama masih ada yang UNRESOLVED ──
            // Alert baru hanya dibuat setelah operator menyelesaikan alert sebelumnya
            $alreadyActive = Alert::where('device_id',   $deviceId)
                ->where('sensor_type', $sensorType)
                ->where('status',      'unresolved')
                ->exists();

            if ($alreadyActive) {
                $this->line(sprintf(
                    "[MQTT] Alert %s(%s) sudah aktif — dilewati (cooldown %d mnt)",
                    $sensorType, $condition, $this->alertCooldownMinutes
                ));
                continue;
            }

            // Buat alert baru
            Alert::create([
                'device_id'   => $deviceId,
                'sensor_type' => $sensorType,
                'value'       => $value,
                'condition'   => $condition,
                'status'      => 'unresolved',
                'created_at'  => $now,
            ]);

            $this->warn("[MQTT] ⚠ Alert BARU | {$sensorType} = {$value} | kondisi: {$condition}");
            Log::warning('[MQTT] Alert created', [
                'device_id'   => $deviceId,
                'sensor_type' => $sensorType,
                'value'       => $value,
                'condition'   => $condition,
            ]);
        }
    }
}
