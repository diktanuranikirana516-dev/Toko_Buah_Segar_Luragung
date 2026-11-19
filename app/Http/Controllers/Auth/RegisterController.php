<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Tampilkan formulir pendaftaran akun (Register View).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses data registrasi pengguna baru dengan validasi ketat,
     * Google reCAPTCHA v2, auto-login, regenerasi sesi, audit log,
     * dan pengalihan langsung ke halaman utama (Home).
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $role = $request->validated('role', 'customer');
        if (!in_array($role, ['customer', 'admin'])) {
            $role = 'customer';
        }

        // 1. Simpan Data Pengguna Baru Secara Aman (Anti Mass Assignment)
        $user = User::create([
            'name'     => $request->validated('name'),
            'email'    => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'role'     => $role,
        ]);

        // 2. Catat Log Keamanan Siber (Security Audit Logs)
        SecurityAuditLog::record(
            'REGISTER',
            "Registrasi akun baru berhasil: {$user->name} ({$user->email}) - Peran: " . strtoupper($user->role),
            [
                'user_id' => $user->id,
                'email'   => Str::mask($user->email, '*', 3),
                'role'    => $user->role,
                'ip'      => $request->ip(),
            ],
            $user->id
        );

        event(new Registered($user));

        // 3. Auto-Login Langsung Setelah Registrasi
        Auth::login($user);

        // 4. Regenerasi ID Sesi Pengguna (Mencegah Session Fixation Attack)
        $request->session()->regenerate();

        // 5. Conditional Redirection Berdasarkan Role Terpilih
        $request->session()->forget('url.intended');
        if ($user->role === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
            return redirect()->route('dashboard')->with('success', 'Selamat datang Administrator! Akun Anda berhasil didaftarkan dan Anda langsung masuk ke panel admin.');
        }

        return redirect()->route('home')->with('success', 'Selamat datang! Akun Anda berhasil didaftarkan dan Anda otomatis masuk. Silakan pilih buah segar favorit Anda.');
    }
}
