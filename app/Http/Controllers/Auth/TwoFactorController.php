<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorLoginCodeNotification;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class TwoFactorController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::query()->find($request->session()->get('login.id'));

        if (! $user || ! hash_equals((string) $user->two_factor_code, (string) $request->code) || $user->two_factor_expires_at?->isPast()) {
            return back()->withErrors(['code' => 'El codigo ingresado no es valido o ya vencio.']);
        }

        $remember = (bool) $request->session()->pull('login.remember', false);
        $request->session()->forget('login.id');

        $user->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
            'two_factor_verified_at' => now(),
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        Auth::login($user, $remember);
        $request->session()->regenerate();

        AuditLogger::record('login', 'auth', 'Inicio de sesion con verificacion de dos pasos.', $request, $user->id);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = User::query()->find($request->session()->get('login.id'));

        if (! $user) {
            return redirect()->route('login');
        }

        $code = (string) random_int(100000, 999999);
        $user->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        try {
            $user->notify(new TwoFactorLoginCodeNotification($code));
        } catch (Throwable $exception) {
            report($exception);
            Log::warning('No fue posible reenviar codigo 2FA por correo.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'code' => app()->isLocal() ? $code : null,
                'error' => $exception->getMessage(),
            ]);

            return back()
                ->with('status', 'No se pudo enviar el correo SMTP desde el servidor local. Usa el codigo mostrado abajo o revisa storage/logs/laravel.log.')
                ->with('two_factor_debug_code', $code);
        }

        return back()->with('status', 'Enviamos un nuevo codigo a tu correo.');
    }
}
