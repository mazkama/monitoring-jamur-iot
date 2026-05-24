<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'location', 'status'];

    public function sensorLogs()
    {
        return $this->hasMany(SensorLog::class);
    }

    public function thresholds()
    {
        return $this->hasMany(Threshold::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }
}
