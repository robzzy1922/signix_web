<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Dokumen;
use App\Models\Ormawas;
use App\Models\Kemahasiswaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrmawaController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Dokumen::where('id_ormawa', auth()->guard('ormawa')->id());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status_dokumen', $request->status);
        }

        $dokumens = $query->latest()->get();

        $countDiajukan = Dokumen::where('id_ormawa', auth()->guard('ormawa')->id())
                                ->where('status_dokumen', 'diajukan')->count();
        $countDisahkan = Dokumen::where('id_ormawa', auth()->guard('ormawa')->id())
                                ->where('status_dokumen', 'disahkan')->count();
        $countButuhRevisi = Dokumen::where('id_ormawa', auth()->guard('ormawa')->id())
                                ->where('status_dokumen', 'butuh revisi')->count();
        $countRevisi = Dokumen::where('id_ormawa', auth()->guard('ormawa')->id())
                              ->where('status_dokumen', 'sudah direvisi')->count();

        return view('user.ormawa.ormawa_dashboard', compact(
            'dokumens',
            'countDiajukan',
            'countDisahkan',
            'countButuhRevisi',
            'countRevisi'
        ));
    }

    public function pengajuan()
    {
        $ormawa = Auth::guard('ormawa')->user();
        $dosenList = Dosen::all();
        $kemahasiswaanList = Kemahasiswaan::all();
        return view('user.ormawa.pengajuan_ormawa', compact('ormawa', 'dosenList', 'kemahasiswaanList'));
    }

    public function storePengajuan(Request $request)
    {
        // Tambahkan debug log
        Log::info('Received request data:', $request->all());

        try {
            // Validasi input
            $validated = $request->validate([
                'nomor_surat' => 'required|string|max:255',
                'nama_pengaju' => 'required|string|max:255',
                'nama_ormawa' => 'required|string|max:255',
                'tujuan_pengajuan' => 'required|in:dosen,kemahasiswaan',
                'kepada_tujuan' => 'required',
                'hal' => 'required|string|max:255',
                'unggah_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'catatan' => 'nullable|string',
            ]);

            // Handle file upload
            if ($request->hasFile('unggah_dokumen')) {
                $file = $request->file('unggah_dokumen');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('dokumen', $fileName, 'public');

                Log::info('File uploaded successfully:', ['path' => $filePath]);
            } else {
                throw new \Exception('File tidak ditemukan');
            }

            // Create new document
            $dokumen = new Dokumen();
            $dokumen->nomor_surat = $request->nomor_surat;
            $dokumen->perihal = $request->hal;
            $dokumen->file = $filePath;
            $dokumen->keterangan = $request->catatan;
            $dokumen->tanggal_pengajuan = now();
            $dokumen->status_dokumen = 'diajukan';
            $dokumen->id_ormawa = Auth::guard('ormawa')->id();
            
            // Set id_dosen atau id_kemahasiswaan berdasarkan tujuan
            if ($request->tujuan_pengajuan === 'dosen') {
                $dokumen->id_dosen = $request->kepada_tujuan;
            } else {
                $dokumen->id_kemahasiswaan = $request->kepada_tujuan;
            }

            // Save document
            $saved = $dokumen->save();
            Log::info('Document save attempt:', ['success' => $saved, 'document_id' => $dokumen->id]);

            if (!$saved) {
                throw new \Exception('Gagal menyimpan dokumen ke database');
            }

            return redirect()
                ->route('ormawa.pengajuan')
                ->with('success', 'Dokumen berhasil diajukan!');

        } catch (\Exception $e) {
            Log::error('Error in storePengajuan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function riwayat(Request $request)
    {
        $query = Dokumen::where('id_ormawa', auth()->guard('ormawa')->id());

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%")
                  ->orWhere('status_dokumen', 'like', "%$search%");
            });
        }
        // Apply status filter if exists
        if ($request->has('status') && $request->status != '') {
            $query->where('status_dokumen', $request->status);
        }

        $dokumens = $query->latest()->get();

        return view('user.ormawa.riwayat_ormawa', compact('dokumens'));
    }

    public function getDokumenContent($id)
    {
        $dokumen = Dokumen::find($id);
        if ($dokumen) {
            $filePath = storage_path('app/public/' . $dokumen->file);
            if (file_exists($filePath)) {
                return response()->download($filePath, $dokumen->nomor_surat . '.pdf');
            }
            return response()->json(['error' => 'File not found'], 404);
        }
        return response()->json(['error' => 'Document not found'], 404);
    }

    public function profile()
    {
        $ormawa = Auth::guard('ormawa')->user();
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
            'namaMahasiswa' => 'required|string|max:255',
            'email' => 'required|email',
            'noHp' => 'required|string|max:15',
        ]);

        $ormawa = Auth::guard('ormawa')->user();
        $ormawa->update([
            'namaMahasiswa' => $request->namaMahasiswa,
            'email' => $request->email,
            'noHp' => $request->noHp,
        ]);
        $ormawa->save();

        return redirect()->route('ormawa.profile')->with('success', 'Profile updated successfully');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:1024'] // 1MB Max
        ]);

        $ormawa = Auth::guard('ormawa')->user();

        if ($ormawa->profile) {
            Storage::disk('public')->delete($ormawa->profile);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $ormawa->update([
            'profile' => $path
        ]);

        return back()->with('success', 'Profile photo updated successfully');
    }

    public function destroyPhoto()
    {
        $ormawa = Auth::guard('ormawa')->user();

        if ($ormawa->profile) {
            Storage::disk('public')->delete($ormawa->profile);

            $ormawa->update([
                'profile' => null
            ]);
        }

        return back()->with('success', 'Profile photo removed successfully');
    }

    public function logout()
    {
        Auth::guard('ormawa')->logout();
        return redirect()->route('login');
    }

    public function showDokumen($id)
    {
        try {
            $dokumen = Dokumen::with(['dosen', 'ormawa'])
                ->where('id', $id)
                ->where('id_ormawa', auth()->guard('ormawa')->id())
                ->firstOrFail();

            return response()->json([
                'id' => $dokumen->id,
                'nomor_surat' => $dokumen->nomor_surat,
                'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
                'perihal' => $dokumen->perihal,
                'status_dokumen' => $dokumen->status_dokumen,
                'keterangan_revisi' => $dokumen->keterangan_revisi,
                'file_url' => asset('storage/' . $dokumen->file)
            ]);
        } catch (\Exception $e) {
            Log::error('Error in showDokumen: ' . $e->getMessage());
            return response()->json(['error' => 'Dokumen tidak ditemukan'], 404);
        }
    }

    public function updateDokumen(Request $request, $id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            // Validate request
            $request->validate([
                'dokumen' => 'required|file|mimes:pdf|max:2048'
            ]);

            // Delete old file if exists
            if ($dokumen->file && Storage::disk('public')->exists($dokumen->file)) {
                Storage::disk('public')->delete($dokumen->file);
            }

            // Store new file dengan nama yang lebih terstruktur
            $file = $request->file('dokumen');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('dokumen', $fileName, 'public');

            // Update dokumen
            $dokumen->update([
                'file' => $filePath,
                'status_dokumen' => 'sudah direvisi',
                'tanggal_revisi' => now() // tambahkan tanggal revisi jika diperlukan
            ]);

            // Log success
            Log::info('Dokumen berhasil diupdate', [
                'dokumen_id' => $dokumen->id,
                'new_file' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupdate'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Dokumen tidak ditemukan', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'File yang diunggah tidak valid. Pastikan file dalam format PDF dan ukuran maksimal 2MB'
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error saat update dokumen', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate dokumen. Silakan coba lagi.'
            ], 500);
        }
    }

    public function detailDokumen($id)
    {
        $dokumen = Dokumen::with(['dosen', 'ormawa'])
            ->where('id', $id)
            ->where('id_ormawa', auth()->guard('ormawa')->user()->id)
            ->firstOrFail();

        return view('user.ormawa.detail_dokumen', compact('dokumen'));
    }
}