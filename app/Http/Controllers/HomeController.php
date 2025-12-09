<?php

namespace App\Http\Controllers;

use App\Imports\GeoCodesImport;
use App\Models\GeoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index()
    {
        User::where('id', 1)->update(['username' => 'admin', 'password' => bcrypt('password')]);
        $provinces = GeoCode::select('province_code as id', 'province_name_th as name')
            ->groupBy('province_code', 'province_name_th')
            ->get();
        $districts = GeoCode::select('district_code as id', 'district_name_th as name')
            ->groupBy('district_code', 'district_name_th')
            ->get();
        $subdistricts = GeoCode::select('subdistrict_code as id', 'subdistrict_name_th as name')
            ->groupBy('subdistrict_code', 'subdistrict_name_th')
            ->get();

        return view('home', compact('provinces', 'districts', 'subdistricts'));
    }

    public function getDistricts(Request $request)
    {
        if ($request->province_code) {
            $districts = GeoCode::where('province_code', $request->input('province_code'))
                ->select('district_code as id', 'district_name_th as name')
                ->groupBy('district_code', 'district_name_th')
                ->get();
        } else {
            return response()->json(['error' => 'Province code is required'], 400);
        }

        if ($districts->isEmpty()) {
            return response()->json(['error' => 'No districts found for the given province code'], 404);
        }

        return response()->json($districts);
    }

    public function getSubDistricts(Request $request)
    {
        if ($request->district_code) {
            $subdistricts = GeoCode::where('district_code', $request->input('district_code'))
                ->select('subdistrict_code as id', 'subdistrict_name_th as name')
                ->groupBy('subdistrict_code', 'subdistrict_name_th')
                ->get();
        } else {
            return response()->json(['error' => 'District code is required'], 400);
        }

        if ($subdistricts->isEmpty()) {
            return response()->json(['error' => 'No subdistricts found for the given district code'], 404);
        }

        return response()->json($subdistricts);
    }



    public function import(Request $request)
    {
        // Validate the uploaded file
        /* $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]); */

        $filePath = public_path('data.csv');

        // Import the Excel file into the GeoCode model
        //Excel::import(new GeoCodesImport, $request->file('file'));
        Excel::import(new GeoCodesImport, $filePath);

        return back()->with('success', 'GeoCodes imported successfully!');
    }
}
