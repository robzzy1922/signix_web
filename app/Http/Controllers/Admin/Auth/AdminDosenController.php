<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminDosenController extends Controller
{
    private const ITEMS_PER_PAGE = 10;
    private const PROFILE_PATH = 'profiles';

    // Validation rules untuk dosen
    private array $validationRules = [
        'nama_dosen' => 'required|string|max:255',
        'nip' => 'required|string|max:18|unique:dosen,nip',
        'email' => 'required|email|unique:dosen,email',
        'no_hp' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
        'password' => 'required|string|min:6',
        'prodi' => 'required|string|max:255',
        'profile' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ];

    private array $validationMessages = [
        'nama_dosen.required' => 'Nama dosen wajib diisi',
        'nama_dosen.max' => 'Nama dosen maksimal 255 karakter',
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
        $dosens = Dosen::paginate(self::ITEMS_PER_PAGE);
        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);
            $profileName = $this->handleProfileUpload($request);

            $this->createDosen($validatedData, $profileName);

            return redirect()
                ->route('admin.dosen.index')
                ->with('success', 'Dosen berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Error creating Dosen: ' . $e->getMessage());

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

    private function createDosen(array $validatedData, ?string $profileName): void
    {
        Dosen::create([
            'nama_dosen' => $validatedData['nama_dosen'],
            'nip' => $validatedData['nip'],
            'email' => $validatedData['email'],
            'no_hp' => $validatedData['no_hp'],
            'password' => Hash::make($validatedData['password']),
            'prodi' => $validatedData['prodi'],
            'profile' => $profileName
        ]);
    }

    public function edit(Dosen $dosen)
    {
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        try {
            // Modify validation rules for update
            $this->validationRules['nip'] = 'required|string|max:18|unique:dosen,nip,' . $dosen->id;
            $this->validationRules['email'] = 'required|email|unique:dosen,email,' . $dosen->id;
            $this->validationRules['password'] = 'nullable|string|min:6';
            $this->validationRules['profile'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';

            $validatedData = $this->validateRequest($request);

            // Handle profile update if new file is uploaded
            if ($request->hasFile('profile')) {
                // Delete old profile if exists
                if ($dosen->profile) {
                    $oldProfilePath = public_path(self::PROFILE_PATH . '/' . $dosen->profile);
                    if (file_exists($oldProfilePath)) {
                        unlink($oldProfilePath);
                    }
                }
                $profileName = $this->handleProfileUpload($request);
                $dosen->profile = $profileName;
            }

            // Update Dosen data
            $dosen->nama_dosen = $validatedData['nama_dosen'];
            $dosen->nip = $validatedData['nip'];
            $dosen->email = $validatedData['email'];
            $dosen->no_hp = $validatedData['no_hp'];
            $dosen->prodi = $validatedData['prodi'];

            // Update password only if provided
            if (!empty($validatedData['password'])) {
                $dosen->password = Hash::make($validatedData['password']);
            }

            $dosen->save();

            return redirect()
                ->route('admin.dosen.index')
                ->with('success', 'Data Dosen berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error updating Dosen: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Dosen $dosen)
    {
        try {
            // Delete profile image if exists
            if ($dosen->profile) {
                $profilePath = public_path(self::PROFILE_PATH . '/' . $dosen->profile);
                if (file_exists($profilePath)) {
                    unlink($profilePath);
                }
            }

            $dosen->delete();

            return redirect()
                ->route('admin.dosen.index')
                ->with('success', 'Data Dosen berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting Dosen: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }
}