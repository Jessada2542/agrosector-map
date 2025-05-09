<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $isActive = 'dashboard';

        return view('dashboard.index', compact('isActive'));
    }
}
