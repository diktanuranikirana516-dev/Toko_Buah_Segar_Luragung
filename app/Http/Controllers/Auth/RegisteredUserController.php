<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Dilindungi dengan RegisterRequest & anti-mass assignment.
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $role = $request->validated('role', 'customer');
        if (!in_array($role, ['customer', 'admin'])) {
            $role = 'customer';
        }

        $user = new User();
        $user->name = $request->validated('name');
        $user->email = $request->validated('email');
        $user->password = Hash::make($request->validated('password'));
        $user->role = $role;
        $user->save();

        // Audit Trail: Pendaftaran akun baru
        \App\Models\SecurityAuditLog::record(
            'REGISTER',
            "Akun baru terdaftar: {$user->name} ({$user->email}) - Peran: " . strtoupper($user->role),
            [
                'user_id' => $user->id,
                'email'   => \Illuminate\Support\Str::mask($user->email, '*', 3),
                'role'    => $user->role,
                'ip'      => $request->ip(),
            ],
            $user->id
        );

        event(new Registered($user));

        // Auto-login langsung setelah registrasi berhasil
        Auth::login($user);

        // Regenerasi sesi untuk mencegah Session Fixation Attack
        $request->session()->regenerate();

        // Conditional Redirection Berdasarkan Role Terpilih
        $request->session()->forget('url.intended');
        if ($user->role === 'admin' || (method_exists($user, 'isAdmin') && $user->isAdmin())) {
            return redirect()->route('dashboard')->with('success', 'Selamat datang Administrator! Akun Anda berhasil didaftarkan dan Anda langsung masuk ke panel admin.');
        }

        // Setelah mendaftar, arahkan customer langsung ke halaman utama belanja (Home-Centric)
        return redirect()->route('home')->with('success', 'Selamat datang! Akun Anda berhasil didaftarkan dan Anda telah otomatis masuk. Silakan pilih buah segar favorit Anda.');
    }
}