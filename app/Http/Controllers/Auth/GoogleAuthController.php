<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        if (! class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            return redirect()->route('login')->with('status', 'El acceso con Google esta preparado. Falta instalar Socialite y configurar GOOGLE_CLIENT_ID / GOOGLE_CLIENT_SECRET.');
        }

        if (! $this->configureGoogleProvider()) {
            return redirect()->route('login')->with('status', 'El acceso con Google aun no esta activo. Configuralo en el panel de administracion.');
        }

        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if (! class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
            return redirect()->route('login')->with('status', 'El acceso con Google aun no esta configurado.');
        }

        if (! $this->configureGoogleProvider()) {
            return redirect()->route('login')->with('status', 'El acceso con Google aun no esta activo.');
        }

        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();
        $clientRole = Role::query()->firstOrCreate(['slug' => 'cliente'], [
            'name' => 'Cliente',
            'description' => 'Acceso a cuenta cliente.',
        ]);

        $user = User::query()->where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = User::query()->create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Cliente Google',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'auth_provider' => 'google',
                'role_id' => $clientRole->id,
                'password' => Hash::make(Str::password(32)),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'auth_provider' => 'google',
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        AuditLogger::record('login_google', 'auth', 'Inicio de sesion con Google.', $request, $user->id);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function configureGoogleProvider(): bool
    {
        $setting = Setting::current();

        if (! $setting->google_login_enabled) {
            return false;
        }

        $clientId = $setting->google_client_id ?: config('services.google.client_id');
        $clientSecret = $setting->google_client_secret ?: config('services.google.client_secret');
        $redirectUri = $setting->google_redirect_uri ?: config('services.google.redirect') ?: route('auth.google.callback');

        if (! $clientId || ! $clientSecret || ! $redirectUri) {
            return false;
        }

        config([
            'services.google.client_id' => $clientId,
            'services.google.client_secret' => $clientSecret,
            'services.google.redirect' => $redirectUri,
        ]);

        return true;
    }
}
