<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GeoCode;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $provinces = GeoCode::select('province_code as id', 'province_name_th as name')
            ->groupBy('province_code', 'province_name_th')
            ->get();
        $districts = GeoCode::select('district_code as id', 'district_name_th as name')
            ->groupBy('district_code', 'district_name_th')
            ->get();
        $subdistricts = GeoCode::select('subdistrict_code as id', 'subdistrict_name_th as name')
            ->groupBy('subdistrict_code', 'subdistrict_name_th')
            ->get();
        $sideAtive = 'map';

        return view('map', compact('sideAtive', 'provinces', 'districts', 'subdistricts'));
    }
}
