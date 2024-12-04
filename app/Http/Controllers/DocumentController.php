<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf|max:10240',
        ]);

        $filePath = $request->file('document')->store('documents');

        $document = Document::create([
            'user_id' => auth()->id(),
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('documents.show', $document);
    }

    public function show(Document $document)
    {
        return view('user.dosen.show', compact('document'));
    }

    public function saveBarcodePosition(Request $request, Document $document)
    {
        // Validasi posisi
        $request->validate([
        'left' => 'required|numeric',
        'top' => 'required|numeric',
    ]);

    // Simpan posisi barcode di database atau file
    $document->update([
        'barcode_position_left' => $request->left,
        'barcode_position_top' => $request->top,
    ]);

        return response()->json(['success' => true]);
    }

    public function insertBarcodeToPdf(Document $document)
    {
        $pdf = Pdf::loadFile(storage_path('app/' . $document->file_path));

        // Posisikan barcode di PDF sesuai dengan data yang disimpan
    // Gunakan PDF library seperti DomPDF untuk sisipkan barcode ke dalam file PDF.

        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', true);
    
    // Generate PDF yang telah disertakan barcode
    $output = $pdf->output();

        // Simpan hasilnya atau kembalikan kepada pengguna
        file_put_contents(storage_path('app/documents/signed_' . $document->id . '.pdf'), $output);
    }
}
