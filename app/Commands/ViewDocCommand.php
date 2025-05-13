<?php

namespace App\Commands;

use App\Models\Dokumen;
use App\Repositories\DocumentRepository;
use Illuminate\Support\Facades\Storage;

class ViewDocCommand implements Command
{
    private $dokumen;
    private $repository;

    public function __construct(Dokumen $dokumen, DocumentRepository $repository)
    {
        $this->dokumen = $dokumen;
        $this->repository = $repository;
    }

    public function execute()
    {
        if (!Storage::exists($this->dokumen->file_path)) {
            throw new \Exception('Document not found');
        }

        $path = Storage::path($this->dokumen->file_path);
        $content = file_get_contents($path);
        $mimeType = mime_content_type($path) ?: 'application/pdf';

        return [
            'content' => $content,
            'mimeType' => $mimeType,
            'filename' => basename($this->dokumen->file_path)
        ];
    }
}