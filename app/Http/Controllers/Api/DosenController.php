<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Dokumen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DosenController extends Controller
{
    public function index()
    {
        try {
            $dosen = Dosen::select('id', 'nama_dosen as nama')->get();
            
            return response()->json([
                'success' => true,
                'data' => $dosen
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dosen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDocumentsForDosen($id)
    {
        $dokumen = Dokumen::with('ormawa:id,namaOrmawa,namaMahasiswa') 
            ->where('id_dosen', $id)
            ->where('status_dokumen', 'diajukan') 
            ->get();

        return response()->json([
            'success' => true,
            'data' => $dokumen->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nomor_surat' => $item->nomor_surat,
                    'pengirim' => $item->ormawa->namaOrmawa ?? 'Tidak Diketahui',
                    'namaMahasiswa' => $item->ormawa->namaMahasiswa ?? 'Tidak Diketahui',
                    'tanggal_pengajuan' => $item->tanggal_pengajuan,
                    'perihal' => $item->perihal,
                    'keterangan' => $item->keterangan,
                    'id_ormawa' => $item->id_ormawa,
                ];
            })
        ]);
    }

    // Ambil detail satu dokumen
    public function getDokumenDetail($id)
    {
        $dokumen = Dokumen::find($id);

        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dokumen
        ]);
    }

    /**
     * Mengirim keterangan revisi untuk dokumen
     * 
     * @param Request $request
     * @param int $id ID dokumen
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitRevisi(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'keterangan' => 'required|string|max:1000'
            ]);

            // Cari dokumen
            $dokumen = Dokumen::find($id);
            
            if (!$dokumen) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan'
                ], 404);
            }

            // Mulai transaksi
            DB::beginTransaction();
            
            try {
                // Update status dokumen
                $dokumen->status_dokumen = 'butuh revisi';
                $dokumen->keterangan_revisi = $request->keterangan;
                $dokumen->tanggal_revisi = now();
                $dokumen->save();
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil ditandai untuk revisi',
                    'data' => [
                        'id' => $dokumen->id,
                        'status' => $dokumen->status_dokumen,
                        'keterangan_revisi' => $dokumen->keterangan_revisi,
                        'tanggal_revisi' => $dokumen->tanggal_revisi
                    ]
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in submitRevisi API: ' . $e->getMessage(), [
                'document_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan revisi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
} 