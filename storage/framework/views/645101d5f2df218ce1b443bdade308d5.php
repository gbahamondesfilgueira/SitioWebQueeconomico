<?php
    $setting = \App\Models\Setting::current();
    $sections = [
        'General' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'bi-speedometer2'],
            ['label' => 'Configuración', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'icon' => 'bi-gear'],
            ['label' => 'Usuarios', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'bi-people'],
            ['label' => 'Roles', 'disabled' => true, 'icon' => 'bi-shield-lock'],
            ['label' => 'Auditoría', 'route' => 'admin.audit-logs.index', 'active' => 'admin.audit-logs.*', 'icon' => 'bi-activity'],
        ],
        'Catálogo Maestro' => [
            ['label' => 'Categorías', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*', 'icon' => 'bi-folder'],
            ['label' => 'Marcas', 'route' => 'admin.brands.index', 'active' => 'admin.brands.*', 'icon' => 'bi-tag'],
            ['label' => 'Proveedores', 'route' => 'admin.suppliers.index', 'active' => 'admin.suppliers.*', 'icon' => 'bi-truck'],
            ['label' => 'Etiquetas', 'route' => 'admin.tags.index', 'active' => 'admin.tags.*', 'icon' => 'bi-bookmark'],
            ['label' => 'Unidades de medida', 'route' => 'admin.measurement-units.index', 'active' => 'admin.measurement-units.*', 'icon' => 'bi-rulers'],
            ['label' => 'Impuestos', 'route' => 'admin.taxes.index', 'active' => 'admin.taxes.*', 'icon' => 'bi-percent'],
            ['label' => 'Países de origen', 'route' => 'admin.origin-countries.index', 'active' => 'admin.origin-countries.*', 'icon' => 'bi-globe'],
        ],
        'Productos' => [
            ['label' => 'Productos', 'route' => 'admin.products.index', 'active' => 'admin.products.*', 'icon' => 'bi-box'],
            ['label' => 'Atributos', 'route' => 'admin.attributes.index', 'active' => 'admin.attributes.*', 'icon' => 'bi-sliders'],
            ['label' => 'Importaciones masivas', 'route' => 'admin.imports.products.index', 'active' => 'admin.imports.products.*', 'icon' => 'bi-list'],
            ['label' => 'Variantes', 'disabled' => true, 'active' => 'admin.products.variants.*', 'icon' => 'bi-grid'],
        ],
        'Motor Comercial' => [
            ['label' => 'Listas de precios', 'route' => 'admin.price-lists.index', 'active' => 'admin.price-lists.*', 'icon' => 'bi-cash'],
            ['label' => 'Promociones', 'route' => 'admin.promotions.index', 'active' => 'admin.promotions.*', 'icon' => 'bi-megaphone'],
            ['label' => 'Packs', 'route' => 'admin.product-packs.index', 'active' => 'admin.product-packs.*', 'icon' => 'bi-box2'],
            ['label' => 'Cupones', 'route' => 'admin.coupons.index', 'active' => 'admin.coupons.*', 'icon' => 'bi-ticket'],
            ['label' => 'Descuentos por cantidad', 'route' => 'admin.quantity-discounts.index', 'active' => 'admin.quantity-discounts.*', 'icon' => 'bi-layers'],
            ['label' => 'Calculadora de precios', 'route' => 'admin.pricing.calculator', 'active' => 'admin.pricing.calculator', 'icon' => 'bi-calculator'],
        ],
        'CRM Clientes' => [
            ['label' => 'Clientes', 'route' => 'admin.customers.index', 'active' => 'admin.customers.*', 'icon' => 'bi-person-vcard'],
            ['label' => 'Etiquetas cliente', 'route' => 'admin.customer-tags.index', 'active' => 'admin.customer-tags.*', 'icon' => 'bi-stars'],
        ],
        'Pedidos' => [
            ['label' => 'Pedidos', 'route' => 'admin.orders.index', 'active' => 'admin.orders.*', 'icon' => 'bi-receipt'],
            ['label' => 'Devoluciones', 'route' => 'admin.order-returns.index', 'active' => 'admin.order-returns.*', 'icon' => 'bi-return'],
        ],
        'Envíos' => [
            ['label' => 'Transportistas', 'route' => 'admin.shipping.carriers.index', 'active' => 'admin.shipping.carriers.*'],
            ['label' => 'Servicios', 'route' => 'admin.shipping.services.index', 'active' => 'admin.shipping.services.*'],
            ['label' => 'Zonas', 'route' => 'admin.shipping.zones.index', 'active' => 'admin.shipping.zones.*'],
            ['label' => 'Tarifas', 'route' => 'admin.shipping.rates.index', 'active' => 'admin.shipping.rates.*'],
            ['label' => 'Importar tarifas', 'route' => 'admin.shipping.imports.index', 'active' => 'admin.shipping.imports.*'],
            ['label' => 'Cotizador', 'route' => 'admin.shipping.quote-calculator', 'active' => 'admin.shipping.quote-calculator'],
            ['label' => 'Etiquetas', 'route' => 'admin.shipping.labels.index', 'active' => 'admin.shipping.labels.*'],
            ['label' => 'Tracking', 'route' => 'admin.shipping.tracking.index', 'active' => 'admin.shipping.tracking.*'],
            ['label' => 'Integraciones envío', 'route' => 'admin.shipping.integrations.index', 'active' => 'admin.shipping.integrations.*'],
        ],
        'POS' => [
            ['label' => 'Punto de venta', 'route' => 'pos.sale.create', 'active' => 'pos.sale.*'],
            ['label' => 'Terminales POS', 'route' => 'admin.pos.terminals.index', 'active' => 'admin.pos.terminals.*'],
            ['label' => 'Cotizaciones POS', 'route' => 'pos.quotes.index', 'active' => 'pos.quotes.*'],
            ['label' => 'Reservas POS', 'route' => 'pos.reservations.index', 'active' => 'pos.reservations.*'],
            ['label' => 'Ventas POS', 'route' => 'admin.orders.index', 'params' => ['channel' => 'pos'], 'active' => 'admin.orders.*'],
            ['label' => 'Cajas POS', 'route' => 'admin.cash-registers.index', 'active' => 'admin.cash-registers.*'],
            ['label' => 'Medios de pago POS', 'disabled' => true],
        ],
        'Inventario' => [
            ['label' => 'Bodegas', 'route' => 'admin.warehouses.index', 'active' => 'admin.warehouses.*', 'icon' => 'bi-house'],
            ['label' => 'Stock actual', 'route' => 'admin.stock.index', 'active' => 'admin.stock.*', 'icon' => 'bi-boxes'],
            ['label' => 'Kardex / Movimientos', 'route' => 'admin.stock-movements.index', 'active' => 'admin.stock-movements.*', 'icon' => 'bi-list'],
            ['label' => 'Ajustes', 'route' => 'admin.stock-adjustments.index', 'active' => 'admin.stock-adjustments.*', 'icon' => 'bi-plusminus'],
            ['label' => 'Transferencias', 'route' => 'admin.stock-transfers.index', 'active' => 'admin.stock-transfers.*', 'icon' => 'bi-arrow-left-right'],
            ['label' => 'Reservas', 'route' => 'admin.stock-reservations.index', 'active' => 'admin.stock-reservations.*', 'icon' => 'bi-bookmark'],
        ],
        'Reportes' => [
            ['label' => 'Dashboard Ejecutivo', 'route' => 'admin.reports.dashboard', 'active' => 'admin.reports.dashboard'],
            ['label' => 'Ventas', 'route' => 'admin.reports.sales', 'active' => 'admin.reports.sales'],
            ['label' => 'Productos', 'route' => 'admin.reports.products', 'active' => 'admin.reports.products'],
            ['label' => 'Inventario', 'route' => 'admin.reports.inventory', 'active' => 'admin.reports.inventory'],
            ['label' => 'Kardex', 'route' => 'admin.reports.kardex', 'active' => 'admin.reports.kardex'],
            ['label' => 'Clientes', 'route' => 'admin.reports.customers', 'active' => 'admin.reports.customers'],
            ['label' => 'POS', 'route' => 'admin.reports.pos', 'active' => 'admin.reports.pos'],
            ['label' => 'Caja', 'route' => 'admin.reports.cash', 'active' => 'admin.reports.cash'],
            ['label' => 'Envíos', 'route' => 'admin.reports.shipping', 'active' => 'admin.reports.shipping'],
            ['label' => 'Promociones', 'route' => 'admin.reports.promotions', 'active' => 'admin.reports.promotions'],
            ['label' => 'Integraciones', 'route' => 'admin.reports.integrations', 'active' => 'admin.reports.integrations'],
        ],
        'Integraciones' => [
            ['label' => 'Panel integraciones', 'route' => 'admin.integrations.index', 'active' => 'admin.integrations.*'],
            ['label' => 'Mapeo productos', 'route' => 'admin.external-product-mappings.index', 'active' => 'admin.external-product-mappings.*'],
            ['label' => 'Mapeo pedidos', 'route' => 'admin.external-order-mappings.index', 'active' => 'admin.external-order-mappings.*'],
            ['label' => 'Webhooks', 'route' => 'admin.webhook-events.index', 'active' => 'admin.webhook-events.*'],
            ['label' => 'Jobs', 'route' => 'admin.sync-jobs.index', 'active' => 'admin.sync-jobs.*'],
            ['label' => 'Logs', 'route' => 'admin.integration-logs.index', 'active' => 'admin.integration-logs.*'],
            ['label' => 'API Clients', 'route' => 'admin.api-clients.index', 'active' => 'admin.api-clients.*'],
        ],
        'Sistema' => [
            ['label' => 'Estado del sistema', 'route' => 'admin.system.health', 'active' => 'admin.system.health'],
            ['label' => 'Logs', 'route' => 'admin.system.logs', 'active' => 'admin.system.logs'],
            ['label' => 'Backups', 'route' => 'admin.system.backups.index', 'active' => 'admin.system.backups.*'],
            ['label' => 'Seguridad', 'route' => 'admin.security.index', 'active' => 'admin.security.*'],
            ['label' => 'Permisos', 'route' => 'admin.security.permissions', 'active' => 'admin.security.permissions'],
            ['label' => 'Empresas', 'route' => 'admin.companies.index', 'active' => 'admin.companies.*'],
            ['label' => 'Sucursales', 'route' => 'admin.branches.index', 'active' => 'admin.branches.*'],
            ['label' => 'Monedas', 'route' => 'admin.currencies.index', 'active' => 'admin.currencies.*'],
            ['label' => 'API Docs', 'route' => 'admin.api-docs.index', 'active' => 'admin.api-docs.*'],
        ],
    ];
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> | <?php echo e($setting->store_name); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar p-3">
            <div class="d-flex align-items-center gap-2 mb-4">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-brand d-flex align-items-center gap-2 text-white text-decoration-none flex-grow-1">
                    <span class="bg-white text-dark rounded px-2 py-1 fw-bold">QE</span>
                    <span class="admin-brand-text">
                        <span class="d-block fw-semibold"><?php echo e($setting->store_name); ?></span>
                        <span class="d-block small text-secondary">Panel administrativo</span>
                    </span>
                </a>
                <button type="button" class="btn btn-sm btn-outline-light admin-sidebar-toggle" aria-label="Expandir o retraer menú" data-sidebar-toggle>
                    <span class="admin-toggle-symbol">‹</span>
                </button>
            </div>

            <nav class="admin-nav accordion" id="adminSidebarNav">
                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $sectionId = 'sidebar-section-'.\Illuminate\Support\Str::slug($section);
                        $isOpen = collect($items)->contains(fn ($item) => isset($item['active']) && request()->routeIs($item['active']));
                    ?>
                    <div class="admin-nav-section">
                        <button class="admin-section-toggle <?php echo e($isOpen ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo e($sectionId); ?>" aria-expanded="<?php echo e($isOpen ? 'true' : 'false'); ?>">
                            <span class="admin-section-title"><?php echo e($section); ?></span>
                            <span class="admin-section-chevron">⌄</span>
                        </button>
                        <div id="<?php echo e($sectionId); ?>" class="collapse <?php echo e($isOpen ? 'show' : ''); ?>" data-bs-parent="#adminSidebarNav">
                            <div class="nav nav-pills flex-column gap-1 pb-2">
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php ($isActive = isset($item['active']) && request()->routeIs($item['active'])); ?>
                                    <?php if($item['disabled'] ?? false): ?>
                                        <span class="nav-link <?php echo e($isActive ? 'active' : 'disabled'); ?>">
                                            <?php if(isset($item['icon'])): ?><i class="bi <?php echo e($item['icon']); ?> me-2"></i><?php endif; ?>
                                            <span class="admin-link-text"><?php echo e($item['label']); ?></span>
                                        </span>
                                    <?php else: ?>
                                        <a class="nav-link <?php echo e($isActive ? 'active' : ''); ?>" href="<?php echo e(route($item['route'], $item['params'] ?? [])); ?>">
                                            <?php if(isset($item['icon'])): ?><i class="bi <?php echo e($item['icon']); ?> me-2"></i><?php endif; ?>
                                            <span class="admin-link-text"><?php echo e($item['label']); ?></span>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </aside>

        <div class="admin-content flex-grow-1 d-flex flex-column">
            <nav class="navbar navbar-expand bg-white border-bottom px-3">
                <div>
                    <div class="fw-semibold"><?php echo $__env->yieldContent('page-title', 'Panel administrativo'); ?></div>
                    <div class="small text-secondary"><?php echo e(now()->format('d/m/Y H:i')); ?></div>
                </div>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="badge text-bg-light"><?php echo e(auth()->user()->role?->name); ?></span>
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('profile.edit')); ?>">Perfil</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-dark btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>Salir
                        </button>
                    </form>
                </div>
            </nav>

            <main class="flex-grow-1 p-3 p-lg-4">
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <footer class="border-top bg-white px-4 py-3 text-secondary small">
                Ecommerce + POS. Base administrativa modular lista para nuevas fases.
            </footer>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Aplicaciones con c#\SitioWebQueeconomico\resources\views\layouts\admin.blade.php ENDPATH**/ ?>