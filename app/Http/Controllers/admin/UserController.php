<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
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

    public function data(Request $request)
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

    public function store(Request $request)
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

    public function update(Request $request)
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
}
