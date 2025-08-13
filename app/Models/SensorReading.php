<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $fillable = [
        'datetime',
        'humid_out',
        'humid_in',
        'temp_in',
        'temp_out',
        'tan',
        'nh3'
    ];
}
