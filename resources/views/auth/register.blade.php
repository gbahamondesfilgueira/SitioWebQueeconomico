<x-guest-layout>
    <h1 class="h4 mb-3">Crear cuenta</h1>
    <p class="text-secondary">No pedimos password en el registro. Te enviaremos un correo seguro para crearla.</p>

    <a class="btn btn-outline-dark w-100 mb-3" href="{{ route('auth.google.redirect') }}">
        Registrarme con Google
    </a>

    <div class="text-center text-secondary small mb-3">o crea tu cuenta con correo</div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="name">Nombre</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <label class="form-label" for="phone">Telefono</label>
                <input id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label" for="rut">RUT</label>
                <input id="rut" class="form-control @error('rut') is-invalid @enderror" name="rut" value="{{ old('rut') }}">
                @error('rut') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <h2 class="h6 mt-2">Direccion de envio y facturacion</h2>
        <div class="row g-2">
            <div class="col-md-6 mb-3"><label class="form-label" for="region">Region</label><input id="region" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}" required>@error('region') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="col-md-6 mb-3"><label class="form-label" for="commune">Comuna</label><input id="commune" name="commune" class="form-control @error('commune') is-invalid @enderror" value="{{ old('commune') }}" required>@error('commune') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="col-md-6 mb-3"><label class="form-label" for="city">Ciudad</label><input id="city" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>@error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="col-md-6 mb-3"><label class="form-label" for="street">Calle</label><input id="street" name="street" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}" required>@error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="col-md-4 mb-3"><label class="form-label" for="number">Numero</label><input id="number" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required>@error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
            <div class="col-md-4 mb-3"><label class="form-label" for="apartment">Depto/Casa</label><input id="apartment" name="apartment" class="form-control" value="{{ old('apartment') }}"></div>
            <div class="col-md-4 mb-3"><label class="form-label" for="reference">Referencia</label><input id="reference" name="reference" class="form-control" value="{{ old('reference') }}"></div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('login') }}">Ya tengo cuenta</a>
            <button class="btn btn-primary" type="submit">Enviar enlace</button>
        </div>
    </form>
</x-guest-layout>
