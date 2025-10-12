<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggedIn
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // If user has admin role (role_id > 1) redirect to admin area
            if (Auth::user()->role_id > 1) {
                return redirect()->route('admin.index');
            }

            return redirect()->route('map.index');
        }

        return $next($request);
    }
}
