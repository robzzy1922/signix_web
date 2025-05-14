<?php

namespace App\Services;

use App\Models\Dokumen;
use App\States\ApprovedState;
use App\States\PendingState;
use App\States\RevisionState;
use Illuminate\Support\Facades\Log;

class DocumentStateService
{
    /**
     * Set document state to pending
     *
     * @param Dokumen $dokumen
     * @param array $data
     * @return Dokumen
     */
    public function setToPending(Dokumen $dokumen, array $data = [])
    {
        $dokumen->setState(new PendingState());
        return $dokumen->handle($data);
    }

    /**
     * Set document state to approved
     *
     * @param Dokumen $dokumen
     * @param array $data
     * @return Dokumen
     */
    public function setToApproved(Dokumen $dokumen, array $data = [])
    {
        $dokumen->setState(new ApprovedState());
        return $dokumen->handle($data);
    }

    /**
     * Set document state to revision
     *
     * @param Dokumen $dokumen
     * @param array $data
     * @return Dokumen
     */
    public function setToRevision(Dokumen $dokumen, array $data = [])
    {
        $dokumen->setState(new RevisionState());
        return $dokumen->handle($data);
    }

    /**
     * Process action on document based on action type
     *
     * @param Dokumen $dokumen
     * @param string $action
     * @param array $data
     * @return Dokumen
     */
    public function processAction(Dokumen $dokumen, string $action, array $data = [])
    {
        Log::info("Processing action {$action} for document {$dokumen->id}");

        switch ($action) {
            case 'approve':
                $data['verified'] = true;
                break;
            case 'revise':
                $data['needs_revision'] = true;
                break;
            case 'resubmit':
                $data['resubmitted'] = true;
                break;
        }

        return $dokumen->handle($data);
    }
}
