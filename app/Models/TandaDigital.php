<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TandaDigital extends Model
{
    use HasFactory;

    protected $table = 'tanda_digital';

    protected $fillable = [
        'data_qr',
        'tanggal_pembuatan',
        'id_ormawa',
        'id_dosen',
        'id_dokumen',
    ];

    // Define relationships if needed
} 