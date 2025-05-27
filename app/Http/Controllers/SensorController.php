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

        $marker = UserUseSensor::with('userSensor', 'latestSensor')
            ->where('status', 1)
            ->when($request->input('province_code'), function ($query) use ($request) {
                return $query->whereHas('userSensor', function ($q) use ($request) {
                    $q->where('province_code', $request->input('province_code'));
                });
            })
            ->when($request->input('district_code'), function ($query) use ($request) {
                return $query->whereHas('userSensor', function ($q) use ($request) {
                    $q->where('district_code', $request->input('district_code'));
                });
            })
            ->when($request->input('subdistrict_code'), function ($query) use ($request) {
                return $query->whereHas('userSensor', function ($q) use ($request) {
                    $q->where('subdistrict_code', $request->input('subdistrict_code'));
                });
            })
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Area received successfully',
            'data' => $marker->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'lat' => $item->userSensor->lat,
                    'lon' => $item->userSensor->lon,
                ];
            })
        ]);
    }
}
