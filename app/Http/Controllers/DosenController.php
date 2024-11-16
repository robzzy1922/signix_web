<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function dashboardDosen()
    {
        return view('user.dosen.dashboard_dosen');
    }

    public function create()
    {
        return view('user.dosen.create_tandatangan');
    }

    public function riwayat()
    {
        return view('user.dosen.riwayat_dosen');
    }
}
