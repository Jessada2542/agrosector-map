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
