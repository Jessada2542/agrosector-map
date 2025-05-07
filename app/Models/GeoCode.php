<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoCode extends Model
{
    protected $fillable = [
        'province_code',
        'province_name_th',
        'province_name_en',
        'district_code',
        'district_name_th',
        'district_name_en',
        'subdistrict_code',
        'subdistrict_name_th',
        'subdistrict_name_en',
    ];
}
