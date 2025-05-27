<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUseSensor extends Model
{
    protected $fillable = [
        'user_id',
        'user_sensors_id',
        'name',
        'detail',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userSensor()
    {
        return $this->belongsTo(UserSensor::class, 'user_sensors_id');
    }
}
