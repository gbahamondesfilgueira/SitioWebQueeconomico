<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => Setting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::current();

        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'rut' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'maintenance_mode' => ['nullable', 'boolean'],
            'google_login_enabled' => ['nullable', 'boolean'],
            'google_client_id' => ['nullable', 'string', 'max:1000'],
            'google_client_secret' => ['nullable', 'string', 'max:1000'],
            'google_redirect_uri' => ['nullable', 'url', 'max:255'],
            'google_search_console_verification' => ['nullable', 'string', 'max:255'],
            'google_analytics_measurement_id' => ['nullable', 'string', 'max:50'],
            'google_tag_manager_id' => ['nullable', 'string', 'max:50'],
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }

            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        unset($validated['logo']);
        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');
        $validated['google_login_enabled'] = $request->boolean('google_login_enabled');

        if (! $request->filled('google_client_secret')) {
            unset($validated['google_client_secret']);
        }

        $setting->update($validated);

        AuditLogger::record('updated', 'settings', 'Configuración general actualizada.');

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
