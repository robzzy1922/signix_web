<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'nomor_surat',
        'perihal',
        'status_dokumen',
        'file',
        'keterangan',
        'tanggal_pengajuan',
        'id_ormawa',
        'id_dosen',
    ];

    // Define relationships if needed

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
} 