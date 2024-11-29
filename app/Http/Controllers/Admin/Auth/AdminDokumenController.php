<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dokumen;

class AdminDokumenController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $dokumens = Dokumen::query()
            ->when($request->search, function($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%")
                ->orWhere('judul_dokumen', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status_dokumen', $status);
            })
            ->paginate($perPage);

        return view('admin.dokumen.index', compact('dokumens'));
    }

    public function create()
    {
        return view('admin.dokumen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            // Add other validation rules as needed
        ]);

        // Handle file upload and document creation
        $dokumen = new Dokumen();
        // Add document creation logic

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }

    public function edit(Dokumen $dokumen)
    {
        return view('admin.dokumen.edit', compact('dokumen'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            // Add other validation rules as needed
        ]);

        // Handle file upload and document update
        // Add document update logic

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    public function destroy(Dokumen $dokumen)
    {
        // Add document deletion logic
        $dokumen->delete();

        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }

    public function show($id)
    {
        $dokumen = Dokumen::with(['ormawa', 'dosen'])->findOrFail($id);

        // Return data as JSON for AJAX request
        return response()->json([
            'id' => $dokumen->id,
            'nomor_surat' => $dokumen->nomor_surat,
            'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
            'perihal' => $dokumen->perihal,
            'file' => $dokumen->file, // Path ke file dokumen
            'status_dokumen' => $dokumen->status_dokumen,
            'keterangan' => $dokumen->keterangan,
            'ormawa' => [
                'nama' => $dokumen->ormawa->namaMahasiswa ?? 'N/A',
                'namaOrmawa' => $dokumen->ormawa->namaOrmawa ?? 'N/A'
            ],
            'dosen' => [
                'nama' => $dokumen->dosen->nama_dosen ?? 'N/A'
            ]
        ]);
    }

    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $filePath = storage_path('app/public/' . $dokumen->file);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        return response()->download($filePath);
    }

    public function view($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        $filePath = storage_path('app/public/' . $dokumen->file);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        // Return file for viewing in browser
        return response()->file($filePath);
    }
}