<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::where('username', $request->username)->first();

        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            if (!$user || !Hash::check($request->password, $user->password)) {
                // ถ้า Auth::attempt() สำเร็จ
                $user = User::where('username', $request->username)->firstOrFail();

                // สร้าง Token สำหรับการใช้งาน API
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Login success',
            'role' => $user->role_id,
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
