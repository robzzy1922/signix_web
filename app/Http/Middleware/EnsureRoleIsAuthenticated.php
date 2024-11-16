<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EnsureRoleIsAuthenticated
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::guard($role)->check()) {
            return redirect()->route('login')->withErrors(['login' => 'Anda harus login terlebih dahulu.']);
        }

        return $next($request);
    }
}
