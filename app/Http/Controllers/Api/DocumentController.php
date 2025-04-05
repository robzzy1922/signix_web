<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokumen;
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
                'tujuan_pengajuan' => 'required|string',
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
} 