<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function create()
    {
        return view('admin.dosen.create');
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

        return redirect()->route('admin.dashboard')->with('success', 'Dosen berhasil ditambahkan!');
    }
}