<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ormawas;
use App\Models\Dosen;
use App\Models\Dokumen;

class TandaQr extends Model
{
    use HasFactory;

    protected $table = 'tanda_qrs';
    protected $fillable = [
        'data_qr',
        'tanggal_pembuatan',
        'id_ormawa',
        'id_dosen',
        'id_dokumen',
    ];

    // Relationship with Dosen model
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }

    // Relationship with Dokumen model
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class, 'id_dokumen');
    }

    // Relationship with Ormawa model
    public function ormawa()
    {
        return $this->belongsTo(Ormawas::class, 'id_ormawa');
    }
}