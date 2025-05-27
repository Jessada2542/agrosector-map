<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserSensor;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
class UserController extends Controller
{
    public function index()
    {
        $data = User::whereId(Auth::id())->first();
        $sideAtive = 'user';

        return view('user.index', compact('sideAtive', 'data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ])->setAttributeNames([
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::whereId($id)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $avatarName = NULL;
            if ($request->hasFile('avatar')) {
                $oldAvatarPath = public_path('images/avatars/' . $user->avatar);
                if ($user->avatar && file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }

                $avatar = $request->file('avatar');
                $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
                $avatar->move(public_path('images/avatars'), $avatarName);
            }

            $user->update([
                'avatar' => $avatarName ? $avatarName : $user->avatar,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update user'
            ], 500);
        }
    }

    public function planting(Request $request)
    {
        if ($request->ajax()) {
            $query = UserUseSensor::with('userSensor.sensorKey')->where('user_id', Auth::id());

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('device_key', function ($row) {
                    return $row->userSensor?->sensorKey?->key ?? 'N/A';
                })
                ->editColumn('date_start', function ($row) {
                    return $row->start_date ? date('d-m-Y', strtotime($row->start_date)) : '';
                })
                ->editColumn('date_end', function ($row) {
                    return $row->end_date ? date('d-m-Y', strtotime($row->end_date)) : '-';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded">เปิดใช้งาน</span>'
                        : '<span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded">ปิดใช้งาน</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="px-3 py-1 text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 rounded btn-edit" data-id="' . $row->id . '"><i class="fa-solid fa-gear"></i> แก้ไข</button>';
                })
                ->rawColumns(['device_key','date_start', 'date_end', 'status', 'action'])
                ->make(true);
        }

        $sideAtive = 'planting';

        return view('user.planting', compact('sideAtive'));
    }

    public function plantingData($id)
    {
        // ดึง user_sensor_id ที่มี useSensor.status = 1
        $excludedSensorIds = UserUseSensor::where('status', 1)
            ->pluck('user_sensors_id');

        // Query หลัก
        $plantingData = UserSensor::with('sensorKey', 'useSensor')
            ->where('user_id', $id)
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

    public function plantingAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|exists:user_sensors,id',
            'name' => 'required|string|max:255',
            'detail' => 'nullable|string|max:500',
            'date_start' => 'required|date',
        ])->setAttributeNames([
            'device_id' => 'Device ID',
            'name' => 'Name',
            'detail' => 'Detail',
            'date_start' => 'Start Date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sensorKey = UserSensor::whereId($request->input('device_id'))->first();

            if (!$sensorKey) {
                return response()->json([
                    'status' => false,
                    'message' => 'Device key not found'
                ], 404);
            }

            UserUseSensor::create([
                'user_id' => Auth::id(),
                'user_sensors_id' => $sensorKey->id,
                'name' => $request->input('name'),
                'detail' => $request->input('detail'),
                'start_date' => $request->input('date_start'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Planting data added successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add planting data'
            ], 500);
        }
    }
}
