<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingSensorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

        }
        $sideActive = 'setting';

        return view('admin.sensor', compact('sideActive'));
    }

    public function data(Request $request)
    {
        // Logic to fetch sensor data
        return response()->json(['status' => true, 'data' => []]);
    }

    public function update(Request $request)
    {
        // Logic to update sensor settings
        return response()->json(['status' => true, 'message' => 'Settings updated successfully']);
    }
}
