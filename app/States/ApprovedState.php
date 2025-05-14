<?php

namespace App\States;

use App\Models\Dokumen;
use Illuminate\Support\Facades\Log;

class ApprovedState implements DocumentState
{
    /**
     * Handle the logic for approved state
     *
     * @param Dokumen $dokumen
     * @param array $data
     * @return Dokumen
     */
    public function handle(Dokumen $dokumen, array $data = [])
    {
        Log::info("Document {$dokumen->id} is in approved state");

        // Update document fields for approved state
        $dokumen->status_dokumen = $this->getStatus();
        $dokumen->tanggal_verifikasi = now();
        $dokumen->is_signed = true;

        // If there's a specific message for approval
        if (isset($data['keterangan'])) {
            $dokumen->keterangan = $data['keterangan'];
        }

        $dokumen->save();

        return $dokumen;
    }

    /**
     * Get the status string for approved state
     *
     * @return string
     */
    public function getStatus(): string
    {
        return 'disahkan';
    }
}
