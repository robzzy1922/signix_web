<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\Dosen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    public function submit(Request $request)
    {
        try {
            $request->validate([
                'nomor_surat' => 'required|string',
                'tujuan_pengajuan' => 'required|numeric|exists:dosen,id', // Fixed to use 'dosen' instead of 'dosens'
                'hal' => 'required|string',
                'dokumen' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10MB
                'catatan' => 'nullable|string',
            ]);

            // Simpan file dokumen
            $file = $request->file('dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('dokumen', $fileName, 'public');

            // Buat dokumen baru
            $dokumen = new Dokumen();
            $dokumen->nomor_surat = $request->nomor_surat;
            $dokumen->perihal = $request->hal;
            $dokumen->file = $filePath;
            $dokumen->keterangan = $request->catatan;
            $dokumen->tanggal_pengajuan = now();
            $dokumen->status_dokumen = 'diajukan';
            $dokumen->id_ormawa = $request->user()->id;
            $dokumen->id_dosen = (int)$request->tujuan_pengajuan; // Konversi ke integer untuk memastikan tipe data benar

            $dokumen->save();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diajukan',
                'data' => $dokumen
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error submitting document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $ormawaId = $request->user()->id;
            
            // Log untuk debugging
            Log::info('Getting stats for ormawa:', ['ormawa_id' => $ormawaId]);
            
            $allDocuments = Dokumen::where('id_ormawa', $ormawaId)->get();
            Log::info('All documents:', ['documents' => $allDocuments->toArray()]);

            $stats = [
                'submitted' => $allDocuments->where('status_dokumen', 'diajukan')->count(),
                'signed' => $allDocuments->where('status_dokumen', 'ditandatangani')->count(),
                'need_revision' => $allDocuments->where('status_dokumen', 'perlu_revisi')->count(),
                'revised' => $allDocuments->where('status_dokumen', 'sudah_direvisi')->count(),
            ];

            Log::info('Document stats:', $stats);

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting document stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDosenDocumentStats(Request $request)
    {
        try {
            $user = $request->user(); // pastikan user dosen sudah login
            $dosenId = $user->id;

            // Ambil dokumen yang ditujukan ke dosen ini
            $documents = Dokumen::where('id_dosen', $dosenId)->get();

            $stats = [
                'diajukan' => $documents->where('status_dokumen', 'diajukan')->count(),
                'disahkan' => $documents->where('status_dokumen', 'ditandatangani')->count(),
                'butuh_revisi' => $documents->where('status_dokumen', 'perlu_revisi')->count(),
                'sudah_direvisi' => $documents->where('status_dokumen', 'sudah_direvisi')->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik dokumen dosen',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function getTujuanPengajuan()
    {
        try {
            $dosen = Dosen::select('id', 'nama_dosen as nama')->get();
            
            Log::info('Fetching tujuan pengajuan data', ['count' => $dosen->count()]);
            
            return response()->json([
                'success' => true,
                'data' => $dosen
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting tujuan pengajuan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil tujuan pengajuan: ' . $e->getMessage()
            ], 500);
        }
    }
}