<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika belum login, redirect ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $path = $request->path();

        // Jika path adalah logout, izinkan akses
        if ($path === 'admin/logout') {
            return $next($request);
        }

        // Cek akses ke halaman user
        if (str_starts_with($path, 'admin/users')) {
            if (!$user->hasAnyRole(['super admin', 'admin umum'])) {
                return redirect()->route('dashboard');
            }
        }

        // Super admin bisa akses semua
        if ($user->hasRole('super admin')) {
            return $next($request);
        }

        // Admin umum bisa akses semua kecuali pembayaran
        if ($user->hasRole('admin umum')) {
            if (str_contains($path, 'pembayaran')) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        }

        // Keuangan hanya bisa akses pembayaran
        if ($user->hasRole('keuangan')) {
            if (!str_contains($path, 'pembayaran')) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        }

        // Role lain hanya bisa view (GET request)
        if ($request->method() !== 'GET') {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
