<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Device;
use App\Models\Threshold;
use App\Models\SensorLog;
use App\Models\Harvest;
use App\Models\Alert;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@iot.com',
            'password' => bcrypt('123123'),
            'role' => 'admin',
        ]);

        $staff = User::create([
            'name' => 'Staff User',
            'email' => 'staff@iot.com',
            'password' => bcrypt('123123'),
            'role' => 'staff',
        ]);

        // 2. Create Devices
        $device1 = Device::create([
            'id' => 'CH-001',
            'name' => 'Chamber 1 (Vantage)',
            'location' => 'Main Lab',
            'status' => 'active',
        ]);

        $device2 = Device::create([
            'id' => 'INC-A',
            'name' => 'Incubator A',
            'location' => 'Zone 2',
            'status' => 'active',
        ]);

        // 3. Create Thresholds (Consistent for both devices)
        $sensorTypes = ['temperature', 'humidity', 'co2'];
        $ranges = [
            'temperature' => [20.0, 30.0],
            'humidity' => [60.0, 90.0],
            'co2' => [400.0, 1200.0],
        ];

        foreach ([$device1, $device2] as $device) {
            foreach ($sensorTypes as $type) {
                Threshold::create([
                    'device_id' => $device->id,
                    'sensor_type' => $type,
                    'min_value' => $ranges[$type][0],
                    'max_value' => $ranges[$type][1],
                    'is_active' => true,
                ]);
            }
        }

        // 4. Create Sample Sensor Logs (Last 12 hours)
        for ($i = 12; $i >= 0; $i--) {
            $time = Carbon::now()->subHours($i);
            
            SensorLog::create([
                'device_id' => $device1->id,
                'temperature' => rand(220, 270) / 10,
                'humidity' => rand(70, 85),
                'co2' => rand(500, 800),
                'created_at' => $time,
            ]);

            SensorLog::create([
                'device_id' => $device2->id,
                'temperature' => rand(200, 250) / 10,
                'humidity' => rand(65, 80),
                'co2' => rand(450, 750),
                'created_at' => $time,
            ]);
        }

        // 5. Create Sample Harvest Records
        Harvest::create([
            'date' => Carbon::now()->subDays(2),
            'amount' => 12.5,
            'quality' => 'Grade A',
            'device_id' => $device1->id,
            'user_id' => $staff->id,
        ]);

        Harvest::create([
            'date' => Carbon::now()->subDays(5),
            'amount' => 8.2,
            'quality' => 'Grade B',
            'device_id' => $device2->id,
            'user_id' => $staff->id,
        ]);

        // 6. Create some sample Alerts
        Alert::create([
            'device_id' => $device1->id,
            'sensor_type' => 'temperature',
            'value' => 32.5,
            'condition' => 'above_max',
            'status' => 'unresolved',
            'created_at' => Carbon::now()->subHours(1),
        ]);
    }
}
