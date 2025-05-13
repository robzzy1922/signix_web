<?php

namespace App\Commands;

use App\Models\Dokumen;
use App\Repositories\DocumentRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SaveBarcodeCommand implements Command
{
    private $dokumen;
    private $qrPositionX;
    private $qrPositionY;
    private $width;
    private $height;

    public function __construct(Dokumen $dokumen, $qrPositionX, $qrPositionY, $width, $height)
    {
        $this->dokumen = $dokumen;
        $this->qrPositionX = $qrPositionX;
        $this->qrPositionY = $qrPositionY;
        $this->width = $width;
        $this->height = $height;
    }

    public function execute()
    {
        // Simpan posisi QR code
        $this->dokumen->update([
            'qr_position_x' => $this->qrPositionX,
            'qr_position_y' => $this->qrPositionY,
            'qr_width' => $this->width,
            'qr_height' => $this->height,
        ]);

        // Generate PDF baru dengan QR code
        $pdf = PDF::loadFile(storage_path('app/' . $this->dokumen->file_path));

        // Tambahkan QR code ke PDF - menyesuaikan dengan library PDF yang digunakan
        // Karena addImage tidak tersedia di DomPDF, gunakan cara lain untuk menambahkan gambar
        // Contoh menggunakan loadView dengan template HTML yang menyertakan gambar
        $qrCodeUrl = asset('storage/' . $this->dokumen->qr_code_path);
        $content = '<div style="position: absolute; left: ' . $this->qrPositionX . 'px; top: ' . $this->qrPositionY . 'px;">';
        $content .= '<img src="' . $qrCodeUrl . '" width="' . $this->width . '" height="' . $this->height . '">';
        $content .= '</div>';

        // Simpan PDF baru
        $newPdfPath = 'documents/signed_' . $this->dokumen->id . '.pdf';
        Storage::put($newPdfPath, $pdf->output());

        // Update dokumen dengan file baru dan status
        $this->dokumen->update([
            'file_path' => $newPdfPath,
            'status_dokumen' => 'disahkan'
        ]);

        return $this->dokumen;
    }
}