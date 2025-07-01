<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GeoCode;
use App\Models\User;
use App\Models\UserUseSensor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MapController extends Controller
{
    public function index()
    {
        $provinces = GeoCode::select('province_code as id', 'province_name_th as name')
            ->groupBy('province_code', 'province_name_th')
            ->get();
        $districts = GeoCode::select('district_code as id', 'district_name_th as name')
            ->groupBy('district_code', 'district_name_th')
            ->get();
        $subdistricts = GeoCode::select('subdistrict_code as id', 'subdistrict_name_th as name')
            ->groupBy('subdistrict_code', 'subdistrict_name_th')
            ->get();
        $sideActive = 'map';

        return view('admin.map', compact('sideActive', 'provinces', 'districts', 'subdistricts'));
    }

    public function dashboard(Request $request)
    {
        if ($request->ajax()) {
            $query = UserUseSensor::with('user', 'latestSensor');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->editColumn('n', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->n : '';
                })
                ->editColumn('p', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->p : '';
                })
                ->editColumn('k', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->k : '';
                })
                ->editColumn('ph', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->ph : '';
                })
                ->editColumn('datetime', function ($row) {
                    return $row->latestSensor ? $row->latestSensor->created_at->format('d-m-Y H:i') : '';
                })
                ->editColumn('date_start', function ($row) {
                    return $row->start_date ? Carbon::parse($row->start_date)->format('d-m-Y') : '';
                })
                ->editColumn('date_end', function ($row) {
                    return $row->end_date ? Carbon::parse($row->end_date)->format('d-m-Y') : '';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn-info bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded flex items-center gap-1" data-id="'. $row->id .'">
                        <i class="fa-regular fa-circle-question"></i> ดูข้อมูล
                    </button>';
                })
                ->rawColumns(['user_name', 'n', 'p', 'k', 'ph', 'datetime', 'date_start', 'date_end', 'action'])
                ->make(true);
        }

        $sideActive = 'dashboard';

        return view('admin.dashboard', compact('sideActive'));
    }

    public function dashboardData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:user_use_sensors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = UserUseSensor::with(
            'user',
            'userSensor',
            'userSensor.province',
            'userSensor.district',
            'userSensor.subdistrict',
            'userSensor.sensorKey',
            'latestSensor'
            )
            ->whereId($request->id)
            ->first();

        $subdistrict = $data->userSensor->subdistrict->subdistrict_name_th ?? '';
        $district = $data->userSensor->district->district_name_th ?? '';
        $province = $data->userSensor->province->province_name_th ?? '';
        $province_code = $data->userSensor->province->province_code ?? '';

        return response()->json([
            'status' => true,
            'data' => [
                'user_name' => $data->user->name ?? '',
                'name' => $data->name ?? '',
                'position' => $data->userSensor ? $data->userSensor->lat . ', ' . $data->userSensor->lon : '',
                'address' => 'ตำบล' . $subdistrict . ' อำเภอ' . $district . ' จังหวัด' . $province . ' ' . $province_code . '000',
                'detail' => $data->detail ?? '',
                'sensor' => $data->userSensor->sensorKey->key ?? '',
                'n' => $data->latestSensor->n ?? '',
                'p' => $data->latestSensor->p ?? '',
                'k' => $data->latestSensor->k ?? '',
                'ph' => $data->latestSensor->ph ?? '',
                'datetime' => $data->latestSensor?->created_at?->format('d-m-Y H:i') ?? '',
                'date_start' => $data->start_date ? Carbon::parse($data->start_date)->format('d-m-Y') : '',
                'date_end' => $data->end_date ? Carbon::parse($data->end_date)->format('d-m-Y') : ''
            ]
        ]);
    }

    public function users(Request $request)
    {
        if ($request->ajax()) {
            $query = User::where('role_id', 1);

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = $row->avatar ? asset('images/avatars/' . $row->avatar) : asset('images/avatars/No_image.png');
                    return '<img src="' . $url . '" class="w-10 h-10 rounded-full object-cover" alt="' . e($row->name) . '">';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d-m-Y H:i') : '';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn-info bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 border border-yellow-700 rounded" data-id="'. $row->id .'">
                        <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                    </button>';
                })
                ->rawColumns(['image', 'created_at', 'action'])
                ->make(true);
        }

        $sideActive = 'users';

        return view('admin.users', compact('sideActive'));
    }

    public function usersData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = User::findOrFail($request->id);

        return response()->json([
            'status' => true,
            'data' => [
                'image' => $data->avatar ? asset('images/avatars/' . $data->avatar) : asset('images/avatars/No_image.png'),
                'name' => $data->name,
                'username' => $data->username,
                'email' => $data->email,
                'phone' => $data->phone,
                'address' => $data->address,
                'created_at' => $data->created_at ? $data->created_at->format('d-m-Y H:i') : '',
            ]
        ]);
    }

    public function usersStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images/avatars'), $avatarName);
        }

        User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'address' => $request->input('address'),
            'avatar' => $avatarName,
        ]);

        return response()->json(['status' => true, 'message' => 'User created successfully']);
    }

    public function usersUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::whereId($request->id)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $avatarName = $user->avatar;
        if ($request->hasFile('avatar')) {
            $oldAvatarPath = public_path('images/avatars/' . $user->avatar);
            if ($user->avatar && file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images/avatars'), $avatarName);
        }

        $user->update([
            'avatar' => $avatarName ? $avatarName : $user->avatar,
            'name' => $request->input('name'),
            'address' => $request->input('address'),
        ]);

        return response()->json(['status' => true, 'message' => 'User updated successfully']);
    }

    public function planting(Request $request)
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
}
