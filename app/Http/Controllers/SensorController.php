<?php

namespace App\Http\Controllers;

use App\Models\SensorKey;
use App\Models\SensorTest;
use App\Models\UserUseSensor;
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
            'userSensor',
            'latestSensor',
            'sensors' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(15));
            }
        ])->whereId($id)->first();

        if (!$sensorData) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $sensorData,
        ]);
    }
}
