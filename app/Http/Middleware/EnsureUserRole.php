<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Otorisasi berbasis peran (RBAC) dengan prinsip Fail Secure.
     * Mencegah Broken Access Control & PrivEsc.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Jika belum terotentikasi, alihkan ke login
        if (! $user) {
            return redirect()->route('login');
        }

        $userRole = $user->role ?? 'customer';

        if (! in_array($userRole, $roles, true)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

