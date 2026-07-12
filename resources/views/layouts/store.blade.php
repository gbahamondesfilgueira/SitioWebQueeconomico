@php
    $setting = \App\Models\Setting::current();
    $menuCategories = app(\App\Services\StorefrontService::class)->categoryMenu();
    $storeLogo = asset('images/que-economico-logo.png');
    $cartCount = 0;
    try {
        $activeCart = \App\Models\CartSession::query()
            ->where('status', 'active')
            ->when(auth()->check(), fn ($query) => $query->where('user_id', auth()->id()), fn ($query) => $query->where('session_id', request()->session()->getId()))
            ->with('items:id,cart_session_id,quantity')
            ->latest()
            ->first();
        $cartCount = (int) ($activeCart?->items->sum('quantity') ?? 0);
    } catch (\Throwable $exception) {
        $cartCount = 0;
    }
    $catalogPanels = [
        'latest' => [
            'label' => 'Lo ultimo',
            'badge' => 'Tendencias',
            'title' => 'Lo ultimo',
            'columns' => [
                ['title' => 'Mujer', 'items' => ['Aros y pendientes', 'Collares modernos', 'Pulseras doradas', 'Accesorios casuales', 'Joyeria minimalista']],
                ['title' => 'Calzado', 'items' => ['Botas mujer', 'Zapatillas urbanas', 'Sandalias temporada', 'Calzado outdoor']],
                ['title' => 'Belleza', 'items' => ['Cuidado facial', 'Perfumes arabes', 'Maquillaje diario', 'Belleza coreana', 'Sets de regalo']],
                ['title' => 'Hogar', 'items' => ['Organizadores', 'Decoracion', 'Cocina practica', 'Iluminacion', 'Bano y limpieza']],
                ['title' => 'Nueva temporada', 'items' => ['Ropa mujer', 'Ropa hombre', 'Zapatos mujer', 'Tecnologia util', 'Regalos destacados']],
            ],
        ],
        'brands' => [
            'label' => 'Mejores marcas',
            'title' => 'Mejores marcas',
            'columns' => [
                ['title' => 'Favoritas', 'items' => ['LikeShop', 'Generica', 'NN Joyas', 'Urban Trend', 'Casa Facil']],
                ['title' => 'Moda', 'items' => ['Mujer diaria', 'Hombre urbano', 'Accesorios moda', 'Calzado tendencia']],
                ['title' => 'Belleza', 'items' => ['Beauty F', 'Fenty Beauty', 'Perfumes Premium', 'Cuidado personal']],
                ['title' => 'Hogar', 'items' => ['Organiza Pro', 'Deco House', 'Cocina Smart', 'Ilumina']],
            ],
        ],
        'offers' => [
            'label' => 'Ofertas',
            'title' => 'Ofertas',
            'columns' => [
                ['title' => 'Descuentos', 'items' => ['Hasta 20%', 'Hasta 40%', 'Liquidacion', 'Ultimas unidades']],
                ['title' => 'Campanas', 'items' => ['Cyber ofertas', 'Flash sale', 'Compra 2 y paga menos', 'Cupones activos']],
                ['title' => 'Categorias', 'items' => ['Belleza en oferta', 'Moda rebajada', 'Hogar barato', 'Tecnologia conveniente']],
                ['title' => 'Packs', 'items' => ['Packs regalo', 'Combos hogar', 'Sets belleza', 'Packs accesorios']],
            ],
        ],
        'gifts' => [
            'label' => 'Regalos',
            'title' => 'Regalos',
            'columns' => [
                ['title' => 'Para ella', 'items' => ['Pendientes', 'Collares', 'Belleza', 'Accesorios dorados']],
                ['title' => 'Para el', 'items' => ['Tecnologia', 'Accesorios', 'Hogar practico', 'Cuidado personal']],
                ['title' => 'Por precio', 'items' => ['Menos de $5.000', 'Menos de $10.000', 'Menos de $20.000', 'Premium']],
                ['title' => 'Ocasiones', 'items' => ['Cumpleanos', 'Aniversario', 'Amigo secreto', 'Dia especial']],
            ],
        ],
        'women' => [
            'label' => 'Mujer',
            'title' => 'Mujer',
            'columns' => [
                ['title' => 'Joyeria', 'items' => ['Aros', 'Pendientes', 'Collares', 'Pulseras', 'Anillos']],
                ['title' => 'Moda', 'items' => ['Blusas', 'Poleras', 'Chaquetas', 'Pantalones', 'Vestidos']],
                ['title' => 'Calzado', 'items' => ['Botas', 'Zapatillas', 'Sandalias', 'Zapatos casuales']],
                ['title' => 'Accesorios', 'items' => ['Carteras', 'Cinturones', 'Lentes', 'Panuelo']],
            ],
        ],
        'men' => [
            'label' => 'Hombre',
            'title' => 'Hombre',
            'columns' => [
                ['title' => 'Ropa', 'items' => ['Poleras', 'Camisas', 'Chaquetas', 'Pantalones']],
                ['title' => 'Calzado', 'items' => ['Zapatillas', 'Botines', 'Outdoor', 'Casual']],
                ['title' => 'Accesorios', 'items' => ['Billeteras', 'Cinturones', 'Mochilas', 'Relojes']],
                ['title' => 'Cuidado', 'items' => ['Perfumes', 'Barberia', 'Cuidado facial', 'Sets regalo']],
            ],
        ],
        'shoes' => [
            'label' => 'Zapatos y Zapatillas',
            'title' => 'Zapatos y Zapatillas',
            'columns' => [
                ['title' => 'Mujer', 'items' => ['Botas', 'Botines', 'Zapatillas', 'Sandalias']],
                ['title' => 'Hombre', 'items' => ['Zapatillas urbanas', 'Outdoor', 'Casual', 'Deportivo']],
                ['title' => 'Temporada', 'items' => ['Invierno', 'Verano', 'Lluvia', 'Outdoor']],
                ['title' => 'Complementos', 'items' => ['Plantillas', 'Cordones', 'Limpieza', 'Organizadores']],
            ],
        ],
        'beauty' => [
            'label' => 'Belleza',
            'title' => 'Belleza',
            'columns' => [
                ['title' => 'Maquillaje', 'items' => ['Labios', 'Rostro', 'Ojos', 'Brochas']],
                ['title' => 'Cuidado facial', 'items' => ['Serums', 'Cremas', 'Limpieza', 'Mascarillas']],
                ['title' => 'Perfumes', 'items' => ['Arabes', 'Mujer', 'Hombre', 'Unisex']],
                ['title' => 'Cabello', 'items' => ['Shampoo', 'Tratamientos', 'Accesorios', 'Peinado']],
            ],
        ],
        'home' => [
            'label' => 'Hogar',
            'title' => 'Hogar',
            'columns' => [
                ['title' => 'Cocina', 'items' => ['Utensilios', 'Organizadores', 'Reposteria', 'Botellas']],
                ['title' => 'Decoracion', 'items' => ['Lamparas', 'Cuadros', 'Espejos', 'Textiles']],
                ['title' => 'Orden', 'items' => ['Cajas', 'Percheros', 'Estantes', 'Canastos']],
                ['title' => 'Limpieza', 'items' => ['Bano', 'Cocina', 'Pisos', 'Accesorios']],
            ],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/que-economico-logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/que-economico-logo.png') }}">
    <x-store.seo :seo="$seo ?? []" />
    @if ($setting->google_search_console_verification)
        <meta name="google-site-verification" content="{{ $setting->google_search_console_verification }}">
    @endif
    @if ($setting->google_tag_manager_id)
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer',@json($setting->google_tag_manager_id));
        </script>
    @endif
    @if ($setting->google_analytics_measurement_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ rawurlencode($setting->google_analytics_measurement_id) }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @json($setting->google_analytics_measurement_id));
        </script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="storefront qe-storefront">
    @if ($setting->google_tag_manager_id)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ rawurlencode($setting->google_tag_manager_id) }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <header class="qe-store-header sticky-top">
        <div class="qe-store-topbar">
            <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="small">Despacho a todo Chile | Retiro en tienda preparado</div>
                <div class="d-flex gap-3 small">
                    <a href="{{ route('store.pages.show', 'contacto') }}">Centro de ayuda</a>
                    <a href="{{ route('store.pages.show', 'politicas-de-envio') }}">Despachos</a>
                    <a href="{{ route('store.pages.show', 'terminos-y-condiciones') }}">Mis compras</a>
                </div>
            </div>
        </div>

        <div class="bg-white border-bottom">
            <div class="container py-2">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn qe-catalog-toggle d-lg-inline-flex" type="button" data-bs-toggle="offcanvas" data-bs-target="#catalogOffcanvas" aria-controls="catalogOffcanvas">
                        <span class="catalog-open">☰</span>
                        <span class="catalog-close">×</span>
                        <span class="d-none d-md-inline ms-2 fw-semibold">Catalogo</span>
                    </button>

                    <a class="qe-store-brand" href="{{ route('store.home') }}">
                        <img src="{{ $storeLogo }}" alt="{{ $setting->store_name }}">
                    </a>

                    <form action="{{ route('store.search') }}" class="qe-store-search flex-grow-1" role="search">
                        <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar">
                        <button class="btn" type="submit">Buscar</button>
                    </form>

                    @auth
                        <a class="btn btn-outline-dark d-none d-md-inline-flex" href="{{ route('account.dashboard') }}">Mi cuenta</a>
                    @else
                        <button class="btn btn-outline-dark d-none d-md-inline-flex" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Ingresar</button>
                    @endauth

                    <a class="btn qe-cart-button" href="{{ route('store.cart.index') }}">
                        Carrito
                        <span class="qe-cart-badge" data-cart-count @if($cartCount < 1) hidden @endif>{{ $cartCount }}</span>
                    </a>
                </div>
            </div>
        </div>

        <nav class="qe-store-nav d-none d-lg-block">
            <div class="container d-flex align-items-center gap-4 py-2 small fw-semibold">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ route('store.shop') }}">Tienda</a>
                <a href="{{ route('store.packs.index') }}">Packs</a>
                @foreach($menuCategories->take(6) as $category)
                    <a href="{{ route('store.categories.show', $category->slug) }}">{{ $category->name }}</a>
                @endforeach
                <a class="ms-auto text-danger" href="{{ route('store.shop', ['offer' => 1]) }}">Ofertas</a>
            </div>
        </nav>
        <nav class="qe-mobile-quick-nav d-lg-none">
            <a href="{{ url('/') }}">Home</a>

            <a href="{{ route('store.shop') }}">Tienda</a>
            <a href="{{ route('store.shop', ['offer' => 1]) }}">Ofertas</a>
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#catalogOffcanvas" aria-controls="catalogOffcanvas">Categorias</button>
            <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}">Mi Cuenta</a>
        </nav>
    </header>

    <div class="offcanvas offcanvas-start qe-catalog-offcanvas" tabindex="-1" id="catalogOffcanvas">
        <div class="qe-catalog-shell" data-catalog-shell>
            <aside class="qe-catalog-left">
                <div class="qe-catalog-left-header">
                    <strong>!Hola!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
                </div>

                <nav class="qe-catalog-side-list">
                    @foreach($catalogPanels as $key => $panel)
                        <button class="qe-catalog-side-link @if($loop->first) active @endif" type="button" data-catalog-panel-trigger="{{ $key }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                            <span>{{ $panel['label'] }} @if(!empty($panel['badge']))<span class="qe-catalog-badge">{{ $panel['badge'] }}</span>@endif</span>
                            <span>›</span>
                        </button>
                    @endforeach
                </nav>
            </aside>

            <section class="qe-catalog-mega">
                <div class="qe-catalog-mega-scroll">
                    @foreach($catalogPanels as $key => $panel)
                        <div class="qe-catalog-panel @if($loop->first) active @endif" data-catalog-panel="{{ $key }}">
                            <div class="qe-catalog-feature-title">
                                <span class="qe-catalog-star">☆</span>
                                <span>{{ $panel['title'] }}</span>
                            </div>

                            <div class="qe-catalog-columns">
                                @foreach($panel['columns'] as $column)
                                    <div class="qe-catalog-column">
                                        <div class="qe-catalog-column-block">
                                            <h3>{{ $column['title'] }}</h3>
                                            <a class="qe-catalog-view-all" href="{{ route('store.shop') }}">Ver todo</a>
                                            @foreach($column['items'] as $item)
                                                <a class="qe-catalog-child-link" href="{{ route('store.shop', ['q' => $item]) }}">
                                                    {{ $item }}
                                                    @if($loop->first)<span class="qe-catalog-new">Nuevo</span>@endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    @guest
        <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Accede a tu cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" role="tablist">
                            <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#loginPane" type="button">Iniciar sesion</button></li>
                            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#registerPane" type="button">Registrarme</button></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="loginPane">
                                <a class="btn btn-outline-dark w-100 mb-3" href="{{ route('auth.google.redirect') }}">Continuar con Google</a>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                                    <button class="btn btn-primary w-100" type="submit">Entrar</button>
                                </form>
                                <a class="small d-inline-block mt-2" href="{{ route('password.request') }}">Olvide mi password</a>
                            </div>
                            <div class="tab-pane fade" id="registerPane">
                                <a class="btn btn-outline-dark w-100 mb-3" href="{{ route('auth.google.redirect') }}">Registrarme con Google</a>
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <input type="text" name="name" class="form-control mb-2" placeholder="Nombre" required>
                                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                    <input type="text" name="phone" class="form-control mb-2" placeholder="Telefono" required>
                                    <input type="text" name="rut" class="form-control mb-2" placeholder="RUT opcional">
                                    <div class="row g-2">
                                        <div class="col-6"><input type="text" name="region" class="form-control mb-2" placeholder="Region" required></div>
                                        <div class="col-6"><input type="text" name="commune" class="form-control mb-2" placeholder="Comuna" required></div>
                                        <div class="col-6"><input type="text" name="city" class="form-control mb-2" placeholder="Ciudad" required></div>
                                        <div class="col-6"><input type="text" name="street" class="form-control mb-2" placeholder="Calle" required></div>
                                        <div class="col-6"><input type="text" name="number" class="form-control mb-2" placeholder="Numero" required></div>
                                        <div class="col-6"><input type="text" name="apartment" class="form-control mb-2" placeholder="Depto/Casa"></div>
                                    </div>
                                    <input type="text" name="reference" class="form-control mb-2" placeholder="Referencia">
                                    <div class="small text-secondary mb-2">Te enviaremos un enlace seguro para crear tu password.</div>
                                    <button class="btn btn-primary w-100" type="submit">Enviar enlace</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <main>
        <div class="qe-cart-toast" data-cart-toast hidden></div>
        @if(session('success'))<div class="container pt-3"><div class="alert alert-success">{{ session('success') }}</div></div>@endif
        @if(session('status'))<div class="container pt-3"><div class="alert alert-info">{{ session('status') }}</div></div>@endif
        @yield('content')
    </main>

    <footer class="qe-store-footer mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="{{ $storeLogo }}" alt="{{ $setting->store_name }}" class="qe-footer-logo mb-3">
                    <p class="text-white-50">Tienda preparada para ecommerce, POS y ventas presenciales.</p>
                    <div class="small">Instagram · Facebook · TikTok</div>
                </div>
                <div class="col-md-4">
                    <h3 class="h6">Links legales</h3>
                    <a href="{{ route('store.pages.show', 'politicas-de-envio') }}">Politicas de envio</a>
                    <a href="{{ route('store.pages.show', 'politicas-de-devolucion') }}">Politicas de devolucion</a>
                    <a href="{{ route('store.pages.show', 'terminos-y-condiciones') }}">Terminos y condiciones</a>
                    <a href="{{ route('store.pages.show', 'politica-de-privacidad') }}">Politica de privacidad</a>
                </div>
                <div class="col-md-4">
                    <h3 class="h6">Newsletter</h3>
                    <form method="POST" action="{{ route('store.newsletter') }}" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="source" value="footer">
                        <input type="email" name="email" class="form-control" placeholder="tu@email.cl" required>
                        <button class="btn btn-light">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
