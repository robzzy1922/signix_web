<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\TandaQr;
use App\Services\DocumentService;
use App\Services\DocumentStateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $documentService;
    protected $documentStateService;

    public function __construct(DocumentService $documentService, DocumentStateService $documentStateService)
    {
        $this->documentService = $documentService;
        $this->documentStateService = $documentStateService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:10240',
        ]);

        $dokumen = $this->documentService->upload($request->file('document'));

        // Initialize document state and handle it as pending
        $dokumen->handle();

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

        // Save barcode position using command pattern
        $this->documentService->saveBarcodePos(
            $dokumen,
            $request->x,
            $request->y,
            $request->width,
            $request->height
        );

        // Verify the document and transition to approved state
        $dokumen->handle(['verified' => true, 'keterangan' => 'Dokumen telah diverifikasi']);

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

    public function requestRevision(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'keterangan_revisi' => 'required|string|max:500',
        ]);

        // Process revision using state pattern
        $this->documentStateService->processAction(
            $dokumen,
            'revise',
            ['keterangan_revisi' => $request->keterangan_revisi]
        );

        // Check user type and redirect accordingly
        $guard = auth()->getDefaultDriver();

        if ($guard === 'dosen') {
            return redirect()->route('dosen.dokumen.show', $dokumen->id)
                ->with('success', 'Dokumen telah diminta untuk direvisi.');
        } elseif ($guard === 'kemahasiswaan') {
            return redirect()->route('kemahasiswaan.dokumen.show', $dokumen->id)
                ->with('success', 'Dokumen telah diminta untuk direvisi.');
        } else {
            return redirect()->back()
                ->with('success', 'Dokumen telah diminta untuk direvisi.');
        }
    }

    public function resubmitDocument(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:10240',
            'keterangan_pengirim' => 'nullable|string|max:500',
        ]);

        // Upload the new document version
        $filePath = $request->file('document')->store('documents');
        $dokumen->file = $filePath;
        $dokumen->save();

        // Process resubmission using state pattern
        $this->documentStateService->processAction(
            $dokumen,
            'resubmit',
            ['keterangan_pengirim' => $request->keterangan_pengirim ?? 'Dokumen telah direvisi']
        );

        // Redirect to Ormawa's document view
        return redirect()->route('ormawa.dokumen.show', $dokumen->id)
            ->with('success', 'Dokumen telah berhasil diajukan kembali.');
    }
}