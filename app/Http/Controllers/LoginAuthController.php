<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $role = $request->input('role');
        $password = $request->input('password');
        $credentials = [];
    
        switch ($role) {
            case 'admin':
                $credentials = ['email' => $request->input('email'), 'password' => $password];
                $guard = 'admin';
                $redirect = '/admin/dashboard';
                break;
            case 'ormawa':
                $credentials = ['nim' => $request->input('nim'), 'password' => $password];
                $guard = 'ormawa';
                $redirect = '/ormawa/dashboard';
                break;
            case 'dosen':
                $credentials = ['nip' => $request->input('nip'), 'password' => $password];
                $guard = 'dosen';
                $redirect = '/dosen/dashboard';
                break;
            default:
                return back()->withErrors(['role' => 'Role tidak valid']);
        }
    
        if (Auth::guard($guard)->attempt($credentials)) {
            return redirect()->intended($redirect);
        }
    
        return back()->withErrors(['login' => 'Kredensial tidak valid']);
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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
