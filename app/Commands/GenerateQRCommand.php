<?php

namespace App\Commands;

use App\Models\Dokumen;
use App\Models\TandaQr;
use App\Repositories\DocumentRepository;
use Illuminate\Support\Facades\Storage;

class GenerateQRCommand implements Command
{
    private $dokumen;
    private $userId;
    private $repository;

    public function __construct(Dokumen $dokumen, $userId, DocumentRepository $repository)
    {
        $this->dokumen = $dokumen;
        $this->userId = $userId;
        $this->repository = $repository;
    }

    public function execute()
    {
        // Generate unique identifier for QR
        $qrData = [
            'document_id' => $this->dokumen->id,
            'timestamp' => now()->timestamp,
            'validator' => $this->userId
        ];

        $qrString = json_encode($qrData);

        // Generate QR code using Simple QR Code
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrString);

        // Save QR code to storage
        $qrPath = 'qrcodes/doc_' . $this->dokumen->id . '_' . time() . '.png';
        Storage::put('public/' . $qrPath, $qrCode);

        // Create TandaQr record
        TandaQr::create([
            'data_qr' => $qrString,
            'tanggal_pembuatan' => now(),
            'id_ormawa' => $this->dokumen->id_ormawa,
            'id_dosen' => $this->userId,
            'id_kemahasiswaan' => $this->userId,
            'id_dokumen' => $this->dokumen->id
        ]);

        // Update document with QR code path
        return $this->repository->update($this->dokumen, [
            'qr_code_path' => $qrPath
        ]);
    }
}
