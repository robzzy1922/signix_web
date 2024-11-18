<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use App\Models\Dokumen;

class OrmawaController extends Controller
{
    public function dashboard(Request $request)
    {
        $dokumens = Dokumen::all();
        $status = $request->input('status');

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
        // Validate the request
        $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'kepada_tujuan' => 'required|exists:dosen,id',
            'hal' => 'required|string|max:255',
            'unggah_dokumen' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'catatan' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('unggah_dokumen')) {
            $file = $request->file('unggah_dokumen');
            $filePath = $file->store('dokumen', 'public');
        }

        // Save to the database
        Dokumen::create([
            'nomor_surat' => $request->input('nomor_surat'),
            'perihal' => $request->input('hal'),
            'file' => $filePath ?? null,
            'keterangan' => $request->input('catatan'),
            'tanggal_pengajuan' => now(),
            'status_dokumen' => 'diajukan',
            'id_ormawa' => Auth::guard('ormawa')->id(),
            'id_dosen' => $request->input('kepada_tujuan'),
        ]);

        // Redirect with success message
        return redirect()->route('ormawa.pengajuan')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function riwayat(Request $request)
    {
        $dosen = Auth::guard('ormawa')->user();
        $status = $request->input('status');

        $query = Dokumen::where('id_dosen', $dosen->id)->with('dosen');

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
}
