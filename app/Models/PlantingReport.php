<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantingReport extends Model
{
    protected $fillable = [
        'use_user_sensor_id',
        'user_id',
        'detail',
    ];

    public function plantingImage()
    {
        return $this->hasMany(PlantingImage::class, 'planting_report_id');
    }
}
