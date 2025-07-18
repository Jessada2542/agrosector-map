<?php

namespace App\Http\Controllers;

use App\Models\GeoCode;
use App\Models\UserSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = UserSensor::with('sensorKey', 'province', 'district', 'subdistrict')->where('user_id', Auth::id());

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('device_key', function ($row) {
                    return $row->sensorKey->key ?? 'N/A';
                })
                ->editColumn('position', function ($row) {
                    return $row->lat && $row->lon ? $row->lat . ', ' . $row->lon : 'N/A';
                })
               ->addColumn('address', function ($row) {
                    $parts = [
                        $row->province?->province_name_th ?? null,
                        $row->district?->district_name_th ?? null,
                        $row->subdistrict?->subdistrict_name_th ?? null,
                    ];

                    // ลบค่าว่างหรือ null ออก และรวมด้วย ', '
                    return implode(', ', array_filter($parts));
                })
                /* ->editColumn('status', function ($row) {
                    return $row->sensorKey->is_active == 1
                        ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">ออนไลน์</span>'
                        : '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">ออฟไลน์</span>';
                }) */
                ->addColumn('action', function ($row) {
                    return '<button class="px-3 py-1 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded btn-edit" data-id="' . $row->id . '"><i class="fa-solid fa-gear"></i> แก้ไข</button>';
                })
                ->rawColumns(['device_key', 'position', 'address'/* , 'status' */, 'action'])
                ->make(true);
        }

        $province = GeoCode::select('province_code as id', 'province_name_th as name')
            ->groupBy('province_code', 'province_name_th')
            ->get();
        $district = GeoCode::select('district_code as id', 'district_name_th as name')
            ->groupBy('district_code', 'district_name_th')
            ->get();
        $subdistrict = GeoCode::select('subdistrict_code as id', 'subdistrict_name_th as name')
            ->groupBy('subdistrict_code', 'subdistrict_name_th')
            ->get();

        $sideAtive = 'setting';

        return view('setting.index', compact('sideAtive', 'province', 'district', 'subdistrict'));
    }

    public function edit($id)
    {
        $userSensor = UserSensor::with('sensorKey')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        return response()->json([
            'status' => true,
            'data' => $userSensor
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_sensors,id',
            'lat' => 'required',
            'lon' => 'required',
            'province_code' => 'required|exists:geo_codes,province_code',
            'district_code' => 'required|exists:geo_codes,district_code',
            'subdistrict_code' => 'required|exists:geo_codes,subdistrict_code',
        ]);

        $userSensor = UserSensor::whereId($request->input('id'))->first();
        if (!$userSensor) {
            return response()->json(['status' => false, 'message' => 'Device not found'], 404);
        }
        $userSensor->update([
            'lat' => $request->input('lat'),
            'lon' => $request->input('lon'),
            'province_code' => $request->input('province_code'),
            'district_code' => $request->input('district_code'),
            'subdistrict_code' => $request->input('subdistrict_code'),
        ]);

        return response()->json(['status' => true, 'message' => 'Device settings updated successfully']);
    }
}
