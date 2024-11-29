<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Ormawas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AdminOrmawaController extends Controller
{
    private const ITEMS_PER_PAGE = 10;
    private const PROFILE_PATH = 'profiles';

    // Validation rules moved to a separate property for better maintainability
    private array $validationRules = [
        'namaMahasiswa' => 'required|string|max:255',
        'namaOrmawa' => 'required|string|max:255',
        'nim' => 'required|string|max:8|unique:ormawas,nim',
        'email' => 'required|email|unique:ormawas,email',
        'noHp' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
        'password' => 'required|string|min:6',
        'profile' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ];

    private array $validationMessages = [
        'namaMahasiswa.required' => 'Nama mahasiswa wajib diisi',
        'namaMahasiswa.max' => 'Nama mahasiswa maksimal 255 karakter',
        'namaOrmawa.required' => 'Nama organisasi wajib diisi',
        'namaOrmawa.max' => 'Nama organisasi maksimal 255 karakter',
        'nim.required' => 'NIM wajib diisi',
        'nim.max' => 'NIM maksimal 8 karakter',
        'nim.unique' => 'NIM sudah terdaftar',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'noHp.required' => 'Nomor HP wajib diisi',
        'noHp.max' => 'Nomor HP maksimal 15 karakter',
        'noHp.regex' => 'Nomor HP hanya boleh berisi angka',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 6 karakter',
        'profile.required' => 'Foto profil wajib diisi',
        'profile.image' => 'File harus berupa gambar',
        'profile.mimes' => 'Format gambar harus jpeg, png, atau jpg',
        'profile.max' => 'Ukuran gambar maksimal 2MB'
    ];

    public function index()
    {
        $ormawas = Ormawas::paginate(self::ITEMS_PER_PAGE);
        return view('admin.ormawa.index', compact('ormawas'));
    }

    public function create()
    {
        return view('admin.ormawa.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);
            $profileName = $this->handleProfileUpload($request);

            $this->createOrmawa($validatedData, $profileName);

            return redirect()
                ->route('admin.ormawa.index')
                ->with('success', 'Ormawa berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error('Error creating Ormawa: ' . $e->getMessage());

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

    private function createOrmawa(array $validatedData, ?string $profileName): void
    {
        Ormawas::create([
            'namaMahasiswa' => $validatedData['namaMahasiswa'],
            'namaOrmawa' => $validatedData['namaOrmawa'],
            'nim' => $validatedData['nim'],
            'email' => $validatedData['email'],
            'noHp' => $validatedData['noHp'],
            'password' => Hash::make($validatedData['password']),
            'profile' => $profileName
        ]);
    }

    public function edit(Ormawas $ormawa)
    {
        return view('admin.ormawa.edit', compact('ormawa'));
    }

    public function update(Request $request, Ormawas $ormawa)
    {
        try {
            // Modify validation rules for update
            $this->validationRules['nim'] = 'required|string|max:8|unique:ormawas,nim,' . $ormawa->id;
            $this->validationRules['email'] = 'required|email|unique:ormawas,email,' . $ormawa->id;
            $this->validationRules['password'] = 'nullable|string|min:6';
            $this->validationRules['profile'] = 'nullable|image|mimes:jpeg,png,jpg|max:2048';

            $validatedData = $this->validateRequest($request);

            // Handle profile update if new file is uploaded
            if ($request->hasFile('profile')) {
                // Delete old profile if exists
                if ($ormawa->profile) {
                    $oldProfilePath = public_path(self::PROFILE_PATH . '/' . $ormawa->profile);
                    if (file_exists($oldProfilePath)) {
                        unlink($oldProfilePath);
                    }
                }
                $profileName = $this->handleProfileUpload($request);
                $ormawa->profile = $profileName;
            }

            // Update Ormawa data
            $ormawa->namaMahasiswa = $validatedData['namaMahasiswa'];
            $ormawa->namaOrmawa = $validatedData['namaOrmawa'];
            $ormawa->nim = $validatedData['nim'];
            $ormawa->email = $validatedData['email'];
            $ormawa->noHp = $validatedData['noHp'];

            // Update password only if provided
            if (!empty($validatedData['password'])) {
                $ormawa->password = Hash::make($validatedData['password']);
            }

            $ormawa->save();

            return redirect()
                ->route('admin.ormawa.index')
                ->with('success', 'Data Ormawa berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Error updating Ormawa: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Ormawas $ormawa)
    {
        try {
            // Delete profile image if exists
            if ($ormawa->profile) {
                $profilePath = public_path(self::PROFILE_PATH . '/' . $ormawa->profile);
                if (file_exists($profilePath)) {
                    unlink($profilePath);
                }
            }

            $ormawa->delete();

            return redirect()
                ->route('admin.ormawa.index')
                ->with('success', 'Data Ormawa berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting Ormawa: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['exception' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }

    public function editProfile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => ['required', 'current_password:admin'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $admin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $admin = Auth::guard('admin')->user();

        if ($admin->profile) {
            Storage::delete('public/' . $admin->profile);
        }

        $path = $request->file('profile_photo')->store('admin-profiles', 'public');

        $admin->update([
            'profile' => $path,
        ]);

        return redirect()->back()->with('success', 'Profile photo updated successfully!');
    }

    public function destroyProfilePhoto()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->profile) {
            Storage::delete('public/' . $admin->profile);

            $admin->update([
                'profile' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Profile photo removed successfully!');
    }
}