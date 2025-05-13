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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\TandaQr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class KemahasiswaanController extends Controller
{
    public function dashboardKemahasiswaan(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $kemahasiswaan_id = auth()->guard('kemahasiswaan')->user()->id;

        $dokumens = Dokumen::with('kemahasiswaan') // Eager loading relasi dosen
    ->where('id_kemahasiswaan', $kemahasiswaan_id)
    ->when($status, function ($query) use ($status) {
        return $query->where('status_dokumen', $status);
    })
    ->when($search, function ($query) use ($search) {
        return $query->where(function ($q) use ($search) {
            $q->where('nomor_surat', 'like', "%{$search}%")
              ->orWhere('tanggal_pengajuan', 'like', "%{$search}%")
              ->orWhere('perihal', 'like', "%{$search}%")
              ->orWhereHas('kemahasiswaan', function ($q) use ($search) {
                  $q->where('nama_kemahasiswaan', 'like', "%{$search}%");
              })
              ->orWhere('status_dokumen', 'like', "%{$search}%");
        });
    })
    ->orderBy('tanggal_pengajuan', 'desc')
    ->orderBy('created_at', 'desc')
    ->get();


        $countDiajukan = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'diajukan')->count();
        $countDisahkan = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'disahkan')->count();
        $countRevisi = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'sudah direvisi')->count();
        $countButuhRevisi = Dokumen::where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('status_dokumen', 'butuh revisi')->count();

        return view('user.kemahasiswaan.dashboard_kemahasiswaan', compact('dokumens', 'status', 'countDiajukan', 'countDisahkan', 'countRevisi', 'countButuhRevisi'));
    }

    public function create()
    {
        return view('user.kemahasiswaan.create_tandatangan');
    }

    public function riwayat(Request $request)
    {
        $query = Dokumen::query()->where('id_kemahasiswaan', Auth::guard('kemahasiswaan')->id());

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

        $documents = $query->orderBy('tanggal_pengajuan', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('user.kemahasiswaan.riwayat_kemahasiswaan', compact('documents'));
    }

    public function showDokumen($id)
    {
        $kemahasiswaan_id = auth()->guard('kemahasiswaan')->user()->id;

        $dokumen = Dokumen::with(['ormawa', 'dosen'])
            ->where('id_kemahasiswaan', $kemahasiswaan_id)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $dokumen->id,
            'nomor_surat' => $dokumen->nomor_surat,
            'tanggal_pengajuan' => $dokumen->tanggal_pengajuan,
            'perihal' => $dokumen->perihal,
            'status_dokumen' => ucfirst($dokumen->status_dokumen),
            'keterangan' => $dokumen->keterangan,
            'keterangan_revisi' => $dokumen->keterangan_revisi,
            'keterangan_pengirim' => $dokumen->keterangan_pengirim,
            'file' => $dokumen->file,
            'pengaju' => $dokumen->ormawa ? [
                'nama' => $dokumen->ormawa->namaMahasiswa,
                'ormawa' => $dokumen->ormawa->namaOrmawa,
            ] : null,
            'dosen' => $dokumen->dosen ? [
                'nama' => $dokumen->dosen->nama_kemahasiswaan,
            ] : null,
        ]);
    }

    public function getDokumenDetail($id)
    {
        $dokumen = Dokumen::with(['ormawa', 'kemahasiswaan'])->findOrFail($id);
        return response()->json($dokumen);
    }

    public function profile()
    {
        $kemahasiswaan = Auth::guard('kemahasiswaan')->user();
        return view('user.kemahasiswaan.profile', compact('kemahasiswaan'));
    }

    public function editProfile()
    {
        $kemahasiswaan = Auth::guard('kemahasiswaan')->user();
        return view('user.kemahasiswaan.profile', compact('kemahasiswaan'));
    }

    public function updateProfile(Request $request)
    {
        $kemahasiswaan = Auth::guard('kemahasiswaan')->user();

        $request->validate([
            'namaKemahasiswaan' => 'required|string|max:255',
            'email' => 'required|email',
            'noHp' => 'required|string|max:15',
            'currentPassword' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'passwordConfirmation' => 'nullable|same:password',
        ]);

        // Check if email is being changed
        $emailChanged = ($request->email !== $kemahasiswaan->email);

        // Update basic info
        $data = [
            'nama_kemahasiswaan' => $request->namaKemahasiswaan,
            'no_hp' => $request->noHp,
        ];

        // If email is changed, set it as unverified and store new email in verification_email
        if ($emailChanged) {
            $data['verification_email'] = $request->email;
            // Keep the old email until verified
        } else {
            // If email is not changed, include it in update data
            $data['email'] = $request->email;
        }

        // Update password if provided
        if ($request->filled('currentPassword') && $request->filled('password')) {
            // Verify current password
            if (!\Illuminate\Support\Facades\Hash::check($request->currentPassword, $kemahasiswaan->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['currentPassword' => 'Current password is incorrect']);
            }

            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $kemahasiswaan->update($data);

        // If email changed, redirect to verification page
        if ($emailChanged) {
            return redirect()->route('kemahasiswaan.profile')
                ->with('verify_email', true)
                ->with('new_email', $request->email);
        }

        return redirect()->route('kemahasiswaan.profile')
            ->with('success', 'Profile updated successfully');
    }

    public function updatePhoto(Request $request)
    {
        // Validasi file
        $request->validate([
            'profile_photo' => ['required', 'image', 'max:1024'], // Maksimal 1MB
        ]);

        // Ambil pengguna yang sedang login
        $kemahasiswaan = Auth::guard('kemahasiswaan')->user();

        // Periksa apakah pengguna ditemukan
        if (!$kemahasiswaan) {
            return back()->with('error', 'Failed to update profile photo. User not found.');
        }

        // Hapus foto profil lama jika ada
        if ($kemahasiswaan->profile && file_exists(public_path('profiles/' . $kemahasiswaan->profile))) {
            unlink(public_path('profiles/' . $kemahasiswaan->profile));
        }

        // Simpan file baru ke folder public/profiles
        $file = $request->file('profile_photo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('profiles'), $filename);

        // Update database
        $kemahasiswaan->update([
            'profile' => $filename,
        ]);

        return back()->with('success', 'Profile photo updated successfully.');
    }


    public function destroyPhoto()
    {
        $kemahasiswaan = Auth::guard('kemahasiswaan')->user();

        if ($kemahasiswaan->profile) {
            Storage::disk('public')->delete($kemahasiswaan->profile);

            $kemahasiswaan->update([
                'profile' => null
            ]);
        }

        return back()->with('success', 'Profile photo removed successfully');
    }

    public function logout(Request $request)
    {
        Auth::guard('kemahasiswaan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    public function generateQrCode($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            // Generate kode pengesahan jika belum ada
            if (!$dokumen->kode_pengesahan) {
                $dokumen->kode_pengesahan = Str::random(10);
                $dokumen->save();
            }

            // Buat URL verifikasi
            $verificationUrl = route('verify.document', ['id' => $id, 'kode' => $dokumen->kode_pengesahan]);

            // Generate QR Code dengan path yang benar
            $qrCodePath = 'qrcodes/qr_' . $id . '_' . time() . '.png';
            $fullPath = storage_path('app/public/' . $qrCodePath);

            // Pastikan direktori exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Generate QR code menggunakan SimpleSoftwareIO
            QrCode::format('png')
                  ->size(400)
                  ->margin(1)
                  ->generate($verificationUrl, $fullPath);

            // Simpan data ke tabel tanda_qrs
            TandaQr::create([
                'data_qr' => $verificationUrl,
                'tanggal_pembuatan' => now(),
                'id_ormawa' => $dokumen->id_ormawa,
                'id_kemahasiswaan' => auth()->guard('kemahasiswaan')->id(),
                'id_dokumen' => $dokumen->id
            ]);

            // Update dokumen dengan path QR code
            $dokumen->update([
                'qr_code_path' => $qrCodePath
            ]);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => Storage::url($qrCodePath),
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

    public function saveQrPosition(Request $request, Dokumen $dokumen)
    {
        try {
            $validated = $request->validate([
                'x' => 'required|numeric',
                'y' => 'required|numeric',
                'width' => 'required|numeric',
                'height' => 'required|numeric',
                'page' => 'required|numeric'
            ]);

            if (!$dokumen->qr_code_path || !Storage::disk('public')->exists($dokumen->qr_code_path)) {
                throw new \Exception('QR Code belum di-generate');
            }

            $sourcePdfPath = storage_path('app/public/' . $dokumen->file);
            if (!file_exists($sourcePdfPath)) {
                throw new \Exception('File PDF sumber tidak ditemukan');
            }

            // Inisialisasi FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($sourcePdfPath);

            // Proses setiap halaman
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $pdf->AddPage();
                $tplIdx = $pdf->importPage($pageNo);
                $pdf->useTemplate($tplIdx);

                // Tambahkan QR code hanya di halaman yang dipilih
                if ($pageNo === (int)$validated['page']) {
                    $qrCodePath = storage_path('app/public/' . $dokumen->qr_code_path);

                    // Dapatkan ukuran halaman
                    $pageWidth = $pdf->GetPageWidth();
                    $pageHeight = $pdf->GetPageHeight();

                    // Konversi persentase ke koordinat absolut
                    $x = ($validated['x'] * $pageWidth) / 100;
                    $y = ($validated['y'] * $pageHeight) / 100;
                    $width = ($validated['width'] * $pageWidth) / 100;
                    $height = ($validated['height'] * $pageHeight) / 100;

                    // Pastikan QR code tidak keluar dari halaman
                    $x = max(0, min($x, $pageWidth - $width));
                    $y = max(0, min($y, $pageHeight - $height));

                    // Tambahkan QR code ke PDF
                    $pdf->Image($qrCodePath, $x, $y, $width, $height);
                }
            }

            // Simpan PDF yang sudah ditandatangani
            $newFileName = 'signed_' . time() . '_' . basename($dokumen->file);
            $newFilePath = 'dokumen/' . $newFileName;

            // Pastikan direktori exists
            $fullPath = storage_path('app/public/' . $newFilePath);
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Simpan PDF ke storage
            $pdf->Output($fullPath, 'F');

            // Update database dengan timestamp yang benar
            $dokumen->update([
                'file' => $newFilePath,
                'qr_position_x' => $validated['x'],
                'qr_position_y' => $validated['y'],
                'qr_width' => $validated['width'],
                'qr_height' => $validated['height'],
                'qr_page' => $validated['page'],
                'status_dokumen' => 'disahkan',
                'is_signed' => true,
                'tanggal_verifikasi' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code berhasil ditambahkan dan dokumen telah disahkan'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in saveQrPosition: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyDocument($id)
    {
        try {
            $dokumen = Dokumen::with(['dosen', 'ormawa'])->findOrFail($id);

            if (!$dokumen->is_signed || !$dokumen->kode_pengesahan) {
                return view('verify.document', [
                    'verified' => false,
                    'message' => 'Dokumen belum disahkan'
                ]);
            }

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

    public function editQrCode($id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            if ($dokumen->id_kemahasiswaan != auth()->guard('kemahasiswaan')->id()) {
                abort(403, 'Unauthorized action.');
            }

            // Generate QR code jika belum ada
            if (!$dokumen->qr_code_path || !Storage::disk('public')->exists($dokumen->qr_code_path)) {
                // Generate kode pengesahan baru
                $dokumen->kode_pengesahan = Str::random(10);

                // Set path QR code
                $qrCodePath = 'qrcodes/qr_' . $dokumen->id . '_' . time() . '.png';
                $fullPath = storage_path('app/public/' . $qrCodePath);

                // Buat direktori jika belum ada
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }

                // Generate QR code
                QrCode::format('png')
                      ->size(400)
                      ->margin(1)
                      ->generate(
                          route('verify.document', ['id' => $dokumen->id, 'kode' => $dokumen->kode_pengesahan]),
                          $fullPath
                      );

                // Update dokumen
                $dokumen->update([
                    'qr_code_path' => $qrCodePath,
                    'kode_pengesahan' => $dokumen->kode_pengesahan
                ]);
            }

            return view('user.kemahasiswaan.edit_qr', compact('dokumen'));

        } catch (\Exception $e) {
            Log::error('Error in editQrCode: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat QR Code: ' . $e->getMessage());
        }
    }

    public function submitRevisi(Request $request, $id)
    {
        try {
            $dokumen = Dokumen::findOrFail($id);

            $validated = $request->validate([
                'keterangan' => 'required|string|max:1000'
            ]);

            DB::beginTransaction();
            try {
                $dokumen->status_dokumen = 'butuh revisi';
                $dokumen->keterangan_revisi = $validated['keterangan'];
                $dokumen->tanggal_revisi = now();
                $dokumen->save();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Dokumen berhasil direvisi'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error in submitRevisi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan revisi dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getVerificationStatus()
    {
        $kemahasiswaan = Auth::guard('kemahasiswaan')->user();

        return response()->json([
            'is_verified' => $kemahasiswaan->is_email_verified,
            'email' => $kemahasiswaan->email,
            'verification_in_progress' => !empty($kemahasiswaan->verification_email),
            'verification_email' => $kemahasiswaan->verification_email,
        ]);
    }

    // Generate OTP and send to email
    public function sendEmailOTP(Request $request)
    {
        try {
            Log::info('Received send OTP request', $request->all());

            $request->validate([
                'email' => 'required|email'
            ]);

            $kemahasiswaan = Auth::guard('kemahasiswaan')->user();
            Log::info('User authenticated', ['user_id' => $kemahasiswaan->id]);

            // Check if the email is already verified
            if ($kemahasiswaan->is_email_verified && $kemahasiswaan->email === $request->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already verified.'
                ]);
            }

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP and set expiration time (15 minutes)
            $kemahasiswaan->verification_email = $request->email;
            $kemahasiswaan->email_verification_code = $otp;
            $kemahasiswaan->email_verification_expires_at = Carbon::now()->addMinutes(15);
            $saved = $kemahasiswaan->save();

            if (!$saved) {
                Log::error('Failed to save OTP data to database');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save verification data. Please try again.'
                ], 500);
            }

            Log::info('OTP generated and saved', [
                'user_id' => $kemahasiswaan->id,
                'verification_email' => $kemahasiswaan->verification_email,
                'otp' => $otp, // Don't log OTP in production!
                'expires_at' => $kemahasiswaan->email_verification_expires_at
            ]);

            // For development, include OTP in response regardless of email status
            try {
                // Send email with OTP
                $this->sendOTPEmail($kemahasiswaan->verification_email, $otp, $kemahasiswaan->nama_kemahasiswaan);
                Log::info('OTP email sent successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent to your email. Please check your inbox.',
                    'debug_otp' => $otp
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);

                // Return success with OTP even if email fails to allow testing
                return response()->json([
                    'success' => true,
                    'message' => 'Email might not have been sent, but OTP is generated: ' . $otp,
                    'debug_otp' => $otp
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process sendEmailOTP: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper function to send OTP email
    private function sendOTPEmail($email, $otp, $name)
    {
        try {
            Log::info('Preparing to send OTP email', ['email' => $email, 'otp' => $otp]);

            $data = [
                'otp' => $otp,
                'name' => $name
            ];

            // For debugging, always write to the log
            Log::info('OTP for email verification', [
                'email' => $email,
                'otp' => $otp,
                'name' => $name
            ]);

            Mail::send('emails.otp-verification', $data, function($message) use($email) {
                $message->to($email)
                        ->subject('Email Verification Code - Sistem Dokumen Digital');
            });

            if (Mail::failures()) {
                Log::error('Mail failures detected', ['failures' => Mail::failures()]);
                // Don't throw exception - continue and return the OTP for testing
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            // Don't rethrow - just log the error and continue
            return false;
        }
    }

    // Verify the OTP
    public function verifyEmailOTP(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|numeric|digits:6'
            ]);

            $kemahasiswaan = Auth::guard('kemahasiswaan')->user();

            // Check if OTP is expired
            if (Carbon::now()->isAfter($kemahasiswaan->email_verification_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ]);
            }

            // Verify OTP
            if ($request->otp != $kemahasiswaan->email_verification_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ]);
            }

            // Update email and verification status
            $kemahasiswaan->email = $kemahasiswaan->verification_email;
            $kemahasiswaan->is_email_verified = true;
            $kemahasiswaan->email_verified_at = Carbon::now();
            $kemahasiswaan->email_verification_code = null;
            $kemahasiswaan->verification_email = null;
            $kemahasiswaan->save();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to verify OTP: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify OTP. Please try again.'
            ], 500);
        }
    }

    // Resend OTP
    public function resendOTP()
    {
        try {
            Log::info('Received resend OTP request');

            $kemahasiswaan = Auth::guard('kemahasiswaan')->user();
            Log::info('User authenticated', ['user_id' => $kemahasiswaan->id]);

            if (!$kemahasiswaan->verification_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'No email verification in progress.'
                ]);
            }

            // Generate new OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Update OTP and expiration time
            $kemahasiswaan->email_verification_code = $otp;
            $kemahasiswaan->email_verification_expires_at = Carbon::now()->addMinutes(15);
            $saved = $kemahasiswaan->save();

            if (!$saved) {
                Log::error('Failed to save new OTP data');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate new OTP. Please try again.'
                ], 500);
            }

            Log::info('New OTP generated for resend', [
                'user_id' => $kemahasiswaan->id,
                'verification_email' => $kemahasiswaan->verification_email,
                'otp' => $otp, // Don't log OTP in production!
                'expires_at' => $kemahasiswaan->email_verification_expires_at
            ]);

            // Send email with new OTP
            try {
                $this->sendOTPEmail($kemahasiswaan->verification_email, $otp, $kemahasiswaan->nama_kemahasiswaan);
                Log::info('Resend OTP email sent successfully');

                return response()->json([
                    'success' => true,
                    'message' => 'New OTP sent to your email. Please check your inbox.',
                    'debug_otp' => $otp
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send resend OTP email: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);

                // Return success with OTP even if email fails to allow testing
                return response()->json([
                    'success' => true,
                    'message' => 'Email might not have been sent, but OTP is generated: ' . $otp,
                    'debug_otp' => $otp
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process resendOTP: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show email verification modal
    public function showEmailVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        // No need to set session here, the modal will be shown via JavaScript
        return response()->json([
            'success' => true,
            'email' => $email
        ]);
    }

}
