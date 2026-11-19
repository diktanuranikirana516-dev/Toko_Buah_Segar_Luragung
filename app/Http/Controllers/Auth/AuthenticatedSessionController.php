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

        // 5. Periksa role user dan arahkan ke halaman yang sesuai secara presisi
        if ($user->role === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
            // Bersihkan intended URL agar admin tidak terjebak kembali ke beranda pelanggan (Single-Page / Home)
            $request->session()->forget('url.intended');
            return redirect()->route('dashboard')->with('success', 'Selamat datang di Panel Administrator, ' . $user->name . '!');
        } else {
            // Jika pembeli / customer, arahkan ke beranda publik (hindari redirect ke panel admin jika sebelumnya mencoba akses admin)
            $intended = $request->session()->pull('url.intended');
            if ($intended && (str_contains($intended, '/dashboard') || str_contains($intended, '/transaksi-admin') || str_contains($intended, '/produk') || str_contains($intended, '/laporan') || str_contains($intended, '/pengaturan'))) {
                return redirect()->route('home')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }
            return $intended ? redirect()->to($intended) : redirect()->route('home');
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