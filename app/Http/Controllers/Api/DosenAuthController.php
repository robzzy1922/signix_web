<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DosenAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required',
                'password' => 'required',
            ]);

            $dosen = Dosen::where('nip', $request->nip)->first();

            Log::info('Data Dosen:', ['dosen' => $dosen]);

            if (!$dosen || !Hash::check($request->password, $dosen->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP atau password salah'
                ], 401);
            }

            $token = $dosen->createToken('auth_token', ['*'], now()->addHour())->plainTextToken;

            Log::info('Token created for dosen:', ['token' => $token]);

            $responseData = [
                'success' => true,
                'data' => [
                    'token' => $token,
                    'dosen' => [
                        'id' => $dosen->id,
                        'namaDosen' => $dosen->nama_dosen,
                        'nip' => $dosen->nip,
                        'email' => $dosen->email,
                        'noHp' => $dosen->no_hp,
                        'prodi' => $dosen->prodi,
                        'profile' => $dosen->profile,
                    ]
                ],
                'message' => 'Login berhasil'
            ];

            Log::info('Response Login Dosen:', $responseData);

            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            Log::error('Login Dosen Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout Dosen Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update profile (nama, email, noHp)
    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'namaDosen' => 'required|string|max:255',
                'email' => 'required|email|unique:dosen,email,' . $user->id,
                'noHp' => 'nullable|string|max:20',
            ]);

            $user->nama_dosen = $request->namaDosen;
            $user->email = $request->email;
            $user->no_hp = $request->noHp;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diupdate',
                'data' => [
                    'id' => $user->id,
                    'namaDosen' => $user->nama_dosen,
                    'nip' => $user->nip,
                    'email' => $user->email,
                    'noHp' => $user->no_hp,
                    'prodi' => $user->prodi,
                    'profile' => $user->profile,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Update Profile Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate profil: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update password
    public function updatePassword(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah',
                ], 422);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah',
            ]);
        } catch (\Exception $e) {
            Log::error('Update Password Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update profile photo
    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $user = $request->user();

            // Delete old photo if exists
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }

            // Store new photo
            $file = $request->file('photo');
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('profiles', $filename, 'public');

            $user->profile = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupdate',
                'data' => [
                    'profile' => Storage::url($path)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Update Photo Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate foto profil: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete profile photo
    public function destroyPhoto(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }

            $user->profile = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Photo Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto profil: ' . $e->getMessage()
            ], 500);
        }
    }
}