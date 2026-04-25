<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Device;
use App\Models\Harvest;
use App\Models\SensorLog;
use App\Models\SensorLogHourly;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $deviceCount = Device::count();
        $totalHarvest = Harvest::sum('amount');
        $unresolvedAlerts = Alert::where('status', 'unresolved')->count();
        
        // Paginate logs for the table
        $latestLogs = SensorLog::with('device')->latest()->paginate(10);

        // Chart Data (Last 24 hours of averages)
        $chartData = SensorLogHourly::with('device')
            ->orderBy('hour_time', 'asc')
            ->take(24)
            ->get();

        return view('dashboard', compact(
            'deviceCount',
            'totalHarvest',
            'unresolvedAlerts',
            'latestLogs',
            'chartData'
        ));
    }

    public function apiData()
    {
        $unresolvedAlerts = Alert::where('status', 'unresolved')->count();
        $latestEnv = SensorLog::with('device')->latest()->first();
        
        // Get the latest 10 logs for real-time table update (if on page 1)
        $latestLogs = SensorLog::with('device')->latest()->limit(10)->get()->map(function($log) {
            return [
                'device_name' => $log->device->name,
                'time_human' => $log->created_at->diffForHumans(),
                'temperature' => $log->temperature,
                'humidity' => $log->humidity,
                'co2' => $log->co2,
            ];
        });

        // Chart Data (Last 24 hours of averages)
        $chartData = SensorLogHourly::with('device')
            ->orderBy('hour_time', 'asc')
            ->take(24)
            ->get();

        // Add latest devices
        $latestDevices = Device::latest()->take(3)->get()->map(function($device) {
            return [
                'id' => $device->id,
                'name' => $device->name,
                'status' => $device->status,
                'short_id' => substr($device->id, 0, 15)
            ];
        });

        $deviceCount = Device::count();

        return response()->json([
            'stats' => [
                'temperature' => $latestEnv ? number_format($latestEnv->temperature, 1) : '--',
                'humidity' => $latestEnv ? number_format($latestEnv->humidity, 1) : '--',
                'co2' => $latestEnv ? number_format($latestEnv->co2, 0) : '--',
                'device_name' => $latestEnv ? $latestEnv->device->name : 'N/A',
                'last_seen' => $latestEnv ? $latestEnv->created_at->diffForHumans() : 'N/A',
                'unresolved_alerts' => $unresolvedAlerts,
                'device_count' => $deviceCount,
            ],
            'logs' => $latestLogs,
            'chartData' => $chartData,
            'devices' => $latestDevices
        ]);
    }
}
