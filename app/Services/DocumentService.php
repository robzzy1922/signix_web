<?php

namespace App\Services;

use App\Commands\CommandInvoker;
use App\Commands\GenerateQRCommand;
use App\Commands\SaveBarcodeCommand;
use App\Commands\UploadDocCommand;
use App\Commands\ViewDocCommand;
use App\Models\Dokumen;
use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentService
{
    private $repository;
    private $invoker;

    public function __construct(DocumentRepository $repository, CommandInvoker $invoker)
    {
        $this->repository = $repository;
        $this->invoker = $invoker;
    }

    public function upload($file)
    {
        $userId = Auth::user() ? Auth::user()->id : null;

        $command = new UploadDocCommand($file, $userId, $this->repository);
        $this->invoker->setCommand($command);

        return $this->invoker->executeCommand();
    }

    public function generateQR(Dokumen $dokumen)
    {
        $userId = Auth::user() ? Auth::user()->id : null;

        $command = new GenerateQRCommand($dokumen, $userId, $this->repository);
        $this->invoker->setCommand($command);

        return $this->invoker->executeCommand();
    }

    public function saveBarcodePos(Dokumen $dokumen, $x, $y, $width, $height)
    {
        $command = new SaveBarcodeCommand($dokumen, $x, $y, $width, $height);
        $this->invoker->setCommand($command);

        return $this->invoker->executeCommand();
    }

    public function view(Dokumen $dokumen)
    {
        $command = new ViewDocCommand($dokumen, $this->repository);
        $this->invoker->setCommand($command);

        return $this->invoker->executeCommand();
    }

    public function sign()
    {
        // Implementation for document signing
    }
}
