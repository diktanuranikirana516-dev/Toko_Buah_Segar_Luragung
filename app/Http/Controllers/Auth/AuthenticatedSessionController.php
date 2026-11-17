<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Proses autentikasi (cek email dan password)
        $request->authenticate();

        // 2. Regenerasi sesi untuk keamanan
        $request->session()->regenerate();

        // 3. Ambil data user yang sedang login
        $user = Auth::user();

        // 4. Catat Security Audit Trail
        \App\Models\SecurityAuditLog::record(
            'LOGIN_SUCCESS',
            "Pengguna {$user->name} berhasil masuk",
            ['role' => $user->role ?? 'customer'],
            $user->id
        );

        // 5. Periksa role user dan arahkan ke halaman yang sesuai
        if ($user->role === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
            // Jika admin, arahkan ke route 'dashboard'
            return redirect()->intended(route('dashboard', absolute: false));
        } else {
            // Jika pembeli / customer, arahkan langsung ke halaman utama belanja (Single-Page / Home-Centric)
            return redirect()->intended(route('home', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}