<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\UserUseSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PlantingReport;

class PlantingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = UserUseSensor::with('user', 'userSensor.sensorKey');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return $row->user->name;
                })
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
                    return '<button class="btn-info bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded flex items-center gap-1" data-id="'. $row->id .'">
                        <i class="fa-regular fa-circle-question"></i> ดูข้อมูล
                    </button>';
                })
                ->rawColumns(['user_name', 'device_key', 'date_start', 'date_end', 'status', 'action'])
                ->make(true);
        }

        $sideActive = 'planting';

        return view('admin.planting', compact('sideActive'));
    }

    public function data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_use_sensors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $sensorData = UserUseSensor::whereId($request->id)->first();
        if (!$sensorData) {
            return response()->json(['error' => 'Sensor not found'], 404);
        }

        $plantingReport = PlantingReport::where('use_user_sensor_id', $request->id)->get();

        return response()->json([
            'status' => true,
            'data' => $sensorData,
            'planting_report' => $plantingReport,
        ]);
    }

    public function dataSensor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_use_sensors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->ajax()) {
            $query = Sensor::where('use_user_sensor_id', $request->input('id'))
                ->orderBy('created_at', 'desc');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('datetime', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
                ->rawColumns(['datetime'])
                ->make(true);
        }
    }

    public function dataReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_use_sensors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->ajax()) {
            $query = PlantingReport::with('plantingImage')
                ->where('use_user_sensor_id', $request->input('id'))
                ->orderBy('created_at', 'desc');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $images = $row->plantingImage->pluck('image')->toArray();
                    if (count($images) > 0) {
                        $imageHtml = '';
                        foreach ($images as $image) {
                            $url = asset('images/platnings/' . $image);
                            $imageHtml .= '<img src="' . $url . '" width="50" height="50" class="inline-block mr-2 rounded border preview-image" style="cursor: pointer;">';
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
}
