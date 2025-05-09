<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSensor extends Model
{
    protected $fillable = [
        'user_id',
        'sensor_key_id',
        'lat',
        'lon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sensorKey()
    {
        return $this->belongsTo(SensorKey::class);
    }
}
