<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'sensor_type', 'min_value', 'max_value', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
