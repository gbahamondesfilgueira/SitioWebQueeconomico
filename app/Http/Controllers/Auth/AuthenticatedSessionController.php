<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\TwoFactorLoginCodeNotification;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();
        $code = (string) random_int(100000, 999999);

        $user->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        $request->session()->put('login.id', $user->id);
        $request->session()->put('login.remember', $request->boolean('remember'));

        $status = 'Te enviamos un codigo de verificacion a tu correo.';

        try {
            $user->notify(new TwoFactorLoginCodeNotification($code));
            AuditLogger::record('login_two_factor_requested', 'auth', 'Codigo de verificacion enviado.', $request, $user->id);
        } catch (Throwable $exception) {
            report($exception);
            Log::warning('No fue posible enviar codigo 2FA por correo.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'code' => app()->isLocal() ? $code : null,
                'error' => $exception->getMessage(),
            ]);

            AuditLogger::record('login_two_factor_mail_failed', 'auth', 'No fue posible enviar el codigo 2FA por correo.', $request, $user->id);

            $request->session()->forget(['login.id', 'login.remember']);
            $user->forceFill([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ])->save();

            AuditLogger::record('login', 'auth', 'Inicio de sesion sin 2FA por falla SMTP.', $request, $user->id);

            return redirect()->intended(route('dashboard', absolute: false))
                ->with('status', 'Iniciaste sesion correctamente. No fue posible enviar el codigo de verificacion por correo; revisa la configuracion SMTP.');
        }

        Auth::guard('web')->logout();
        $request->session()->regenerate();

        $redirect = redirect()->route('two-factor.create')->with('status', $status);

        if (app()->isLocal()) {
            $redirect->with('two_factor_debug_code', $code);
        }

        return $redirect;
    }

    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        AuditLogger::record('logout', 'auth', 'Cierre de sesion.', $request, $userId);

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
