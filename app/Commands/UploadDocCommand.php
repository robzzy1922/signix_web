<?php

namespace App\Commands;

use App\Models\Dokumen;
use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadDocCommand implements Command
{
    private $file;
    private $userId;
    private $repository;

    public function __construct($file, $userId, DocumentRepository $repository)
    {
        $this->file = $file;
        $this->userId = $userId;
        $this->repository = $repository;
    }

    public function execute()
    {
        $filePath = $this->file->store('documents');

        $dokumen = new Dokumen([
            'user_id' => $this->userId,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return $this->repository->save($dokumen);
    }
}