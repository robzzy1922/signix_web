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
                'tujuan_pengajuan' => 'required|numeric|exists:dosen,id',
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
            $dokumen->id_dosen = (int)$request->tujuan_pengajuan;

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
            
            // Debug: tampilkan semua dokumen dan statusnya
            Log::info('All documents:', $allDocuments->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'status' => $doc->status_dokumen,
                    'nomor_surat' => $doc->nomor_surat,
                    'perihal' => $doc->perihal
                ];
            })->toArray());

            // Hitung status dengan nilai yang konsisten
            $stats = [
                'submitted' => $allDocuments->where('status_dokumen', 'diajukan')->count(),
                'signed' => $allDocuments->whereIn('status_dokumen', ['ditandatangani', 'disahkan'])->count(),
                'perlu_revisi' => $allDocuments->where('status_dokumen', 'butuh revisi')->count(),
                'sudah_direvisi' => $allDocuments->where('status_dokumen', 'sudah direvisi')->count(),
            ];

            // Debug: tampilkan detail perhitungan untuk setiap status
            Log::info('Status counts detail:', [
                'diajukan' => $allDocuments->where('status_dokumen', 'diajukan')->count(),
                'ditandatangani/disahkan' => $allDocuments->whereIn('status_dokumen', ['ditandatangani', 'disahkan'])->count(),
                'perlu_revisi' => $allDocuments->where('status_dokumen', 'butuh revisi')->count(),
                'sudah_direvisi' => $allDocuments->where('status_dokumen', 'sudah direvisi')->count(),
            ]);

            // Debug: tampilkan semua status dokumen yang ada
            $uniqueStatuses = $allDocuments->pluck('status_dokumen')->unique()->values();
            Log::info('All unique document statuses in database:', $uniqueStatuses->toArray());

            Log::info('Final stats:', $stats);

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
            $user = $request->user();
            $dosenId = $user->id;

            // Ambil dokumen yang ditujukan ke dosen ini
            $documents = Dokumen::where('id_dosen', $dosenId)->get();

            // Gunakan status yang sama dengan getStats untuk konsistensi
            $stats = [
                'diajukan' => $documents->where('status_dokumen', 'diajukan')->count(),
                'disahkan' => $documents->whereIn('status_dokumen', ['ditandatangani', 'disahkan'])->count(),
                'butuh_revisi' => $documents->whereIn('status_dokumen', ['perlu_revisi', 'revisi', 'butuh_revisi'])->count(),
                'sudah_direvisi' => $documents->where('status_dokumen', 'sudah_direvisi')->count(),
            ];

            // Debug: tampilkan detail perhitungan
            Log::info('Dosen document stats detail:', [
                'dosen_id' => $dosenId,
                'total_documents' => $documents->count(),
                'status_counts' => [
                    'diajukan' => $documents->where('status_dokumen', 'diajukan')->count(),
                    'ditandatangani/disahkan' => $documents->whereIn('status_dokumen', ['ditandatangani', 'disahkan'])->count(),
                    'perlu_revisi (all)' => $documents->whereIn('status_dokumen', ['perlu_revisi', 'revisi', 'butuh_revisi'])->count(),
                    'sudah_direvisi' => $documents->where('status_dokumen', 'sudah_direvisi')->count(),
                ]
            ]);

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting dosen document stats: ' . $e->getMessage());
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

    public function getAllDocuments(Request $request)
    {
        try {
            $ormawaId = $request->user()->id;
            Log::info('Getting all documents for ormawa:', ['ormawa_id' => $ormawaId]);

            $documents = Dokumen::where('id_ormawa', $ormawaId)
                ->with(['dosen:id,nama_dosen']) // Include dosen data
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Found documents:', [
                'count' => $documents->count(),
                'documents' => $documents->map(function($doc) {
                    return [
                        'id' => $doc->id,
                        'nomor_surat' => $doc->nomor_surat,
                        'status' => $doc->status_dokumen,
                        'hal' => $doc->perihal,
                        'tujuan' => $doc->dosen->nama_dosen ?? 'Unknown',
                    ];
                })
            ]);

            return response()->json([
                'success' => true,
                'data' => $documents->map(function($doc) {
                    return [
                        'id' => $doc->id,
                        'nomor_surat' => $doc->nomor_surat,
                        'hal' => $doc->perihal,
                        'status' => $doc->status_dokumen,
                        'tanggal_pengajuan' => $doc->tanggal_pengajuan,
                        'keterangan' => $doc->keterangan,
                        'tujuan_pengajuan' => $doc->dosen->nama_dosen ?? 'Unknown',
                        'file' => $doc->file,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting all documents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}