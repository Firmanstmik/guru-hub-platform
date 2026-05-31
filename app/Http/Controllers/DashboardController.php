<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardAdmin()
    {
        return view('admin.dashboard');
    }
    public function dashboardGuru()
    {
        return view('guru.dashboard');
    }
    public function dashboardSiswa()
    {
        return view('student.dashboard');
    }
}
