<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Device;
use App\Models\SensorLog;
use App\Models\SensorLogHourly;
use Carbon\Carbon;

class AggregateSensorHourly extends Command
{
    protected $signature = 'app:aggregate-sensor-hourly';
    protected $description = 'Aggregate sensor logs into hourly averages';

    public function handle()
    {
        $now = Carbon::now();
        $startTime = $now->copy()->subHour()->startOfHour();
        $endTime = $startTime->copy()->endOfHour();

        $devices = Device::all();

        foreach ($devices as $device) {
            $logs = SensorLog::where('device_id', $device->id)
                ->whereBetween('created_at', [$startTime, $endTime])
                ->get();

            if ($logs->count() > 0) {
                SensorLogHourly::create([
                    'device_id' => $device->id,
                    'avg_temperature' => $logs->avg('temperature'),
                    'avg_humidity' => $logs->avg('humidity'),
                    'avg_co2' => $logs->avg('co2'),
                    'hour_time' => $startTime,
                ]);

                $this->info("Aggregated data for device ID: {$device->id} for {$startTime}");
            }
        }

        return 0;
    }
}
