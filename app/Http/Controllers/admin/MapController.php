<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GeoCode;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
        $sideActive = 'map';

        return view('admin.map', compact('sideActive', 'provinces', 'districts', 'subdistricts'));
    }

    public function dashboard(Request $request)
    {
        if ($request->ajax()) {
            $query = UserUseSensor::with('user', 'latestSensor');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->editColumn('datetime', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->rawColumns(['datetime'])
                ->make(true);
        }

        $sideActive = 'dashboard';

        return view('admin.dashboard', compact('sideActive'));
    }
}
