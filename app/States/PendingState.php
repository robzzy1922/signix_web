<?php

namespace App\States;

use App\Models\Dokumen;
use Illuminate\Support\Facades\Log;

class PendingState implements DocumentState
{
    /**
     * Handle the logic for pending state
     *
     * @param Dokumen $dokumen
     * @param array $data
     * @return Dokumen
     */
    public function handle(Dokumen $dokumen, array $data = [])
    {
        // Logic for pending document processing
        Log::info("Document {$dokumen->id} is being processed in pending state");

        // If document is being verified
        if (isset($data['verified']) && $data['verified'] === true) {
            // Change to approved state
            $dokumen->setState(new ApprovedState());
            return $dokumen->handle($data);
        }

        // If document needs revision
        if (isset($data['needs_revision']) && $data['needs_revision'] === true) {
            // Change to revision state
            $dokumen->setState(new RevisionState());
            $dokumen->keterangan_revisi = $data['keterangan_revisi'] ?? 'Dokumen perlu direvisi';
            $dokumen->tanggal_revisi = now();
            $dokumen->save();
            return $dokumen->handle($data);
        }

        // Update document status to show it's still pending
        $dokumen->status_dokumen = $this->getStatus();
        $dokumen->tanggal_pengajuan = $dokumen->tanggal_pengajuan ?? now();
        $dokumen->save();

        return $dokumen;
    }

    /**
     * Get the status string for pending state
     *
     * @return string
     */
    public function getStatus(): string
    {
        return 'diajukan';
    }
}
