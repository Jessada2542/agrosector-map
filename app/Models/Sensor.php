<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'sensor_key_id',
        'n',
        'p',
        'k',
        'ph',
        'ec',
        'temperature',
        'humidity',
    ];

    public function sensorKey()
    {
        return $this->belongsTo(SensorKey::class);
    }
}
