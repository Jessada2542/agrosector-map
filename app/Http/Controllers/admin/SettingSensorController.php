<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SensorKey;
use App\Models\User;
use App\Models\UserSensor;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;

class SettingSensorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

        }

        $sensorAll = SensorKey::count();
        $sensorUse = UserSensor::count();
        $sensorNotUse = $sensorAll - $sensorUse;
        $users = User::where('role_id', 1)->select('id', 'name')->get();
        $sideActive = 'setting';

        return view('admin.sensor', compact('sideActive', 'users', 'sensorAll', 'sensorUse', 'sensorNotUse'));
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
