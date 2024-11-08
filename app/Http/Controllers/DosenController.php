<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function dashboardDosen()
    {
        return view('user.dosen.dashboard_dosen');
    }
}
