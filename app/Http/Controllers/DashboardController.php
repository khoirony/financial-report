<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function dashboardAdmin()
    {
        return view('admin.dashboard');
    }
}
