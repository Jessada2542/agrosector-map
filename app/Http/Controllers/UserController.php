<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = User::whereId(Auth::id())->first();
        $sideAtive = 'user';

        return view('user.index', compact('sideAtive', 'data'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ])->setAttributeNames([
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::whereId($id)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $avatarName = NULL;
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
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update user'
            ], 500);
        }
    }
}
