<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\TandaQr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use setasign\Fpdi\Fpdi; // Correct namespace for FPDI

class DosenApiController extends Controller
{
    /**
     * Generate QR Code for a document and return its URL.
     * API version of DosenController@generateQrCode
     */
    public function generateQrCodeApi(Request $request, $id)
    {
        try {
            $dosen = $request->user(); // Authenticated Dosen via Sanctum
            $dokumen = Dokumen::where('id', $id)->where('id_dosen', $dosen->id)->firstOrFail();

            if (!$dokumen->kode_pengesahan) {
                $dokumen->kode_pengesahan = Str::random(10);
                // No save yet, will be saved if QR generation is successful
            }

            $verificationUrl = route('verify.document', ['id' => $dokumen->id, 'kode' => $dokumen->kode_pengesahan]);
            $qrCodePath = 'qrcodes/api_qr_' . $dokumen->id . '_' . time() . '.png';
            $fullPath = storage_path('app/public/' . $qrCodePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            QrCode::format('png')
                  ->size(400) // Standard size, mobile can resize display
                  ->margin(1)
                  ->generate($verificationUrl, $fullPath);

            // Only save if QR is successfully generated and path is set
            $dokumen->qr_code_path = $qrCodePath;
            $dokumen->save(); // Save kode_pengesahan and qr_code_path

            // Create TandaQr record
            TandaQr::create([
                'data_qr' => $verificationUrl,
                'tanggal_pembuatan' => now(),
                'id_ormawa' => $dokumen->id_ormawa, // Assuming id_ormawa is available
                'id_dosen' => $dosen->id,
                'id_dokumen' => $dokumen->id
            ]);

            return response()->json([
                'success' => true,
                'qr_code_url' => Storage::url($qrCodePath), // URL accessible by mobile app
                'message' => 'QR Code berhasil dibuat.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan atau Anda tidak memiliki akses.'], 404);
        } catch (\Exception $e) {
            Log::error('API QR Code Generation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat QR Code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Embed QR code into the PDF document.
     * API version of DosenController@saveQrPosition
     */
    public function embedQrCodeApi(Request $request, $id)
    {
        try {
            $dosen = $request->user(); // Authenticated Dosen
            $dokumen = Dokumen::where('id', $id)->where('id_dosen', $dosen->id)->firstOrFail();

            $validated = $request->validate([
                'x_percent' => 'required|numeric|min:0|max:100',      // X position as percentage
                'y_percent' => 'required|numeric|min:0|max:100',      // Y position as percentage
                'width_percent' => 'required|numeric|min:1|max:100',  // Width as percentage
                'height_percent' => 'required|numeric|min:1|max:100', // Height as percentage
                'page_number' => 'required|integer|min:1'             // Page number to place QR
            ]);

            if (!$dokumen->qr_code_path || !Storage::disk('public')->exists($dokumen->qr_code_path)) {
                return response()->json(['success' => false, 'message' => 'QR Code belum di-generate untuk dokumen ini.'], 400);
            }

            $sourcePdfPath = storage_path('app/public/' . $dokumen->file);
            if (!file_exists($sourcePdfPath)) {
                 return response()->json(['success' => false, 'message' => 'File PDF sumber tidak ditemukan.'], 404);
            }

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($sourcePdfPath);

            if ($validated['page_number'] > $pageCount) {
                return response()->json(['success' => false, 'message' => 'Nomor halaman melebihi jumlah halaman pada PDF.'], 400);
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx); // Import dimensions by default

                if ($pageNo === (int)$validated['page_number']) {
                    $qrCodeImagePath = storage_path('app/public/' . $dokumen->qr_code_path);

                    // Get actual page dimensions (points)
                    $pageWidthPts = $pdf->getPageWidth();
                    $pageHeightPts = $pdf->getPageHeight();

                    // Convert percentages to absolute points for FPDI
                    // FPDI's Y is from top, X is from left
                    $qrWidthPts = ($validated['width_percent'] / 100) * $pageWidthPts;
                    $qrHeightPts = ($validated['height_percent'] / 100) * $pageHeightPts;
                    $qrXPts = ($validated['x_percent'] / 100) * $pageWidthPts;
                    $qrYPts = ($validated['y_percent'] / 100) * $pageHeightPts;
                    
                    // Ensure QR code doesn't go out of bounds (optional, good practice)
                    $qrXPts = max(0, min($qrXPts, $pageWidthPts - $qrWidthPts));
                    $qrYPts = max(0, min($qrYPts, $pageHeightPts - $qrHeightPts));

                    $pdf->Image($qrCodeImagePath, $qrXPts, $qrYPts, $qrWidthPts, $qrHeightPts);
                }
            }

            $newFileName = 'signed_api_' . time() . '_' . basename($dokumen->file);
            $newFilePath = 'dokumen/' . $newFileName; // Relative to storage/app/public/
            $fullStoragePath = storage_path('app/public/' . $newFilePath);

            if (!file_exists(dirname($fullStoragePath))) {
                mkdir(dirname($fullStoragePath), 0755, true);
            }

            $pdf->Output($fullStoragePath, 'F');

            // Update document record
            $dokumen->update([
                'file' => $newFilePath, // Store new path
                'qr_position_x' => $validated['x_percent'], // Store as percentage
                'qr_position_y' => $validated['y_percent'],
                'qr_width' => $validated['width_percent'],
                'qr_height' => $validated['height_percent'],
                'qr_page' => $validated['page_number'],
                'status_dokumen' => 'disahkan',
                'is_signed' => true,
                'tanggal_verifikasi' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil ditambahkan dan dokumen telah disahkan.',
                'signed_document_url' => Storage::url($newFilePath)
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan atau Anda tidak memiliki akses.'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('API Embed QR Code Error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Gagal menyematkan QR Code: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show document details for API.
     * Simplified version of DosenController@showDokumen
     */
    public function showDokumenApi(Request $request, $id)
    {
        try {
            $dosen = $request->user();
            $dokumen = Dokumen::with(['ormawa:id,namaMahasiswa,namaOrmawa', 'dosen:id,nama_dosen']) // Select specific fields
                ->where('id_dosen', $dosen->id)
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $dokumen->id,
                    'nomor_surat' => $dokumen->nomor_surat,
                    'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
                    'perihal' => $dokumen->perihal,
                    'status_dokumen' => ucfirst($dokumen->status_dokumen),
                    'keterangan' => $dokumen->keterangan,
                    'keterangan_revisi' => $dokumen->keterangan_revisi,
                    'keterangan_pengirim' => $dokumen->keterangan_pengirim,
                    'file_url' => Storage::url($dokumen->file), // URL for mobile to download/view
                    'qr_code_url' => $dokumen->qr_code_path ? Storage::url($dokumen->qr_code_path) : null,
                    'pengaju' => $dokumen->ormawa ? [
                        'nama' => $dokumen->ormawa->namaMahasiswa,
                        'ormawa' => $dokumen->ormawa->namaOrmawa,
                    ] : null,
                    'dosen' => $dokumen->dosen ? [
                        'nama' => $dokumen->dosen->nama_dosen,
                    ] : null,
                    // Add any other fields the mobile app might need
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan atau Anda tidak memiliki akses.'], 404);
        } catch (\Exception $e) {
            Log::error('API Show Dokumen Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil detail dokumen.'], 500);
        }
    }
}