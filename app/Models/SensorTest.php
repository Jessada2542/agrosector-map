<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorTest extends Model
{
    protected $fillable = [
        'n',
        'p',
        'k',
        'ph',
        'ec',
        'temperature',
        'humidity',
        'air_humidity',
        'air_temperature',
        'light'
    ];
}
