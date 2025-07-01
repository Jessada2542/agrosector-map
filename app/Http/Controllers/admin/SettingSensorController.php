<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GeoCode;
use App\Models\SensorKey;
use App\Models\User;
use App\Models\UserSensor;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingSensorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = UserSensor::with('sensorKey', 'province', 'district', 'subdistrict');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->editColumn('sensor_key', function ($row) {
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
                ->editColumn('status', function ($row) {
                    return $row->sensorKey->is_active == 1
                        ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">ออนไลน์</span>'
                        : '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">ออฟไลน์</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="px-3 py-1 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded btn-edit" data-id="' . $row->id . '"><i class="fa-solid fa-gear"></i> แก้ไข</button>';
                })
                ->rawColumns(['user_name', 'sensor_key', 'position', 'address', 'status', 'action'])
                ->make(true);
        }

        $sensorAll = SensorKey::count();
        $sensorUse = UserSensor::count();
        $sensorNotUse = $sensorAll - $sensorUse;
        $users = User::where('role_id', 1)->select('id', 'name')->get();
        $province = GeoCode::select('province_code as id', 'province_name_th as name')
            ->groupBy('province_code', 'province_name_th')
            ->get();
        $district = GeoCode::select('district_code as id', 'district_name_th as name')
            ->groupBy('district_code', 'district_name_th')
            ->get();
        $subdistrict = GeoCode::select('subdistrict_code as id', 'subdistrict_name_th as name')
            ->groupBy('subdistrict_code', 'subdistrict_name_th')
            ->get();
        $sideActive = 'setting';

        return view('admin.sensor', compact('sideActive', 'users', 'sensorAll', 'sensorUse', 'sensorNotUse', 'province', 'district', 'subdistrict'));
    }

    public function data(Request $request)
    {
        // ดึง user_sensor_id ที่มี useSensor.status = 1
        $excludedSensorIds = UserUseSensor::where('status', 1)
            ->pluck('user_sensors_id');

        // Query หลัก
        $plantingData = UserSensor::with('sensorKey', 'useSensor')
            ->whereNotIn('id', $excludedSensorIds) // ตัดพวกที่มี status = 1 ทิ้ง
            ->where(function ($query) {
                $query->whereDoesntHave('useSensor') // ยังไม่มี
                    ->orWhereHas('useSensor', function ($q) {
                        $q->where('status', 0); // หรือมี แต่ status = 0
                    });
            })
            ->get();

        if (!$plantingData) {
            return response()->json([
                'status' => false,
                'message' => 'Planting data not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $plantingData
        ]);
    }

    public function update(Request $request)
    {
        // Logic to update sensor settings
        return response()->json(['status' => true, 'message' => 'Settings updated successfully']);
    }
}
