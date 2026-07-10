@extends('layouts.admin')

@section('title', 'Configuración')
@section('page-title', 'Configuración general')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label" for="store_name">Nombre de la tienda</label>
                    <input class="form-control @error('store_name') is-invalid @enderror" id="store_name" name="store_name" value="{{ old('store_name', $setting->store_name) }}" required>
                    @error('store_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="legal_name">Nombre legal de la empresa</label>
                    <input class="form-control @error('legal_name') is-invalid @enderror" id="legal_name" name="legal_name" value="{{ old('legal_name', $setting->legal_name) }}">
                    @error('legal_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="rut">RUT empresa</label>
                    <input class="form-control @error('rut') is-invalid @enderror" id="rut" name="rut" value="{{ old('rut', $setting->rut) }}">
                    @error('rut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="email">Email de contacto</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $setting->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="phone">Teléfono</label>
                    <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $setting->phone) }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="address">Dirección comercial</label>
                    <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $setting->address) }}">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="currency">Moneda principal</label>
                    <input class="form-control @error('currency') is-invalid @enderror" id="currency" name="currency" value="{{ old('currency', $setting->currency) }}" required>
                    @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="tax_percentage">IVA por defecto (%)</label>
                    <input class="form-control @error('tax_percentage') is-invalid @enderror" id="tax_percentage" name="tax_percentage" type="number" step="0.01" min="0" max="100" value="{{ old('tax_percentage', $setting->tax_percentage) }}" required>
                    @error('tax_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="logo">Logo</label>
                    <input class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" type="file" accept="image/*">
                    @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($setting->logo_path)
                        <div class="small text-secondary mt-1">Logo actual: {{ $setting->logo_path }}</div>
                    @endif
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="maintenance_mode" name="maintenance_mode" value="1" @checked(old('maintenance_mode', $setting->maintenance_mode))>
                        <label class="form-check-label" for="maintenance_mode">Modo mantenimiento</label>
                    </div>
                </div>

                <div class="col-12">
                    <hr class="my-2">
                    <h2 class="h5 mb-1">Google, SEO y medicion</h2>
                    <p class="text-secondary small mb-0">Completa estos campos para activar login con Google, Search Console, Analytics y Tag Manager.</p>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="google_login_enabled" name="google_login_enabled" value="1" @checked(old('google_login_enabled', $setting->google_login_enabled))>
                        <label class="form-check-label" for="google_login_enabled">Permitir registro e inicio de sesion con Google</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="google_client_id">Google Client ID</label>
                    <input class="form-control @error('google_client_id') is-invalid @enderror" id="google_client_id" name="google_client_id" value="{{ old('google_client_id', $setting->google_client_id) }}" placeholder="xxxxxxxx.apps.googleusercontent.com">
                    @error('google_client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="google_client_secret">Google Client Secret</label>
                    <input class="form-control @error('google_client_secret') is-invalid @enderror" id="google_client_secret" name="google_client_secret" type="password" value="" placeholder="{{ $setting->google_client_secret ? 'Guardado. Escribe uno nuevo solo si deseas cambiarlo.' : 'Google Client Secret' }}">
                    @error('google_client_secret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="google_redirect_uri">URL de retorno OAuth</label>
                    <input class="form-control @error('google_redirect_uri') is-invalid @enderror" id="google_redirect_uri" name="google_redirect_uri" value="{{ old('google_redirect_uri', $setting->google_redirect_uri ?: route('auth.google.callback')) }}">
                    @error('google_redirect_uri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Esta misma URL debe quedar autorizada en Google Cloud.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="google_search_console_verification">Search Console verification</label>
                    <input class="form-control @error('google_search_console_verification') is-invalid @enderror" id="google_search_console_verification" name="google_search_console_verification" value="{{ old('google_search_console_verification', $setting->google_search_console_verification) }}" placeholder="codigo de verificacion">
                    @error('google_search_console_verification') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="google_analytics_measurement_id">Google Analytics Measurement ID</label>
                    <input class="form-control @error('google_analytics_measurement_id') is-invalid @enderror" id="google_analytics_measurement_id" name="google_analytics_measurement_id" value="{{ old('google_analytics_measurement_id', $setting->google_analytics_measurement_id) }}" placeholder="G-XXXXXXXXXX">
                    @error('google_analytics_measurement_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="google_tag_manager_id">Google Tag Manager ID</label>
                    <input class="form-control @error('google_tag_manager_id') is-invalid @enderror" id="google_tag_manager_id" name="google_tag_manager_id" value="{{ old('google_tag_manager_id', $setting->google_tag_manager_id) }}" placeholder="GTM-XXXXXXX">
                    @error('google_tag_manager_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Guardar configuración</button>
                </div>
            </form>
        </div>
    </div>
@endsection
