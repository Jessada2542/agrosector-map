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

        // Attempt to authenticate using the web guard (session driver)
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Regenerate the session ID to prevent session fixation and persist login
        $request->session()->regenerate();

        // Now Auth::user() will return the authenticated user on subsequent requests
        $user = Auth::user();

        // If this is an AJAX/API login you may want to return JSON; for typical
        // browser-based login redirect to the intended page so the session cookie is set.
        if ($request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Login success',
                'role' => $user->role_id,
            ], Response::HTTP_OK);
        }

        return redirect()->intended(route('map.index'));
    }

    public function logout()
    {
        Auth::logout();

        // Invalidate the session and regenerate token to fully logout the user
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
