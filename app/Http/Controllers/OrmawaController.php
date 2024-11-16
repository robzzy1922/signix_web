<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
class OrmawaController extends Controller
{
    public function dashboard()
    {
        return view('user.ormawa.ormawa_dashboard');
    }

    public function pengajuan()
    {
        $ormawa = Auth::guard('ormawa')->user();
        $dosenList = Dosen::all();
        return view('user.ormawa.pengajuan_ormawa', compact('dosenList', 'ormawa'));
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
