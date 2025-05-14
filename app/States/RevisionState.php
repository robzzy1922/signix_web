<?php

namespace App\States;

use App\Models\Dokumen;
use Illuminate\Support\Facades\Log;

class RevisionState implements DocumentState
{
    /**
     * Handle the logic for revision state
     *
     * @param Dokumen $dokumen
     * @param array $data
     * @return Dokumen
     */
    public function handle(Dokumen $dokumen, array $data = [])
    {
        Log::info("Document {$dokumen->id} is in revision state");

        // If document has been revised and resubmitted
        if (isset($data['resubmitted']) && $data['resubmitted'] === true) {
            // Change back to pending state for review
            $dokumen->setState(new PendingState());
            $dokumen->keterangan_pengirim = $data['keterangan_pengirim'] ?? 'Dokumen telah direvisi';
            $dokumen->save();
            return $dokumen->handle();
        }

        // Update document status for revision
        $dokumen->status_dokumen = $this->getStatus();
        $dokumen->save();

        return $dokumen;
    }

    /**
     * Get the status string for revision state
     *
     * @return string
     */
    public function getStatus(): string
    {
        return 'direvisi';
    }
}
