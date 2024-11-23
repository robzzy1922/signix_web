<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDosenController extends Controller
{
    public function index()
    {
        return view('admin.dosen.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telepon' => 'nullable|string|max:15',
        ]);

        // Simpan data Dosen (simulasi)
        // Dosen::create($request->all());

        return redirect()->route('admin.adminDashboard')->with('success', 'Dosen berhasil ditambahkan!');
    }
}