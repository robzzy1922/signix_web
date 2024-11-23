<?php

namespace App\Http\Controllers\Admin\Auth;


use App\Models\Ormawas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminOrmawaController extends Controller
{
    public function index()
    {
        $ormawas = Ormawas::paginate(10);
        return view('admin.ormawa.index', compact('ormawas'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'namaMahasiswa' => 'required|string|max:255',
                'namaOrmawa' => 'required|string|max:255',
                'nim' => 'required|string|max:8|unique:ormawas,nim',
                'email' => 'required|email|unique:ormawas,email',
                'noHp' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
                'password' => 'required|string|min:6',
                'profile' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ], [
                'namaMahasiswa.required' => 'Nama mahasiswa wajib diisi',
                'namaMahasiswa.max' => 'Nama mahasiswa maksimal 255 karakter',
                'namaOrmawa.required' => 'Nama organisasi wajib diisi',
                'namaOrmawa.max' => 'Nama organisasi maksimal 255 karakter',
                'nim.required' => 'NIM wajib diisi',
                'nim.max' => 'NIM maksimal 8 karakter',
                'nim.unique' => 'NIM sudah terdaftar',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'noHp.required' => 'Nomor HP wajib diisi',
                'noHp.max' => 'Nomor HP maksimal 15 karakter',
                'noHp.regex' => 'Nomor HP hanya boleh berisi angka',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 6 karakter',
                'profile.required' => 'Foto profil wajib diisi',
                'profile.image' => 'File harus berupa gambar',
                'profile.mimes' => 'Format gambar harus jpeg, png, atau jpg',
                'profile.max' => 'Ukuran gambar maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Handle file upload
            if ($request->hasFile('profile')) {
                $profileFile = $request->file('profile');
                $profileName = time() . '.' . $profileFile->getClientOriginalExtension();
                $profileFile->move(public_path('profiles'), $profileName);
            }

            // Create new Ormawa
            $ormawa = Ormawas::create([
                'namaMahasiswa' => $request->namaMahasiswa,
                'namaOrmawa' => $request->namaOrmawa,
                'nim' => $request->nim,
                'email' => $request->email,
                'noHp' => $request->noHp,
                'password' => Hash::make($request->password),
                'profile' => $profileName ?? null
            ]);

            return redirect()->route('admin.ormawa.index')
                ->with('success', 'Ormawa berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Log error
            Log::error($e->getMessage());

            return redirect()->back()
                ->withErrors(['exception' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function create()
    {
        return view('admin.ormawa.create');
    }
}