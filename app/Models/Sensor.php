<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'user_id',
        'use_user_sensor_id',
        'sensor_key_id',
        'n',
        'p',
        'k',
        'ph',
        'ec',
        'temperature',
        'humidity',
        'air_humidity',
        'air_temperature',
        'light',
        'co2',
        'nh3'
    ];

    public function sensorKey()
    {
        return $this->belongsTo(SensorKey::class);
    }

    public function useUserSensor()
    {
        return $this->belongsTo(UserUseSensor::class, 'use_user_sensor_id');
    }
}
