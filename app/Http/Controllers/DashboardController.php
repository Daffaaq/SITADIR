<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Superadmin.Dashboard.index');
    }
    public function indexKaryawan()
    {
        return view('Karyawan.Dashboard.index');
    }
}
