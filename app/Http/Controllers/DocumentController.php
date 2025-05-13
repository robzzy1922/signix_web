<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\TandaQr;
use App\Services\DocumentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:10240',
        ]);

        $dokumen = $this->documentService->upload($request->file('document'));

        return redirect()->route('dokumen.show', $dokumen);
    }

    public function show(Dokumen $dokumen)
    {
        return view('user.dosen.show', compact('dokumen'));
    }

    public function saveBarcodePosition(Request $request, Dokumen $dokumen)
    {
        // Validasi posisi
        $request->validate([
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
        ]);

        $this->documentService->saveBarcodePos(
            $dokumen,
            $request->x,
            $request->y,
            $request->width,
            $request->height
        );

        return response()->json(['success' => true]);
    }

    public function generateQrCode(Dokumen $dokumen)
    {
        try {
            $result = $this->documentService->generateQR($dokumen);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => asset('storage/' . $result->qr_code_path)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewDocument($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        try {
            $result = $this->documentService->view($dokumen);

            return response($result['content'])
                ->header('Content-Type', $result['mimeType'])
                ->header('Content-Disposition', 'inline; filename="' . $result['filename'] . '"');
        } catch (\Exception $e) {
            abort(404, 'Document not found: ' . $e->getMessage());
        }
    }
}