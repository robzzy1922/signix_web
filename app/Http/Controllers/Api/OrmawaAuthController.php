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
}