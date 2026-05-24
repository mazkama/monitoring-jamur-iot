<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Threshold;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ThresholdController extends Controller
{
    public function index()
    {
        $thresholds = Threshold::with('device')->paginate(10);
        $devices = Device::all();
        return view('thresholds.index', compact('thresholds', 'devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|exists:devices,id',
            'thresholds' => 'required|array',
        ]);

        foreach ($request->thresholds as $type => $data) {
            if (in_array($type, ['temperature', 'humidity', 'co2'])) {
                if (isset($data['is_active']) && $data['is_active']) {
                    Threshold::updateOrCreate(
                        [
                            'device_id' => $request->device_id,
                            'sensor_type' => $type,
                        ],
                        [
                            'min_value' => $data['min_value'] ?? 0,
                            'max_value' => $data['max_value'] ?? 0,
                            'is_active' => true,
                        ]
                    );
                } else {
                    // Update existing to inactive, or do nothing if none exists.
                    $threshold = Threshold::where('device_id', $request->device_id)->where('sensor_type', $type)->first();
                    if ($threshold) {
                        $threshold->update(['is_active' => false]);
                    }
                }
            }
        }

        // Publish konfigurasi ke device via MQTT
        $this->publishConfigToDevice($request->device_id);

        return redirect()->route('thresholds.index')->with('success', 'Konfigurasi Threshold Perangkat berhasil disimpan.');
    }

    public function update(Request $request, Threshold $threshold)
    {
        $validated = $request->validate([
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric',
            'is_active' => 'boolean',
        ]);

        if (!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        $threshold->update($validated);

        return redirect()->route('thresholds.index')->with('success', 'Status Threshold diperbarui.');
    }

    public function destroy(Threshold $threshold)
    {
        $threshold->delete();
        return redirect()->route('thresholds.index')->with('success', 'Setting Threshold dihapus.');
    }

    /**
     * Publish konfigurasi aktif device ke MQTT topic config.
     */
    protected function publishConfigToDevice(string $deviceId): void
    {
        try {
            $device     = Device::find($deviceId);
            $thresholds = Threshold::where('device_id', $deviceId)
                ->where('is_active', true)
                ->get()
                ->keyBy('sensor_type');

            $config = [
                'device_name' => $device->name ?? $deviceId,
                'co2_min'     => (float) ($thresholds->get('co2')?->min_value         ?? 0),
                'co2_max'     => (float) ($thresholds->get('co2')?->max_value         ?? 0),
                'temp_min'    => (float) ($thresholds->get('temperature')?->min_value ?? 0),
                'temp_max'    => (float) ($thresholds->get('temperature')?->max_value ?? 0),
                'hum_min'     => (float) ($thresholds->get('humidity')?->min_value    ?? 0),
                'hum_max'     => (float) ($thresholds->get('humidity')?->max_value    ?? 0),
            ];

            app(MqttService::class)->publishDeviceConfig($deviceId, $config);

        } catch (\Throwable $e) {
            Log::error('[MQTT] Gagal publish config dari ThresholdController', [
                'device_id' => $deviceId,
                'error'     => $e->getMessage(),
            ]);
        }
    }
}
