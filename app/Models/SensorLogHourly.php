<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorLogHourly extends Model
{
    use HasFactory;

    protected $table = 'sensor_logs_hourly';
    public $timestamps = false;

    protected $fillable = ['device_id', 'avg_temperature', 'avg_humidity', 'avg_co2', 'hour_time'];

    protected $casts = [
        'hour_time' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
