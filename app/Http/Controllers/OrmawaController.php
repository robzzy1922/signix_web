<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Dokumen;
use App\Models\Ormawas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrmawaController extends Controller
{
    public function dashboard(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $dokumens = Dokumen::query()
            ->when($status, function ($query) use ($status) {
                return $query->where('status_dokumen', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('tanggal_pengajuan', 'like', "%{$search}%")
                      ->orWhere('perihal', 'like', "%{$search}%")
                      ->orWhereHas('dosen', function ($q) use ($search) {
                          $q->where('nama_dosen', 'like', "%{$search}%");
                      })
                      ->orWhere('status_dokumen', 'like', "%{$search}%");
                });
            })
            ->get();

        $countDiajukan = Dokumen::where('status_dokumen', 'diajukan')->count();
        $countDisahkan = Dokumen::where('status_dokumen', 'disahkan')->count();
        $countRevisi = Dokumen::where('status_dokumen', 'direvisi')->count();

        return view('user.ormawa.ormawa_dashboard', compact('dokumens', 'status', 'countDiajukan', 'countDisahkan', 'countRevisi'));
    }

    public function pengajuan()
    {
        $ormawa = Auth::guard('ormawa')->user();
        $dosenList = Dosen::all();
        return view('user.ormawa.pengajuan_ormawa', compact('dosenList', 'ormawa'));
    }

    public function storePengajuan(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'nomor_surat' => 'required|string|max:255',
                'kepada_tujuan' => 'required|exists:dosen,id',
                'perihal' => 'required|string|max:255',
                'unggah_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'keterangan' => 'nullable|string',
            ]);

            // Handle file upload
            if ($request->hasFile('unggah_dokumen')) {
                $file = $request->file('unggah_dokumen');
                $filePath = $file->store('dokumen', 'public');
            } else {
                throw new \Exception('File tidak ditemukan');
            }

            // Save to the database dengan menggunakan create
            $dokumen = new Dokumen();
            $dokumen->nomor_surat = $request->nomor_surat;
            $dokumen->perihal = $request->perihal;
            $dokumen->file = $filePath;
            $dokumen->keterangan = $request->keterangan;
            $dokumen->tanggal_pengajuan = now();
            $dokumen->status_dokumen = 'diajukan';
            $dokumen->id_ormawa = Auth::guard('ormawa')->user()->id;
            $dokumen->id_dosen = $request->kepada_tujuan;

            if (!$dokumen->save()) {
                throw new \Exception('Gagal menyimpan data ke database');
            }

            return redirect()->route('ormawa.ormawa_ashboard')->with('success', 'Pengajuan berhasil diajukan.');
        } catch (\Exception $e) {
            Log::error('Error in storePengajuan: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function riwayat(Request $request)
    {
        $ormawa = Auth::guard('ormawa')->user();
        $status = $request->input('status_dokumen');

        $query = Dokumen::where('id_ormawa', $ormawa->id)->with('dosen');

        if ($status) {
            $query->where('status_dokumen', $status);
        }

        $dokumens = $query->get();

        return view('user.ormawa.riwayat_ormawa', compact('dokumens'));
    }

    public function getDokumenContent($id)
    {
        $dokumen = Dokumen::find($id);
        if ($dokumen) {
            $filePath = storage_path('app/public/' . $dokumen->file);
            if (file_exists($filePath)) {
                return response()->file($filePath);
            }
            return response()->json(['error' => 'File not found'], 404);
        }
        return response()->json(['error' => 'Document not found'], 404);
    }

    public function profile()
    {
        $ormawa = Auth::guard('ormawa')->user();
        if (!$ormawa) {
            return redirect()->route('login')->withErrors('User not authenticated.');
        }
        return view('user.ormawa.profile', compact('ormawa'));
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ormawa = Auth::guard('ormawa')->user();

        // Delete old photo if exists
        if ($ormawa->profile) {
            Storage::delete($ormawa->profile);
        }

        // Store new photo
        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        // Update user profile photo path
        $ormawa->profile = $path;
        $ormawa->save();

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }

    public function removeProfilePhoto()
    {
        $ormawa = auth()->user();

        // Delete photo if exists
        if ($ormawa->profile) {
            Storage::delete($ormawa->profile);
            $ormawa->profile = null;
            $ormawa->save();
        }

        return redirect()->back()->with('success', 'Profile photo removed successfully.');
    }

    public function viewPhoto()
    {
        $ormawa = auth()->user()->ormawa;

        if ($ormawa->profile) {
            return response()->file(storage_path('app/' . $ormawa->profile));
        }

        return redirect()->back()->with('error', 'No profile photo to view.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ormawa = auth()->user()->ormawa; // Assuming the user is related to Ormawa
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');
            $ormawa->profile = $path;
            $ormawa->save();
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }

    public function showProfile()
    {
        $ormawa = Auth::guard('ormawa')->user(); // Example of fetching the Ormawa model
        return view('user.ormawa.profile', compact('ormawa'));
    }

    public function editProfile()
    {
        $ormawa = Auth::guard('ormawa')->user();
        return view('user.ormawa.edit_profile', compact('ormawa'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
        ]);

        $ormawa = Auth::guard('ormawa')->user();
        $ormawa->namaMahasiswa = $request->input('name');
        $ormawa->noHp = $request->input('phone');
        $ormawa->email = $request->input('email');
        $ormawa->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}