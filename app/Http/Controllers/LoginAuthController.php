<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ormawa;

class LoginAuthController extends Controller
{

    private $response = [
        'message' => 'null',
        'status' => 'null',
    ];

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'role' => 'required|in:admin,ormawa,dosen',
                'password' => 'required|string',
            ]);

            \Log::info('Login attempt:', [
                'role' => $request->role,
                'credentials' => $request->except('password')
            ]);

            $role = $request->role;
            $credentials = [];
            $password = $request->password;

            switch ($role) {
                case 'admin':
                    $request->validate(['email' => 'required|email']);
                    $credentials = ['email' => $request->email, 'password' => $password];
                    $guard = 'admin';
                    break;

                case 'ormawa':
                    $request->validate(['nim' => 'required|string']);
                    $credentials = ['nim' => $request->nim, 'password' => $password];
                    $guard = 'ormawa';
                    break;

                case 'dosen':
                    $request->validate(['nip' => 'required|string']);
                    $credentials = ['nip' => $request->nip, 'password' => $password];
                    $guard = 'dosen';
                    break;

                default:
                    return response()->json(['message' => 'Role tidak valid'], 400);
            }

            if (Auth::guard($guard)->attempt($credentials)) {
                $user = Auth::guard($guard)->user();
                
                // Untuk API request
                if ($request->wantsJson()) {
                    $token = $user->createToken($role . '-token')->plainTextToken;
                    return response()->json([
                        'message' => 'Login berhasil',
                        'token' => $token,
                        'user' => $user,
                    ], 200);
                }
                
                // Untuk web request
                return redirect()->intended($this->getRedirectRoute($role));
            }

            // Handle failed login attempts
            $errorMessage = match($role) {
                'ormawa' => 'NIM atau password salah',
                'admin' => 'Email atau password salah',
                'dosen' => 'NIP atau password salah',
                default => 'Kredensial tidak valid'
            };

            if ($request->wantsJson()) {
                return response()->json(['message' => $errorMessage], 401);
            }

            // For web requests, redirect back with error message
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => $errorMessage]);

        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan pada server',
                    'error' => $e->getMessage()
                ], 500);
            }

            // For web requests, redirect back with error message
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => 'Terjadi kesalahan pada server']);
        }
    }

    public function dashboardOrmawa()
    {
        // Logika untuk menampilkan dashboard
        return redirect()->route('ormawa.dashboard'); // Pastikan view 'dashboard' ada
    }

    public function dashboardDosen()
    {
        // Logika untuk menampilkan dashboard
        return redirect()->route('dosen.dashboard'); // Pastikan view 'dashboard' ada
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    private function getRedirectRoute($role)
    {
        return match($role) {
            'admin' => '/admin/dashboard',
            'ormawa' => '/ormawa/dashboard',
            'dosen' => '/dosen/dashboard',
            default => '/'
        };
    }
}