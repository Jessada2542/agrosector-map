<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorKey;
use App\Models\SensorTest;
use App\Models\UserUseSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SensorController extends Controller
{
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'n' => 'required',
            'p' => 'required',
            'k' => 'required',
            'ph' => 'required',
            'ec' => 'required',
            'temperature' => 'required',
            'humidity' => 'required',
        ])->setAttributeNames([
            'n' => 'Nitrogen',
            'p' => 'Phosphorus',
            'k' => 'Potassium',
            'ph' => 'pH',
            'ec' => 'Electrical Conductivity',
            'temperature' => 'Temperature',
            'humidity' => 'Humidity',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->first()
            ], 422);
        }

        SensorTest::create([
            'n' => $request->input('n'),
            'p' => $request->input('p'),
            'k' => $request->input('k'),
            'ph' => $request->input('ph'),
            'ec' => $request->input('ec'),
            'temperature' => $request->input('temperature'),
            'humidity' => $request->input('humidity'),
        ]);

        return response()->json(['status' => true, 'message' => 'Sensor data processed successfully']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'sensor_key' => 'required|exists:sensor_keys,key',
            'n' => 'required',
            'p' => 'required',
            'k' => 'required',
            'ph' => 'required',
            'ec' => 'required',
            'temperature' => 'required',
            'humidity' => 'required',
        ])->setAttributeNames([
            'user_id' => 'User',
            'sensor_key' => 'Sensor Key',
            'n' => 'Nitrogen',
            'p' => 'Phosphorus',
            'k' => 'Potassium',
            'ph' => 'pH',
            'ec' => 'Electrical Conductivity',
            'temperature' => 'Temperature',
            'humidity' => 'Humidity',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->first(),
                'data' => $request->all()
            ], 422);
        }

        $sensorKey = SensorKey::where('key', $request->input('sensor_key'))->first();
        if (!$sensorKey) {
            return response()->json(['status' => false, 'message' => 'Invalid sensor key'], 422);
        }

        if ($sensorKey->is_active != 1) {
            return response()->json(['status' => true, 'message' => 'Sensor key is not active']);
        }

        $useUserSensor = UserUseSensor::where('user_id', $request->input('user_id'))
            ->where('user_sensors_id', $sensorKey->id)
            ->where('status', 1)
            ->first();

        if (!$useUserSensor) {
            return response()->json([
                'status' => false,
                'message' => 'Sensor is not assigned to this user.'
            ], 422);
        }

        Sensor::create([
            'user_id' => $request->input('user_id'),
            'use_user_sensor_id' => $useUserSensor->id,
            'sensor_key_id' => $sensorKey->id,
            'n' => $request->input('n'),
            'p' => $request->input('p'),
            'k' => $request->input('k'),
            'ph' => $request->input('ph'),
            'ec' => $request->input('ec'),
            'temperature' => $request->input('temperature'),
            'humidity' => $request->input('humidity'),
        ]);

        return response()->json(['status' => true, 'message' => 'Sensor data processed successfully']);
    }

    public function generateSensor()
    {
        $prefix = 'SN';
        $date = date('Ymd');
        $random = strtoupper(Str::random(4));

        $serialNumber = $prefix . '-' . $date . '-' . $random;

        $sensor = [
            'key' => $serialNumber,
        ];

        SensorKey::create($sensor);
        return response()->json([
            'status' => true,
            'message' => 'Sensor key generated successfully'
        ]);
    }

    public function marker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_code' => 'nullable',
            'district_code' => 'nullable',
            'subdistrict_code' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->first()
            ], 422);
        }

        $markerQuery = UserUseSensor::with('userSensor', 'latestSensor')
            ->where('status', 1);

        if ($request->filled('subdistrict_code')) {
            $markerQuery->whereHas('userSensor', function ($q) use ($request) {
                $q->where('subdistrict_code', $request->input('subdistrict_code'));
            });
        } elseif ($request->filled('district_code')) {
            $markerQuery->whereHas('userSensor', function ($q) use ($request) {
                $q->where('district_code', $request->input('district_code'));
            });
        } elseif ($request->filled('province_code')) {
            $markerQuery->whereHas('userSensor', function ($q) use ($request) {
                $q->where('province_code', $request->input('province_code'));
            });
        }

        $marker = $markerQuery->get();

        return response()->json([
            'status' => true,
            'message' => 'Area received successfully',
            'data' => $marker->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'lon' => floatval($item->userSensor->lon),
                    'lat' => floatval($item->userSensor->lat),
                ];
            })
        ]);
    }

    public function data($id)
    {
        $sensorData = UserUseSensor::with([
            'user',
            'userSensor',
            'userSensor.province',
            'userSensor.district',
            'userSensor.subdistrict',
            'latestSensor',
            'sensors' => function ($query) {
                $query->where('created_at', '>=', Carbon::now('Asia/Bangkok')->subDays(7)->toDateTimeString());
            }
        ])->whereId($id)->first();

        if (!$sensorData) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $sensorData,
            'datetime' => Carbon::now('Asia/Bangkok')->toDateTimeString(),
            'subday' => Carbon::now('Asia/Bangkok')->subDays(7)->toDateTimeString()
        ]);
    }

    public function updateConnect(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sensor_key' => 'required|exists:sensor_keys,key',
        ])->setAttributeNames([
            'sensor_key' => 'Sensor Key',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->first()
            ], 422);
        }

        $sensorKey = SensorKey::where('key', $request->input('sensor_key'))->first();
        if (!$sensorKey) {
            return response()->json(['status' => false, 'message' => 'Invalid sensor key'], 422);
        }

        $sensorKey->update(['updated_at' => Carbon::now()]);

        return response()->json(['status' => true, 'message' => 'Sensor connected successfully']);
    }
}
