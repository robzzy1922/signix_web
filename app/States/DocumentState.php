<?php

namespace App\States;

use App\Models\Dokumen;

interface DocumentState
{
    /**
     * Handle the current state's logic
     *
     * @param Dokumen $dokumen The document context
     * @param array $data Additional data for state handling
     * @return mixed
     */
    public function handle(Dokumen $dokumen, array $data = []);

    /**
     * Get the status string representation for this state
     *
     * @return string
     */
    public function getStatus(): string;
}
