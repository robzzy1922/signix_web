<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Dokumen;

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
} 