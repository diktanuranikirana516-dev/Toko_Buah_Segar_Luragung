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

        $userRole = strtolower(trim($user->role ?? 'customer'));

        $isAllowed = in_array($userRole, $roles, true);
        if (!$isAllowed && in_array('admin', $roles, true) && method_exists($user, 'isAdmin') && $user->isAdmin()) {
            $isAllowed = true;
        }

        if (! $isAllowed) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses untuk membuka halaman ini.');
        }

        return $next($request);
    }
}

