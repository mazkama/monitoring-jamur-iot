<?php

namespace App\Services;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected ?MqttClient $client = null;

    /**
     * Buat koneksi ke broker MQTT.
     */
    public function connect(): MqttClient
    {
        $host     = config('mqtt.host');
        $port     = config('mqtt.port');
        $clientId = config('mqtt.client_id') . '-' . uniqid();

        $settings = (new ConnectionSettings())
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setConnectTimeout(10)
            ->setReconnectAutomatically(false);

        if (config('mqtt.tls_enabled')) {
            $settings = $settings->setUseTls(true)
                                 ->setTlsVerifyPeer(true)
                                 ->setTlsVerifyPeerName(true);
        }

        $this->client = new MqttClient($host, $port, $clientId);
        $this->client->connect($settings);

        Log::info('[MQTT] Terhubung ke broker', ['host' => $host, 'port' => $port]);

        return $this->client;
    }

    /**
     * Publish konfigurasi threshold ke device tertentu.
     *
     * @param  string  $deviceId   MAC address / device ID
     * @param  array   $config     Payload konfigurasi
     */
    public function publishDeviceConfig(string $deviceId, array $config): void
    {
        $topic   = str_replace('{device_id}', $deviceId, config('mqtt.topics.publish_config'));
        $payload = json_encode($config);

        try {
            $client = $this->connect();
            $client->publish($topic, $payload, MqttClient::QOS_AT_LEAST_ONCE, true);
            $client->disconnect();

            Log::info('[MQTT] Config dikirim ke device', [
                'topic'   => $topic,
                'payload' => $config,
            ]);
        } catch (\Throwable $e) {
            Log::error('[MQTT] Gagal publish config', [
                'device_id' => $deviceId,
                'error'     => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Disconnect dari broker (jika masih terhubung).
     */
    public function disconnect(): void
    {
        if ($this->client && $this->client->isConnected()) {
            $this->client->disconnect();
        }
    }
}
