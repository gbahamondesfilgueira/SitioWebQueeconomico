<?php
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
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if (isset($component)) { $__componentOriginal36ae8918758f6a2d02ad7438a536f96f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal36ae8918758f6a2d02ad7438a536f96f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.store.seo','data' => ['seo' => $seo ?? []]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('store.seo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['seo' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($seo ?? [])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal36ae8918758f6a2d02ad7438a536f96f)): ?>
<?php $attributes = $__attributesOriginal36ae8918758f6a2d02ad7438a536f96f; ?>
<?php unset($__attributesOriginal36ae8918758f6a2d02ad7438a536f96f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal36ae8918758f6a2d02ad7438a536f96f)): ?>
<?php $component = $__componentOriginal36ae8918758f6a2d02ad7438a536f96f; ?>
<?php unset($__componentOriginal36ae8918758f6a2d02ad7438a536f96f); ?>
<?php endif; ?>
    <?php if($setting->google_search_console_verification): ?>
        <meta name="google-site-verification" content="<?php echo e($setting->google_search_console_verification); ?>">
    <?php endif; ?>
    <?php if($setting->google_tag_manager_id): ?>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer',<?php echo json_encode($setting->google_tag_manager_id, 15, 512) ?>);
        </script>
    <?php endif; ?>
    <?php if($setting->google_analytics_measurement_id): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(rawurlencode($setting->google_analytics_measurement_id)); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', <?php echo json_encode($setting->google_analytics_measurement_id, 15, 512) ?>);
        </script>
    <?php endif; ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="storefront qe-storefront">
    <?php if($setting->google_tag_manager_id): ?>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo e(rawurlencode($setting->google_tag_manager_id)); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php endif; ?>
    <header class="qe-store-header sticky-top">
        <div class="qe-store-topbar">
            <div class="container d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="small">Despacho a todo Chile | Retiro en tienda preparado</div>
                <div class="d-flex gap-3 small">
                    <a href="<?php echo e(route('store.pages.show', 'contacto')); ?>">Centro de ayuda</a>
                    <a href="<?php echo e(route('store.pages.show', 'politicas-de-envio')); ?>">Despachos</a>
                    <a href="<?php echo e(route('store.pages.show', 'terminos-y-condiciones')); ?>">Mis compras</a>
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

                    <a class="qe-store-brand" href="<?php echo e(route('store.home')); ?>">
                        <img src="<?php echo e($storeLogo); ?>" alt="<?php echo e($setting->store_name); ?>">
                    </a>

                    <form action="<?php echo e(route('store.search')); ?>" class="qe-store-search flex-grow-1" role="search">
                        <input name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="Buscar">
                        <button class="btn" type="submit">Buscar</button>
                    </form>

                    <?php if(auth()->guard()->check()): ?>
                        <a class="btn btn-outline-dark d-none d-md-inline-flex" href="<?php echo e(route('account.dashboard')); ?>">Mi cuenta</a>
                    <?php else: ?>
                        <button class="btn btn-outline-dark d-none d-md-inline-flex" type="button" data-bs-toggle="modal" data-bs-target="#authModal">Ingresar</button>
                    <?php endif; ?>

                    <a class="btn qe-cart-button" href="<?php echo e(route('store.cart.index')); ?>">
                        Carrito
                        <span class="qe-cart-badge" data-cart-count <?php if($cartCount < 1): ?> hidden <?php endif; ?>><?php echo e($cartCount); ?></span>
                    </a>
                </div>
            </div>
        </div>

        <nav class="qe-store-nav d-none d-lg-block">
            <div class="container d-flex align-items-center gap-4 py-2 small fw-semibold">
                <a href="<?php echo e(route('store.shop')); ?>">Tienda</a>
                <a href="<?php echo e(route('store.packs.index')); ?>">Packs</a>
                <?php $__currentLoopData = $menuCategories->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('store.categories.show', $category->slug)); ?>"><?php echo e($category->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a class="ms-auto text-danger" href="<?php echo e(route('store.shop', ['offer' => 1])); ?>">Ofertas</a>
            </div>
        </nav>
        <nav class="qe-mobile-quick-nav d-lg-none">
            <a href="<?php echo e(route('store.shop')); ?>">Tienda</a>
            <a href="<?php echo e(route('store.shop', ['offer' => 1])); ?>">Ofertas</a>
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#catalogOffcanvas" aria-controls="catalogOffcanvas">Categorias</button>
            <a href="<?php echo e(auth()->check() ? route('account.dashboard') : route('login')); ?>">Mi Cuenta</a>
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
                    <?php $__currentLoopData = $catalogPanels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $panel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button class="qe-catalog-side-link <?php if($loop->first): ?> active <?php endif; ?>" type="button" data-catalog-panel-trigger="<?php echo e($key); ?>" aria-expanded="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                            <span><?php echo e($panel['label']); ?> <?php if(!empty($panel['badge'])): ?><span class="qe-catalog-badge"><?php echo e($panel['badge']); ?></span><?php endif; ?></span>
                            <span>›</span>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </nav>
            </aside>

            <section class="qe-catalog-mega">
                <div class="qe-catalog-mega-scroll">
                    <?php $__currentLoopData = $catalogPanels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $panel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="qe-catalog-panel <?php if($loop->first): ?> active <?php endif; ?>" data-catalog-panel="<?php echo e($key); ?>">
                            <div class="qe-catalog-feature-title">
                                <span class="qe-catalog-star">☆</span>
                                <span><?php echo e($panel['title']); ?></span>
                            </div>

                            <div class="qe-catalog-columns">
                                <?php $__currentLoopData = $panel['columns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="qe-catalog-column">
                                        <div class="qe-catalog-column-block">
                                            <h3><?php echo e($column['title']); ?></h3>
                                            <a class="qe-catalog-view-all" href="<?php echo e(route('store.shop')); ?>">Ver todo</a>
                                            <?php $__currentLoopData = $column['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a class="qe-catalog-child-link" href="<?php echo e(route('store.shop', ['q' => $item])); ?>">
                                                    <?php echo e($item); ?>

                                                    <?php if($loop->first): ?><span class="qe-catalog-new">Nuevo</span><?php endif; ?>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        </div>
    </div>

    <?php if(auth()->guard()->guest()): ?>
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
                                <a class="btn btn-outline-dark w-100 mb-3" href="<?php echo e(route('auth.google.redirect')); ?>">Continuar con Google</a>
                                <form method="POST" action="<?php echo e(route('login')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                                    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                                    <button class="btn btn-primary w-100" type="submit">Entrar</button>
                                </form>
                                <a class="small d-inline-block mt-2" href="<?php echo e(route('password.request')); ?>">Olvide mi password</a>
                            </div>
                            <div class="tab-pane fade" id="registerPane">
                                <a class="btn btn-outline-dark w-100 mb-3" href="<?php echo e(route('auth.google.redirect')); ?>">Registrarme con Google</a>
                                <form method="POST" action="<?php echo e(route('register')); ?>">
                                    <?php echo csrf_field(); ?>
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
    <?php endif; ?>

    <main>
        <div class="qe-cart-toast" data-cart-toast hidden></div>
        <?php if(session('success')): ?><div class="container pt-3"><div class="alert alert-success"><?php echo e(session('success')); ?></div></div><?php endif; ?>
        <?php if(session('status')): ?><div class="container pt-3"><div class="alert alert-info"><?php echo e(session('status')); ?></div></div><?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="qe-store-footer mt-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <img src="<?php echo e($storeLogo); ?>" alt="<?php echo e($setting->store_name); ?>" class="qe-footer-logo mb-3">
                    <p class="text-white-50">Tienda preparada para ecommerce, POS y ventas presenciales.</p>
                    <div class="small">Instagram · Facebook · TikTok</div>
                </div>
                <div class="col-md-4">
                    <h3 class="h6">Links legales</h3>
                    <a href="<?php echo e(route('store.pages.show', 'politicas-de-envio')); ?>">Politicas de envio</a>
                    <a href="<?php echo e(route('store.pages.show', 'politicas-de-devolucion')); ?>">Politicas de devolucion</a>
                    <a href="<?php echo e(route('store.pages.show', 'terminos-y-condiciones')); ?>">Terminos y condiciones</a>
                    <a href="<?php echo e(route('store.pages.show', 'politica-de-privacidad')); ?>">Politica de privacidad</a>
                </div>
                <div class="col-md-4">
                    <h3 class="h6">Newsletter</h3>
                    <form method="POST" action="<?php echo e(route('store.newsletter')); ?>" class="d-flex gap-2">
                        <?php echo csrf_field(); ?>
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
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\layouts\store.blade.php ENDPATH**/ ?>