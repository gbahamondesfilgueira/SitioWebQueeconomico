<x-guest-layout>
    <h1 class="h4 mb-3">Verificacion de dos pasos</h1>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (app()->isLocal() && session('two_factor_debug_code'))
        <div class="alert alert-warning">
            Codigo local de respaldo: <strong>{{ session('two_factor_debug_code') }}</strong>
        </div>
    @endif

    <p class="text-secondary">Enviamos un codigo de 6 digitos a tu correo. Ingresalo para completar el acceso.</p>

    <form method="POST" action="{{ route('two-factor.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="code">Codigo</label>
            <input id="code" class="form-control @error('code') is-invalid @enderror" type="text" name="code" inputmode="numeric" maxlength="6" required autofocus>
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-primary w-100" type="submit">Verificar y entrar</button>
    </form>

    <form method="POST" action="{{ route('two-factor.resend') }}" class="mt-3">
        @csrf
        <button class="btn btn-link p-0" type="submit">Reenviar codigo</button>
    </form>
</x-guest-layout>
