<?php

namespace App\Http\Controllers;

use App\Models\SensorTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    public function test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sensor_data' => 'required|array',
            /* 'sensor_data.*.sensor_key_id' => 'required|exists:sensor_keys,id', */
            'sensor_data.*.n' => 'required|numeric',
            'sensor_data.*.p' => 'required|numeric',
            'sensor_data.*.k' => 'required|numeric',
            'sensor_data.*.ph' => 'required|numeric',
            'sensor_data.*.ec' => 'required|numeric',
            'sensor_data.*.temperature' => 'required|numeric',
            'sensor_data.*.humidity' => 'required|numeric',
        ])->setAttributeNames([
            /* 'sensor_data.*.sensor_key_id' => 'Sensor Key ID', */
            'sensor_data.*.n' => 'Nitrogen',
            'sensor_data.*.p' => 'Phosphorus',
            'sensor_data.*.k' => 'Potassium',
            'sensor_data.*.ph' => 'pH Level',
            'sensor_data.*.ec' => 'Electrical Conductivity',
            'sensor_data.*.temperature' => 'Temperature',
            'sensor_data.*.humidity' => 'Humidity'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->first()
            ], 422);
        }

        foreach ($request->sensor_data as $value) {
            SensorTest::create([
                'n' => $value['n'],
                'p' => $value['p'],
                'k' => $value['k'],
                'ph' => $value['ph'],
                'ec' => $value['ec'],
                'temperature' => $value['temperature'],
                'humidity' => $value['humidity'],
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Sensor data processed successfully']);
    }
}
