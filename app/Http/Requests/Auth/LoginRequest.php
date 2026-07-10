<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::query()->where('email', $this->string('email'))->first();

        if ($user?->locked_until && $user->locked_until->isFuture()) {
            throw ValidationException::withMessages([
                'email' => 'La cuenta está bloqueada temporalmente. Intenta nuevamente más tarde.',
            ]);
        }

        if (! Auth::attempt([...$this->only('email', 'password'), 'is_active' => true], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->registerFailedAttempt($user);

            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son válidas o el usuario está inactivo.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    private function registerFailedAttempt(?User $user): void
    {
        if (! $user) {
            return;
        }

        $attempts = $user->failed_login_attempts + 1;

        $user->forceFill([
            'failed_login_attempts' => $attempts,
            'locked_until' => $attempts >= 5 ? now()->addMinutes(15) : null,
        ])->save();
    }
}
