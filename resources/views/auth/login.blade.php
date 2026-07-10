<x-guest-layout>
    <h1 class="h4 mb-3">Iniciar sesion</h1>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <a class="btn btn-outline-dark w-100 mb-3" href="{{ route('auth.google.redirect') }}">
        Continuar con Google
    </a>

    <div class="text-center text-secondary small mb-3">o ingresa con tu correo</div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check mb-3">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">Recordarme</label>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Olvide mi password</a>
            @endif
            <button class="btn btn-primary" type="submit">Entrar</button>
        </div>
    </form>
</x-guest-layout>
