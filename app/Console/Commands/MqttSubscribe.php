<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\Device;
use App\Models\SensorLog;
use App\Models\Threshold;
use App\Services\MqttService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttSubscribe extends Command
{
    protected $signature   = 'mqtt:subscribe';
    protected $description = 'Subscribe ke MQTT broker dan simpan data sensor ke database (daemon)';

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

        try {
            // 1. Cek apakah device sudah terdaftar di sistem
            $device = Device::find($deviceId);

            if (!$device) {
                $this->warn("[MQTT] Device '{$deviceId}' TIDAK terdaftar. Data diabaikan. Daftarkan device terlebih dahulu di menu Devices.");
                Log::warning('[MQTT] Data dari device tidak dikenal diabaikan', [
                    'device_id' => $deviceId,
                    'topic'     => $topic,
                ]);
                return;
            }

            // 2. Update status device menjadi aktif (jika sebelumnya inactive)
            if ($device->status !== 'active') {
                $device->update(['status' => 'active']);
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

            // 3. Proses Alert dari payload device
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
     * Buat Alert berdasarkan flag dari payload device.
     */
    protected function processAlerts(string $deviceId, array $data, Carbon $now): void
    {
        $alertMap = [
            'temp_alert' => [
                'sensor_type' => 'temperature',
                'value'       => $data['temperature'] ?? 0,
                'temp_min'    => $data['temp_min']    ?? null,
                'temp_max'    => $data['temp_max']    ?? null,
            ],
            'hum_alert'  => [
                'sensor_type' => 'humidity',
                'value'       => $data['humidity']    ?? 0,
                'hum_min'     => $data['hum_min']     ?? null,
                'hum_max'     => $data['hum_max']     ?? null,
            ],
            'co2_alert'  => [
                'sensor_type' => 'co2',
                'value'       => $data['co2']         ?? 0,
                'co2_min'     => $data['co2_min']     ?? null,
                'co2_max'     => $data['co2_max']     ?? null,
            ],
        ];

        foreach ($alertMap as $flag => $info) {
            if (!($data[$flag] ?? false)) {
                continue;
            }

            $sensorType = $info['sensor_type'];
            $value      = $info['value'];

            // Tentukan kondisi berdasarkan min/max dari payload
            $minKey    = $sensorType === 'temperature' ? 'temp_min'
                       : ($sensorType === 'humidity'   ? 'hum_min' : 'co2_min');
            $maxKey    = $sensorType === 'temperature' ? 'temp_max'
                       : ($sensorType === 'humidity'   ? 'hum_max' : 'co2_max');

            $min       = $data[$minKey] ?? null;
            $max       = $data[$maxKey] ?? null;

            $condition = 'out_of_range';
            if ($max !== null && $value > $max) {
                $condition = 'above_max';
            } elseif ($min !== null && $value < $min) {
                $condition = 'below_min';
            }

            Alert::create([
                'device_id'   => $deviceId,
                'sensor_type' => $sensorType,
                'value'       => $value,
                'condition'   => $condition,
                'status'      => 'unresolved',
                'created_at'  => $now,
            ]);

            $this->warn("[MQTT] Alert dibuat | {$sensorType} = {$value} | kondisi: {$condition}");
            Log::warning('[MQTT] Alert created', [
                'device_id'   => $deviceId,
                'sensor_type' => $sensorType,
                'value'       => $value,
                'condition'   => $condition,
            ]);
        }
    }
}
