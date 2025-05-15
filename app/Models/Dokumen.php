<?php

namespace App\Models;

use App\States\DocumentState;
use App\States\PendingState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'tanggal_pengajuan' => 'datetime',
        'tanggal_revisi' => 'datetime',
    ];

    /**
     * The state of the document.
     *
     * @var DocumentState
     */
    private $state;

    /**
     * Get the current state of the document.
     *
     * @return DocumentState
     */
    public function getState(): DocumentState
    {
        if ($this->state === null) {
            // Initialize with default state based on status_dokumen
            $this->initState();
        }

        return $this->state;
    }

    /**
     * Set the state of the document.
     *
     * @param DocumentState $state
     * @return void
     */
    public function setState(DocumentState $state): void
    {
        $this->state = $state;
    }

    /**
     * Initialize the state based on the status_dokumen field.
     *
     * @return void
     */
    protected function initState(): void
    {
        $stateMap = [
            'diajukan' => \App\States\PendingState::class,
            'disahkan' => \App\States\ApprovedState::class,
            'direvisi' => \App\States\RevisionState::class,
        ];

        $stateClass = $stateMap[$this->status_dokumen] ?? PendingState::class;
        $this->state = new $stateClass();
    }

    /**
     * Handle the document according to its current state.
     *
     * @param array $data
     * @return Dokumen
     */
    public function handle(array $data = []): Dokumen
    {
        return $this->getState()->handle($this, $data);
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