<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $fillable = [
        'day',
        'hour',
        'temp_in',
        'temp_out'
    ];
}
