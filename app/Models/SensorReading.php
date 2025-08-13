<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $fillable = [
        'temp',
        'humid',
        'co2',
    ];
}
