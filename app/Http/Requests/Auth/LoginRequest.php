<?php

namespace App\Http\Requests\Auth;

use App\Models\SecurityAuditLog;
use App\Rules\ReCaptcha;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Sanitasi input sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->input('email', ''))),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => [config('services.recaptcha.secret_key') ? 'required' : 'nullable', new ReCaptcha()],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     * Dilengkapi audit logging (Defense in Depth).
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            $maskedEmail = Str::mask($this->input('email', ''), '*', 3);

            // Audit Log File
            Log::warning('Percobaan login gagal terdeteksi (Failed Login Attempt)', [
                'email'      => $maskedEmail,
                'ip'         => $this->ip(),
                'user_agent' => $this->userAgent(),
            ]);

            // Database Security Audit Trail
            SecurityAuditLog::record('LOGIN_FAILED', 'Percobaan login gagal terdeteksi', ['email' => $maskedEmail]);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $maskedEmail = Str::mask($this->input('email', ''), '*', 3);

        // Audit Log: Catat indikasi brute-force
        Log::warning('Akun terkunci sementara akibat rate limit login terlampaui (Lockout)', [
            'email' => $maskedEmail,
            'ip'    => $this->ip(),
        ]);

        SecurityAuditLog::record('LOGIN_LOCKOUT', 'Akun terkunci sementara akibat rate limit terlampaui', ['email' => $maskedEmail]);

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
