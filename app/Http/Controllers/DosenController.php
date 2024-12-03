<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Dokumen;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;

class DosenController extends Controller
{
    public function dashboardDosen(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $dosen_id = auth()->guard('dosen')->user()->id;

        $dokumens = Dokumen::with('dosen') // Eager loading relasi dosen
    ->where('id_dosen', $dosen_id)
    ->when($status, function ($query) use ($status) {
        return $query->where('status_dokumen', $status);
    })
    ->when($search, function ($query) use ($search) {
        return $query->where(function ($q) use ($search) {
            $q->where('nomor_surat', 'like', "%{$search}%")
              ->orWhere('tanggal_pengajuan', 'like', "%{$search}%")
              ->orWhere('perihal', 'like', "%{$search}%")
              ->orWhereHas('dosen', function ($q) use ($search) {
                  $q->where('nama_dosen', 'like', "%{$search}%");
              })
              ->orWhere('status_dokumen', 'like', "%{$search}%");
        });
    })
    ->get();


        $countDiajukan = Dokumen::where('id_dosen', $dosen_id)
            ->where('status_dokumen', 'diajukan')->count();
        $countDisahkan = Dokumen::where('id_dosen', $dosen_id)
            ->where('status_dokumen', 'disahkan')->count();
        $countRevisi = Dokumen::where('id_dosen', $dosen_id)
            ->where('status_dokumen', 'direvisi')->count();

        return view('user.dosen.dashboard_dosen', compact('dokumens', 'status', 'countDiajukan', 'countDisahkan', 'countRevisi'));
    }

    public function create()
    {
        return view('user.dosen.create_tandatangan');
    }

    public function riwayat(Request $request)
    {
        $query = Dokumen::query()->where('id_dosen', Auth::guard('dosen')->id());

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_dokumen', $request->status);
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'LIKE', "%{$search}%")
                  ->orWhere('perihal', 'LIKE', "%{$search}%");
            });
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('user.dosen.riwayat_dosen', compact('documents'));
    }

    public function showDokumen($id)
    {
        $dosen_id = auth()->guard('dosen')->user()->id;

        $dokumen = Dokumen::with(['ormawa', 'dosen'])
            ->where('id_dosen', $dosen_id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $dokumen->id,
            'nomor_surat' => $dokumen->nomor_surat,
            'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
            'perihal' => $dokumen->perihal,
            'status_dokumen' => ucfirst($dokumen->status_dokumen),
            'keterangan' => $dokumen->keterangan,
            'file' => $dokumen->file,
            'pengaju' => $dokumen->ormawa ? [
                'nama' => $dokumen->ormawa->namaMahasiswa,
                'ormawa' => $dokumen->ormawa->namaOrmawa,
            ] : null,
            'dosen' => $dokumen->dosen ? [
                'nama' => $dokumen->dosen->nama_dosen,
            ] : null,
        ]);
    }

    public function getDokumenDetail($id)
    {
        $dokumen = Dokumen::with(['ormawa', 'dosen'])->findOrFail($id);
        return response()->json($dokumen);
    }

    public function profile()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('user.dosen.profile', compact('dosen'));
    }

    public function editProfile()
    {
        $dosen = Auth::guard('dosen')->user();
        return view('user.dosen.edit_profile', compact('dosen'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'namaMahasiswa' => 'required|string|max:255',
            'email' => 'required|email',
            'noHp' => 'required|string|max:15',
        ]);

        $dosen = Auth::guard('dosen')->user();
        $dosen->update([
            'nama_dosen' => $request->nama_dosen,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);
        $dosen->save();

        return redirect()->route('dosen.profile')->with('success', 'Profile updated successfully');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:1024'] // 1MB Max
        ]);

        $dosen = Auth::guard('dosen')->user();

        if ($dosen->profile) {
            Storage::disk('public')->delete($dosen->profile);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $dosen->update([
            'profile' => $path
        ]);

        return back()->with('success', 'Profile photo updated successfully');
    }

    public function destroyPhoto()
    {
        $dosen = Auth::guard('dosen')->user();

        if ($dosen->profile) {
            Storage::disk('public')->delete($dosen->profile);

            $dosen->update([
                'profile' => null
            ]);
        }

        return back()->with('success', 'Profile photo removed successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('dosen')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    public function generateQrCode($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            // Generate verification URL
            $verificationUrl = url("/verify/document/{$id}");

            // Create renderer with larger size and proper styling
            $renderer = new ImageRenderer(
                new RendererStyle(400, 1),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);

            // Generate QR code
            $qrCode = $writer->writeString($verificationUrl);

            // Create unique filename
            $qrCodePath = 'qrcodes/qr_' . uniqid() . '.svg';

            // Ensure directory exists
            Storage::disk('public')->makeDirectory('qrcodes', 0755, true, true);

            // Save QR code image
            Storage::disk('public')->put($qrCodePath, $qrCode);

            // Update document with QR code path
            $dokumen->update([
                'qr_code_path' => $qrCodePath
            ]);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => Storage::disk('public')->url($qrCodePath),
                'message' => 'QR Code berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveQrPosition(Request $request, $id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            // Validate position
            $validated = $request->validate([
                'x' => 'required|numeric',
                'y' => 'required|numeric',
                'width' => 'required|numeric',
                'height' => 'required|numeric'
            ]);

            // Create new PDF with QR code
            $pdf = new Fpdi();
            $pdfPath = storage_path('app/public/' . $dokumen->file);

            // Import pages from existing PDF
            $pageCount = $pdf->setSourceFile($pdfPath);

            // Process each page
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx);

                // Add QR code only on first page
                if ($pageNo === 1) {
                    $qrPath = storage_path('app/public/' . $dokumen->qr_code_path);
                    $pdf->Image($qrPath, $validated['x'], $validated['y'], $validated['width'], $validated['height']);
                }
            }

            // Save new PDF
            $newPdfPath = 'documents/signed_' . uniqid() . '.pdf';
            Storage::disk('public')->put($newPdfPath, $pdf->Output('S'));

            // Update document status and file
            $dokumen->update([
                'file' => $newPdfPath,
                'is_signed' => true,
                'status_dokumen' => 'disahkan'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil ditambahkan ke dokumen'
            ]);

        } catch (\Exception $e) {
            Log::error('Save QR Position Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan posisi QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyDocument($id)
    {
        try {
            $dokumen = Dokumen::with(['dosen', 'ormawa'])->findOrFail($id);

            return view('verify.document', [
                'dokumen' => $dokumen,
                'title' => 'Verifikasi Dokumen',
                'verified' => true,
                'timestamp' => now()->format('d M Y H:i:s')
            ]);
        } catch (\Exception $e) {
            return view('verify.document', [
                'verified' => false,
                'message' => 'Dokumen tidak ditemukan'
            ]);
        }
    }
}