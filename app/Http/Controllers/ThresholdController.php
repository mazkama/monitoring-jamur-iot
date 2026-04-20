<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Threshold;
use Illuminate\Http\Request;

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
}
