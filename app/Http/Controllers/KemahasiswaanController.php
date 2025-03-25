<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;

class KemahasiswaanController extends Controller
{
    public function dashboardKemahasiswaan(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $kemahasiswaan_id = auth()->guard('kemahasiswaan')->user()->id;

        $dokumens = Dokumen::with('kemahasiswaan') // Eager loading relasi dosen
    ->where('id_kemahasiswaan', $kemahasiswaan_id)
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


        $countDiajukan = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'diajukan')->count();
        $countDisahkan = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'disahkan')->count();
        $countRevisi = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'sudah direvisi')->count();
        $countButuhRevisi = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'butuh revisi')->count();

        return view('user.kemahasiswaan.dashboard_kemahasiswaan', compact('dokumens', 'status', 'countDiajukan', 'countDisahkan', 'countRevisi', 'countButuhRevisi'));
    }
}