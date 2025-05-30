<?php

namespace App\Http\Controllers;

use App\Models\PlantingImage;
use App\Models\PlantingReport;
use App\Models\Sensor;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
                        $imageHtml = '';
                        foreach ($images as $image) {
                            $imageHtml .= '<img src="' . asset('images/platnings/' . $image) . '" width="50" height="50" class="img-thumbnail me-1" style="display: inline-block;">';
                        }
                        return $imageHtml;
                    }
                    return 'ไม่มีรูปภาพ';
                })
                ->editColumn('datetime', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->rawColumns(['image', 'datetime'])
                ->make(true);
        }
    }

    public function plantingReportCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'use_user_sensor_id' => 'required|exists:user_use_sensors,id',
            'detail' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $reportId = PlantingReport::create([
            'use_user_sensor_id' => $request->input('use_user_sensor_id'),
            'user_id' => Auth::id(),
            'detail' => $request->input('detail'),
        ])->id;

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/platnings'), $filename);
                $images[] = [
                    'planting_report_id' => $reportId,
                    'image' => $filename,
                ];
            }
            PlantingImage::insert($images);
        }

        return response()->json(['status' => true, 'message' => 'Planting report created successfully']);
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
