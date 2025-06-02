<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ormawas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrmawaAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'nim' => 'required',
                'password' => 'required',
            ]);

            $ormawa = Ormawas::where('nim', $request->nim)->first();
            
            // Log data ormawa yang ditemukan
            Log::info('Data Ormawa:', ['ormawa' => $ormawa]);

            if (!$ormawa || !Hash::check($request->password, $ormawa->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIM atau password salah'
                ], 401);
            }

            // Buat token dengan waktu kadaluarsa 1 jam
            $token = $ormawa->createToken('auth_token', ['*'], now()->addHour())->plainTextToken;
            
            // Log token yang dibuat
            Log::info('Token created:', ['token' => $token]);

            // Format data sesuai model
            $responseData = [
                'success' => true,
                'data' => [
                    'token' => $token,
                    'ormawa' => [
                        'id' => $ormawa->id,
                        'namaMahasiswa' => $ormawa->namaMahasiswa,
                        'namaOrmawa' => $ormawa->namaOrmawa,
                        'nim' => $ormawa->nim,
                        'email' => $ormawa->email,
                        'noHp' => $ormawa->noHp,
                        'profile' => $ormawa->profile
                    ]
                ],
                'message' => 'Login berhasil'
            ];

            // Log response yang akan dikirim
            Log::info('Response Login:', $responseData);

            return response()->json($responseData, 200);

        } catch (\Exception $e) {
            Log::error('Login Error:', ['error' => $e->getMessage()]);
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
            Log::error('Logout Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update profile (nama, email, noHp)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'namaMahasiswa' => 'required|string|max:255',
            'email' => 'required|email|unique:ormawas,email,' . $user->id,
            'noHp' => 'nullable|string|max:20',
        ]);

        $user->namaMahasiswa = $request->namaMahasiswa;
        $user->email = $request->email;
        $user->noHp = $request->noHp;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diupdate',
            'data' => $user,
        ]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
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
    }
}