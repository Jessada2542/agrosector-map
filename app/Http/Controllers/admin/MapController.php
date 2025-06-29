<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GeoCode;
use App\Models\UserUseSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                ->editColumn('n', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->n : '';
                })
                ->editColumn('p', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->p : '';
                })
                ->editColumn('k', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->k : '';
                })
                ->editColumn('ph', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->ph : '';
                })
                ->editColumn('datetime', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->created_at->format('d-m-Y H:i') : '';
                })
                ->editColumn('date_start', function ($row) {
                    return $row->start_date ? Carbon::parse($row->start_date)->format('d-m-Y') : '';
                })
                ->editColumn('date_end', function ($row) {
                    return $row->end_date ? Carbon::parse($row->end_date)->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn-info bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" data-id="'. $row->id .'">
                        ดูข้อมูล
                    </button>';
                })
                ->rawColumns(['user_name', 'n', 'p', 'k', 'ph', 'datetime', 'date_start', 'date_end', 'action'])
                ->make(true);
        }

        $sideActive = 'dashboard';

        return view('admin.dashboard', compact('sideActive'));
    }

    public function data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_use_sensors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = UserUseSensor::with('user', 'latestSensor')->first();

        return response()->json(['status' => true, 'data' => $data]);
    }
}
