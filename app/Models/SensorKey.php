<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorKey extends Model
{
    protected $fillable = [
        'key',
        'is_active',
        'updated_at',
    ];
}
