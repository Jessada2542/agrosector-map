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
        'province_code',
        'district_code',
        'subdistrict_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sensorKey()
    {
        return $this->belongsTo(SensorKey::class);
    }

    public function useSensor()
    {
        return $this->hasOne(UserUseSensor::class, 'user_sensors_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(GeoCode::class, 'province_code', 'province_code');
    }

    public function district()
    {
        return $this->belongsTo(GeoCode::class, 'district_code', 'district_code');
    }

    public function subdistrict()
    {
        return $this->belongsTo(GeoCode::class, 'subdistrict_code', 'subdistrict_code');
    }
}
