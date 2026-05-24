<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Device;
use App\Models\SensorLog;
use App\Models\Threshold;
use Illuminate\Http\Request;

class SensorLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string|exists:devices,id',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'co2' => 'required|numeric',
        ]);

        $device = Device::findOrFail($validated['device_id']);
        
        if ($device->status !== 'active') {
            return response()->json(['message' => 'Device is inactive'], 403);
        }

        $log = SensorLog::create($validated);

        // Check thresholds
        $thresholds = Threshold::where('device_id', $device->id)
            ->where('is_active', true)
            ->get();

        foreach ($thresholds as $threshold) {
            $value = match($threshold->sensor_type) {
                'temperature' => $validated['temperature'],
                'humidity' => $validated['humidity'],
                'co2' => $validated['co2'],
                default => 0
            };
            
            if ($value > $threshold->max_value) {
                Alert::create([
                    'device_id' => $device->id,
                    'sensor_type' => $threshold->sensor_type,
                    'value' => $value,
                    'condition' => 'above_max', 
                    'status' => 'unresolved',
                ]);
            } elseif ($value < $threshold->min_value) {
                Alert::create([
                    'device_id' => $device->id,
                    'sensor_type' => $threshold->sensor_type,
                    'value' => $value,
                    'condition' => 'below_min',
                    'status' => 'unresolved',
                ]);
            }
        }

        return response()->json([
            'message' => 'Log stored successfully',
            'data' => $log
        ], 201);
    }
}
