<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantingImage extends Model
{
    protected $fillable = [
        'planting_report_id',
        'image',
    ];

    public function plantingReport()
    {
        return $this->belongsTo(PlantingReport::class, 'planting_report_id');
    }
}
