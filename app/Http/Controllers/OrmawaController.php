<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrmawaController extends Controller
{
    public function dashboard()
    {
        return view('user.ormawa.ormawa_dashboard');
    }

    public function pengajuan()
    {
        return view('user.ormawa.pengajuan_ormawa');
    }

    public function storePengajuan(Request $request)
    {
        dd($request->all());
        
    }

    public function riwayat()
    {
        return view('user.ormawa.riwayat_ormawa');
    }
}
