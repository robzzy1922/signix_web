<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrmawaController extends Controller
{
    public function create()
    {
        return view('admin.ormawa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        // Simpan data Ormawa (simulasi)
        // Ormawa::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Ormawa berhasil ditambahkan!');
    }
}