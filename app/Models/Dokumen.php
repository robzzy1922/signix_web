<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use App\Models\Ormawas;
use App\Models\Dosen;
use App\Models\Kemahasiswaan;

class Dokumen extends Model
{
    use HasFactory;
    protected $table = 'dokumens';

    protected $fillable = [
        'file',
        'nomor_surat',
        'perihal',
        'qr_position_x',
        'qr_position_y',
        'qr_width',
        'qr_height',
        'status_dokumen',
        'is_signed',
        'qr_code_path',
        'kode_pengesahan',
        'tanggal_pengajuan',
        'tanggal_verifikasi',
        'keterangan',
        'keterangan_revisi',
        'keterangan_pengirim',
        'tanggal_revisi',
        'id_ormawa',
        'id_dosen',
        'id_kemahasiswaan'
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    public function getFilePathAttribute()
    {
        // Hapus prefix 'dokumen/' jika sudah ada di path
        $filePath = str_replace('dokumen/dokumen/', 'dokumen/', $this->file);
        return storage_path('app/public/' . $filePath);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($dokumen) {
            Log::info('Document retrieved', [
                'id' => $dokumen->id,
                'file' => $dokumen->file,
                'full_path' => $dokumen->getFilePathAttribute()
            ]);
        });

        static::saving(function ($dokumen) {
            // Pastikan path file konsisten saat menyimpan
            if ($dokumen->file) {
                $dokumen->file = str_replace('dokumen/dokumen/', 'dokumen/', $dokumen->file);
            }
        });
    }

    // Relationship with Ormawa
    public function ormawa()
    {
        return $this->belongsTo(Ormawas::class, 'id_ormawa');
    }

    // Relationship with Dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    // Relationship with Kemahasiswaan
    public function kemahasiswaan()
    {
        return $this->belongsTo(Kemahasiswaan::class, 'id_kemahasiswaan');
    }
}
