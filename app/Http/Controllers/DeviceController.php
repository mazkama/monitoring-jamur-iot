<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Threshold;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    /**
     * Nilai threshold default untuk perangkat baru.
     */
    protected array $defaultThresholds = [
        'temperature' => ['min_value' => 22, 'max_value' => 35],
        'humidity'    => ['min_value' => 75, 'max_value' => 90],
        'co2'         => ['min_value' => 80, 'max_value' => 400],
    ];

    public function index()
    {
        $devices = Device::paginate(10);
        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        return view('devices.create', ['defaults' => $this->defaultThresholds]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'             => 'required|string|max:255|unique:devices,id',
            'name'           => 'required|string|max:255',
            'location'       => 'required|string|max:255',
            'status'         => 'required|in:active,inactive',
            'temp_min'       => 'required|numeric',
            'temp_max'       => 'required|numeric|gte:temp_min',
            'hum_min'        => 'required|numeric',
            'hum_max'        => 'required|numeric|gte:hum_min',
            'co2_min'        => 'required|numeric',
            'co2_max'        => 'required|numeric|gte:co2_min',
        ]);

        // 1. Buat device
        $device = Device::create([
            'id'       => $validated['id'],
            'name'     => $validated['name'],
            'location' => $validated['location'],
            'status'   => $validated['status'],
        ]);

        // 2. Buat threshold default (bisa diubah dari form)
        $thresholdData = [
            'temperature' => ['min_value' => $validated['temp_min'], 'max_value' => $validated['temp_max']],
            'humidity'    => ['min_value' => $validated['hum_min'],  'max_value' => $validated['hum_max']],
            'co2'         => ['min_value' => $validated['co2_min'],  'max_value' => $validated['co2_max']],
        ];

        foreach ($thresholdData as $type => $values) {
            Threshold::create([
                'device_id'   => $device->id,
                'sensor_type' => $type,
                'min_value'   => $values['min_value'],
                'max_value'   => $values['max_value'],
                'is_active'   => true,
            ]);
        }

        // 3. Publish config default ke MQTT
        try {
            app(MqttService::class)->publishDeviceConfig($device->id, [
                'device_name' => $device->name,
                'temp_min'    => (float) $validated['temp_min'],
                'temp_max'    => (float) $validated['temp_max'],
                'hum_min'     => (float) $validated['hum_min'],
                'hum_max'     => (float) $validated['hum_max'],
                'co2_min'     => (float) $validated['co2_min'],
                'co2_max'     => (float) $validated['co2_max'],
            ]);
        } catch (\Throwable $e) {
            Log::error('[MQTT] Gagal publish config saat tambah device', [
                'device_id' => $device->id,
                'error'     => $e->getMessage(),
            ]);
        }

        return redirect()->route('devices.index')->with('success', 'Perangkat berhasil ditambahkan dengan konfigurasi default.');
    }

    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'status'   => 'required|in:active,inactive',
        ]);

        $device->update($validated);

        // Publish ulang config ke MQTT agar device_name ikut terupdate
        try {
            $thresholds = Threshold::where('device_id', $device->id)
                ->where('is_active', true)
                ->get()
                ->keyBy('sensor_type');

            app(MqttService::class)->publishDeviceConfig($device->id, [
                'device_name' => $device->name,
                'temp_min'    => (float) ($thresholds->get('temperature')?->min_value ?? 0),
                'temp_max'    => (float) ($thresholds->get('temperature')?->max_value ?? 0),
                'hum_min'     => (float) ($thresholds->get('humidity')?->min_value    ?? 0),
                'hum_max'     => (float) ($thresholds->get('humidity')?->max_value    ?? 0),
                'co2_min'     => (float) ($thresholds->get('co2')?->min_value         ?? 0),
                'co2_max'     => (float) ($thresholds->get('co2')?->max_value         ?? 0),
            ]);
        } catch (\Throwable $e) {
            Log::error('[MQTT] Gagal publish config saat update device', [
                'device_id' => $device->id,
                'error'     => $e->getMessage(),
            ]);
        }

        return redirect()->route('devices.index')->with('success', 'Device berhasil diperbarui dan config dikirim ke device.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully.');
    }

    public function toggleStatus(Device $device)
    {
        $device->status = $device->status === 'active' ? 'inactive' : 'active';
        $device->save();

        return back()->with('success', 'Device status updated.');
    }
}
