<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'judul_dokumen',
        'isi_dokumen',
        'tanggal_pembuatan',
        'status_dokumen',
        'id_ormawa',
        'id_dosen',
    ];

    // Define relationships if needed
} 