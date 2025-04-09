<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
}
