<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorKey;
use App\Models\SensorReading;
use App\Models\SensorTest;
use App\Models\UserSensor;
use App\Models\UserUseSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
            'air_humidity' => 'nullable',
            'air_temperature' => 'nullable',
            'light' => 'nullable'
        ])->setAttributeNames([
            'n' => 'Nitrogen',
            'p' => 'Phosphorus',
            'k' => 'Potassium',
            'ph' => 'pH',
            'ec' => 'Electrical Conductivity',
            'temperature' => 'Temperature',
            'humidity' => 'Humidity',
            'air_humidity' => 'Air Humidity',
            'air_temperature' => 'Air Temperature',
            'light' => 'Light'
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
            'air_humidity' => $request->input('air_humidity'),
            'air_temperature' => $request->input('air_temperature'),
            'light' => $request->input('light')
        ]);

        return response()->json(['status' => true, 'message' => 'Sensor data processed successfully']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'sensor_key' => 'required|exists:sensor_keys,key',
            'n' => 'nullable',
            'p' => 'nullable',
            'k' => 'nullable',
            'ph' => 'nullable',
            'ec' => 'nullable',
            'temperature' => 'nullable',
            'humidity' => 'nullable',
            'air_humidity' => 'nullable',
            'air_temperature' => 'nullable',
            'light' => 'nullable',
            'co2' => 'nullable',
            'nh3' => 'nullable',
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
            'air_humidity' => 'Air Humidity',
            'air_temperature' => 'Air Temperature',
            'light' => 'Light',
            'co2' => 'CO2',
            'nh3' => 'NH3'
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

        $userSensor = UserSensor::where('user_id', $request->input('user_id'))
            ->where('sensor_key_id', $sensorKey->id)
            ->first();

        $useUserSensor = UserUseSensor::where('user_id', $request->input('user_id'))
            ->where('user_sensors_id', $userSensor->id)
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
            'ec' => $request->input('ec') ? round($request->input('ec') * 10, 2) : null,
            'temperature' => $request->input('temperature'),
            'humidity' => $request->input('humidity'),
            'air_humidity' => $request->input('air_humidity'),
            'air_temperature' => $request->input('air_temperature'),
            'light' => $request->input('light'),
            'co2' => $request->input('co2'),
            'nh3' => $request->input('nh3'),
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
        if (Auth::check() && in_array(Auth::user()->role_id, [2, 3])) {
            $sensorData = UserUseSensor::with([
                'user',
                'userSensor',
                'userSensor.province',
                'userSensor.district',
                'userSensor.subdistrict',
                'latestSensor',
                'sensors'
            ])->whereId($id)->first();
        } else {
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
        }

        if (!$sensorData) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $sensorData,
            'datetime' => Carbon::now('Asia/Bangkok')->toDateTimeString()
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

    public function isilk(Request $request)
    {
        $query = SensorReading::query();

        if($request->filled('from') && $request->filled('to')){
            $query->whereDate('datetime', '>=', $request->from)
                ->whereDate('datetime', '<=', $request->to);
        }

        $data = $query->orderBy('datetime')->get();

        $labels = $data->pluck('datetime')->map(fn($d) => date('d-m-Y H:i', strtotime($d)));
        $humid_out = $data->pluck('humid_out');
        $humid_in = $data->pluck('humid_in');
        $temp_out = $data->pluck('temp_out');
        $temp_in = $data->pluck('temp_in');
        $tan = $data->pluck('tan');
        $nh3 = $data->pluck('nh3');
        $light_out = $data->pluck('lux_out');
        $light_in = $data->pluck('lux_in');

        $humid_combined = [];
        $temp_combined = [];
        $tan_nh3_combined = [];
        $light_combined = [];
        foreach ($humid_out as $k => $v) {
            $humid_combined[] = $v + ($humid_in[$k] ?? 0);
        }
        foreach ($temp_out as $k => $v) {
            $temp_combined[] = $v + ($temp_in[$k] ?? 0);
        }
        foreach ($tan as $key => $value) {
            $tan_nh3_combined[] = $value + ($nh3[$key] ?? 0);
        }
        foreach ($light_out as $key => $value) {
            $light_combined[] = $value + ($light_in[$key] ?? 0);
        }

        return view('chart', compact(
            'labels',
            'humid_out',
            'humid_in',
            'temp_out',
            'temp_in',
            'tan',
            'nh3',
            'light_out',
            'light_in',
            'humid_combined',
            'temp_combined',
            'tan_nh3_combined',
            'light_combined'
        ));
    }
}
