<x-guest-layout>
    <h1 class="h4 mb-3">Recuperar password</h1>
    <p class="text-secondary">Ingresa tu email y enviaremos un enlace para restablecer tu password.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('login') }}">Volver</a>
            <button class="btn btn-primary" type="submit">Enviar enlace</button>
        </div>
    </form>
</x-guest-layout>
