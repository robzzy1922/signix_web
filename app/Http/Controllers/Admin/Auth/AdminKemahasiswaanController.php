<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Models\Kemahasiswaan;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminKemahasiswaanController extends Controller
{
    private const ITEMS_PER_PAGE = 10;
    private const PROFILE_PATH = 'profiles';

    // Validation rules untuk dosen
    private array $validationRules = [
        'nama_kemahasiswaan' => 'required|string|max:255',
        'nip' => 'required|string|max:18|unique:kemahasiswaan,nip',
        'email' => 'required|email|unique:kemahasiswaan,email',
        'no_hp' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
        'password' => 'required|string|min:6',
        'prodi' => 'required|string|max:255',
        'profile' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ];

    private array $validationMessages = [
        'nama_kemahasiswaan.required' => 'Nama kemahasiswaan wajib diisi',
        'nama_kemahasiswaan.max' => 'Nama kemahasiswaan maksimal 255 karakter',
        'nip.required' => 'NIP wajib diisi',
        'nip.max' => 'NIP maksimal 18 karakter',
        'nip.unique' => 'NIP sudah terdaftar',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'no_hp.required' => 'Nomor HP wajib diisi',
        'no_hp.max' => 'Nomor HP maksimal 15 karakter',
        'no_hp.regex' => 'Nomor HP hanya boleh berisi angka',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 6 karakter',
        'prodi.required' => 'Program studi wajib diisi',
        'prodi.max' => 'Program studi maksimal 255 karakter',
        'profile.required' => 'Foto profil wajib diisi',
        'profile.image' => 'File harus berupa gambar',
        'profile.mimes' => 'Format gambar harus jpeg, png, atau jpg',
        'profile.max' => 'Ukuran gambar maksimal 2MB'
    ];

    public function index()
    {
        $kemahasiswaans = Kemahasiswaan::latest()->paginate(self::ITEMS_PER_PAGE);
        return view('admin.kemahasiswaan.index', compact('kemahasiswaans'));
    }

    public function create()
    {
        return view('admin.kemahasiswaan.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);
            $profileName = $this->handleProfileUpload($request);

            $this->createKemahasiswaan($validatedData, $profileName);

            return redirect()
                ->route('admin.kemahasiswaan.index')
                ->with('success', 'Kemahasiswaan berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Error creating Kemahasiswaan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    private function validateRequest(Request $request): array
    {
        $validator = Validator::make(
            $request->all(),
            $this->validationRules,
            $this->validationMessages
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return $validator->validated();
    }

    private function handleProfileUpload(Request $request): ?string
    {
        if (!$request->hasFile('profile')) {
            return null;
        }

        $profileFile = $request->file('profile');
        $profileName = time() . '.' . $profileFile->getClientOriginalExtension();

        $profileFile->move(
            public_path(self::PROFILE_PATH),
            $profileName
        );

        return $profileName;
    }

    private function createKemahasiswaan(array $validatedData, ?string $profileName): void
    {
        Kemahasiswaan::create([
            'nama_kemahasiswaan' => $validatedData['nama_kemahasiswaan'],
            'nip' => $validatedData['nip'],
            'email' => $validatedData['email'],
            'no_hp' => $validatedData['no_hp'],
            'password' => Hash::make($validatedData['password']),
            'prodi' => $validatedData['prodi'],
            'profile' => $profileName
        ]);
    }

    public function edit(Kemahasiswaan $kemahasiswaan)
    {
        return view('admin.kemahasiswaan.edit', compact('kemahasiswaan'));
    }

    public function update(Request $request, Kemahasiswaan $kemahasiswaan)
    {
        try {
            // Modify validation rules for update
            $this->validationRules['nip'] = 'required|string|max:18|unique:kemahasiswaan,nip,' . $kemahasiswaan->id;
            $this->validationRules['email'] = 'required|email|unique:kemahasiswaan,email,' . $kemahasiswaan->id;
            $this->validationRules['password'] = 'nullable|string|min:6';
            $this->validationRules['profile'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';

            $validatedData = $this->validateRequest($request);

            // Handle profile update if new file is uploaded
            if ($request->hasFile('profile')) {
                // Delete old profile if exists
                if ($kemahasiswaan->profile) {
                    $oldProfilePath = public_path(self::PROFILE_PATH . '/' . $kemahasiswaan->profile);
                    if (file_exists($oldProfilePath)) {
                        unlink($oldProfilePath);
                    }
                }
                $profileName = $this->handleProfileUpload($request);
                $kemahasiswaan->profile = $profileName;
            }

            // Update Dosen data
            $kemahasiswaan->nama_kemahasiswaan = $validatedData['nama_kemahasiswaan'];
            $kemahasiswaan->nip = $validatedData['nip'];
            $kemahasiswaan->email = $validatedData['email'];
            $kemahasiswaan->no_hp = $validatedData['no_hp'];
            $kemahasiswaan->prodi = $validatedData['prodi'];

            // Update password only if provided
            if (!empty($validatedData['password'])) {
                $kemahasiswaan->password = Hash::make($validatedData['password']);
            }

            $kemahasiswaan->save();

            return redirect()
                ->route('admin.kemahasiswaan.index')
                ->with('success', 'Data Kemahasiswaan berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error updating Kemahasiswaan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Kemahasiswaan $kemahasiswaan)
    {
        try {
            // Delete profile image if exists
            if ($kemahasiswaan->profile) {
                $profilePath = public_path(self::PROFILE_PATH . '/' . $kemahasiswaan->profile);
                if (file_exists($profilePath)) {
                    unlink($profilePath);
                }
            }

            $kemahasiswaan->delete();

            return redirect()
                ->route('admin.kemahasiswaan.index')
                ->with('success', 'Data Kemahasiswaan berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting Kemahasiswaan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}
