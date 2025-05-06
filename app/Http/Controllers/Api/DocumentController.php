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
            
            // Pastikan folder dokumen ada
            if (!Storage::exists('public/dokumen')) {
                Storage::makeDirectory('public/dokumen');
            }
            
            // Simpan file dengan path yang benar
            $filePath = $file->storeAs('public/dokumen', $fileName);
            
            // Log path file
            Log::info('File uploaded:', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $filePath,
                'storage_path' => Storage::path($filePath),
                'exists' => Storage::exists($filePath)
            ]);

            // Buat dokumen baru
            $dokumen = new Dokumen();
            $dokumen->nomor_surat = $request->nomor_surat;
            $dokumen->perihal = $request->hal;
            $dokumen->file = str_replace('public/', '', $filePath); // Simpan path relatif
            $dokumen->keterangan = $request->catatan;
            $dokumen->tanggal_pengajuan = now();
            $dokumen->status_dokumen = 'diajukan';
            $dokumen->id_ormawa = $request->user()->id;
            $dokumen->id_dosen = (int)$request->tujuan_pengajuan;

            $dokumen->save();

            // Log dokumen yang disimpan
            Log::info('Document saved:', [
                'id' => $dokumen->id,
                'file_path' => $dokumen->file,
                'storage_path' => Storage::path('public/' . $dokumen->file),
                'exists' => Storage::exists('public/' . $dokumen->file)
            ]);

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
                ->with(['dosen:id,nama_dosen', 'ormawa:id,namaMahasiswa']) // Include dosen and ormawa data
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
                        'namaMahasiswa' => $doc->ormawa->namaMahasiswa ?? 'Unknown',
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
                        'namaMahasiswa' => $doc->ormawa->namaMahasiswa ?? 'Unknown',
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

    public function getDocumentDetail($id)
    {
        try {
            $document = Dokumen::findOrFail($id);
            
            // Get the full URL for the document file
            $fileUrl = Storage::url($document->file);
            $fullUrl = url($fileUrl);
            
            // Log the file path and URL for debugging
            Log::info('Document file details:', [
                'file_path' => $document->file,
                'storage_url' => $fileUrl,
                'full_url' => $fullUrl,
                'file_exists' => Storage::exists($document->file)
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $document->id,
                    'nomor_surat' => $document->nomor_surat,
                    'perihal' => $document->perihal,
                    'status' => $document->status_dokumen,
                    'tanggal_pengajuan' => $document->tanggal_pengajuan,
                    'file_url' => $fullUrl,
                    'keterangan' => $document->keterangan,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting document detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDocumentFile($id)
    {
        try {
            $document = Dokumen::findOrFail($id);
            
            Log::info('=== MULAI MENGAMBIL FILE PDF ===');
            Log::info('Document ID: ' . $id);
            Log::info('File path: ' . $document->file);
            Log::info('Storage path: ' . Storage::path('public/' . $document->file));
            
            if (!Storage::exists('public/' . $document->file)) {
                Log::error('File tidak ditemukan di storage');
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan: ' . $document->file
                ], 404);
            }

            $filePath = Storage::path('public/' . $document->file);
            
            if (!is_file($filePath) || !is_readable($filePath)) {
                Log::error('File tidak dapat dibaca');
                Log::error('Is file: ' . (is_file($filePath) ? 'true' : 'false'));
                Log::error('Is readable: ' . (is_readable($filePath) ? 'true' : 'false'));
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak dapat dibaca'
                ], 500);
            }

            $fileContent = file_get_contents($filePath);
            $fileSize = strlen($fileContent);
            
            Log::info('File size: ' . $fileSize . ' bytes');
            Log::info('First 10 bytes: ' . substr(bin2hex($fileContent), 0, 20));
            
            if (strpos($fileContent, '%PDF-') !== 0) {
                Log::error('File bukan PDF yang valid');
                Log::error('First 10 bytes: ' . substr(bin2hex($fileContent), 0, 20));
                return response()->json([
                    'success' => false,
                    'message' => 'File bukan PDF yang valid'
                ], 400);
            }

            $base64Content = base64_encode($fileContent);
            $mimeType = mime_content_type($filePath);

            Log::info('MIME type: ' . $mimeType);
            Log::info('Base64 length: ' . strlen($base64Content));
            Log::info('First 10 chars of base64: ' . substr($base64Content, 0, 10));

            return response()->json([
                'success' => true,
                'data' => $base64Content,
                'content_type' => $mimeType,
                'filename' => basename($document->file),
                'file_size' => $fileSize
            ]);
        } catch (\Exception $e) {
            Log::error('=== ERROR MENGAMBIL FILE PDF ===');
            Log::error('Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil file dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadRevisi(Request $request, $id)
    {
        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,doc,docx|max:10240', // max 10MB
        ]);

        $dokumen = \App\Models\Dokumen::findOrFail($id);

        // Hapus file lama jika ada
        if ($dokumen->file && Storage::disk('public')->exists($dokumen->file)) {
            Storage::disk('public')->delete($dokumen->file);
        }

        // Simpan file baru
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = uniqid().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('dokumen_revisi', $filename, 'public');
            $dokumen->file = $path;
        }

        // Update status
        $dokumen->status_dokumen = 'sudah direvisi';
        $dokumen->tanggal_revisi = now();
        $dokumen->save();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil direvisi',
            'data' => $dokumen,
        ]);
    }
}