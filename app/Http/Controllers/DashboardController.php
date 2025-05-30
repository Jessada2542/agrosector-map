<?php

namespace App\Http\Controllers;

use App\Models\PlantingReport;
use App\Models\Sensor;
use App\Models\SensorKey;
use App\Models\SensorTest;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Sensor::where('use_user_sensor_id', $request->input(('device_id')))
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('datetime', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->rawColumns(['datetime'])
                ->make(true);
        }

        $sensor = UserUseSensor::with('latestSensor')
            ->where('user_id', Auth::id())
            ->get();

        $sideAtive = 'dashboard';

        return view('dashboard.index', compact('sideAtive', 'sensor'));
    }

    public function plantingReport(Request $request)
    {
        if ($request->ajax()) {
            $query = PlantingReport::with('plantingImage')
                ->where('use_user_sensor_id', $request->input(('device_id')))
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $images = $row->plantingImage->pluck('image')->toArray();
                    if (count($images) > 0) {
                        return '<img src="' . asset('images/platnings/' . $images[0]) . '" class="img-thumbnail" style="width: 50px; height: 50px;">';
                    }
                    return '';
                })
                ->editColumn('datetime', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->rawColumns(['image', 'datetime'])
                ->make(true);
        }
    }

    public function data($id)
    {
        $sensorData = UserUseSensor::whereId($id)->first();
        if (!$sensorData) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        $plantingReport = PlantingReport::where('use_user_sensor_id', $id)
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => true,
            'data' => $sensorData,
            'planting_report' => $plantingReport,
        ]);
    }
}
