<?php

namespace App\Http\Controllers;

use App\Models\UserSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = UserSensor::with('sensorKey')->where('user_id', Auth::id());

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('device_key', function ($row) {
                    return $row->sensorKey->key ?? 'N/A';
                })
                ->editColumn('position', function ($row) {
                    return $row->lat && $row->lon ? $row->lat . ', ' . $row->lon : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    return $row->sensorKey->is_active == 1
                        ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">ออนไลน์</span>'
                        : '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">ออฟไลน์</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="px-3 py-1 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded btn-edit" data-id="' . $row->id . '"><i class="fa-solid fa-gear"></i> แก้ไข</button>';
                })
                ->rawColumns(['device_key', 'position', 'status', 'action'])
                ->make(true);
        }

        $sideAtive = 'setting';

        return view('setting.index', compact('sideAtive'));
    }

    public function edit($id)
    {
        $userSensor = UserSensor::where('sensor_key_id', $id)
            ->where('user_id', Auth::id())
            ->with('sensorKey')
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
        ]);

        $userSensor = UserSensor::whereId($request->input('id'))->first();
        if (!$userSensor) {
            return response()->json(['status' => false, 'message' => 'Device not found'], 404);
        }
        $userSensor->update([
            'lat' => $request->input('lat'),
            'lon' => $request->input('lon'),
        ]);

        return response()->json(['status' => true, 'message' => 'Device settings updated successfully']);
    }
}
