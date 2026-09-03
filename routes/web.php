<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Admin\ApiClientController;
use App\Http\Controllers\Admin\ApiDocumentationController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CashRegisterAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\CustomerCompanyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerDocumentController;
use App\Http\Controllers\Admin\CustomerNoteController;
use App\Http\Controllers\Admin\CustomerRewardController;
use App\Http\Controllers\Admin\CustomerTagController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExternalOrderMappingController;
use App\Http\Controllers\Admin\ExternalProductMappingController;
use App\Http\Controllers\Admin\IntegrationController;
use App\Http\Controllers\Admin\IntegrationLogController;
use App\Http\Controllers\Admin\MeasurementUnitController;
use App\Http\Controllers\Admin\OrderCancellationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderFulfillmentController;
use App\Http\Controllers\Admin\OrderReturnController;
use App\Http\Controllers\Admin\OriginCountryController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PosTerminalController;
use App\Http\Controllers\Admin\PriceListController;
use App\Http\Controllers\Admin\PricingCalculatorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImportController;
use App\Http\Controllers\Admin\ProductPackController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\QuantityDiscountController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingCarrierController;
use App\Http\Controllers\Admin\ShippingIntegrationController;
use App\Http\Controllers\Admin\ShippingLabelController;
use App\Http\Controllers\Admin\ShippingQuoteController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\ShippingRateImportController;
use App\Http\Controllers\Admin\ShippingServiceController;
use App\Http\Controllers\Admin\ShippingTrackingController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockLevelController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\StockReservationController;
use App\Http\Controllers\Admin\StockTransferController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SyncJobController;
use App\Http\Controllers\Admin\SystemBackupController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\SystemLogController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\WarehouseLocationController;
use App\Http\Controllers\Admin\WebhookEventController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Pos\BarcodeController as PosBarcodeController;
use App\Http\Controllers\Pos\CartController as PosCartController;
use App\Http\Controllers\Pos\CashMovementController as PosCashMovementController;
use App\Http\Controllers\Pos\CashRegisterController as PosCashRegisterController;
use App\Http\Controllers\Pos\CashReportController as PosCashReportController;
use App\Http\Controllers\Pos\CouponController as PosCouponController;
use App\Http\Controllers\Pos\CustomerController as PosCustomerController;
use App\Http\Controllers\Pos\DashboardController as PosDashboardController;
use App\Http\Controllers\Pos\DiscountController as PosDiscountController;
use App\Http\Controllers\Pos\PaymentController as PosPaymentController;
use App\Http\Controllers\Pos\PosCancellationController;
use App\Http\Controllers\Pos\PosRefundController;
use App\Http\Controllers\Pos\QuoteController as PosQuoteController;
use App\Http\Controllers\Pos\ReceiptController as PosReceiptController;
use App\Http\Controllers\Pos\ReservationController as PosReservationController;
use App\Http\Controllers\Pos\SaleController as PosSaleController;
use App\Http\Controllers\Pos\SearchController as PosSearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Store\CartController as StoreCartController;
use App\Http\Controllers\Store\CategoryController as StoreCategoryController;
use App\Http\Controllers\Store\CheckoutController as StoreCheckoutController;
use App\Http\Controllers\Store\CouponController as StoreCouponController;
use App\Http\Controllers\Store\HomeController as StoreHomeController;
use App\Http\Controllers\Store\LocalStockRegionController;
use App\Http\Controllers\Store\OrderController as StoreOrderController;
use App\Http\Controllers\Store\PackController as StorePackController;
use App\Http\Controllers\Store\PageController as StorePageController;
use App\Http\Controllers\Store\ProductController as StoreProductController;
use App\Http\Controllers\Store\SearchController as StoreSearchController;
use App\Http\Controllers\Store\ShopController;
use App\Http\Controllers\Webhook\ProviderWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', StoreHomeController::class)->name('store.home');
Route::post('/newsletter', [StoreHomeController::class, 'newsletter'])->name('store.newsletter');
Route::post('/webhooks/{provider}/{event?}', ProviderWebhookController::class)->middleware('throttle:60,1')->name('webhooks.provider');
Route::get('/tienda', ShopController::class)->name('store.shop');
Route::get('/producto/{slug}', [StoreProductController::class, 'show'])->name('store.products.show');
Route::get('/categoria/{slug}', [StoreCategoryController::class, 'show'])->name('store.categories.show');
Route::get('/categoria/{parent}/{child}', [StoreCategoryController::class, 'show'])->name('store.categories.child');
Route::get('/packs', [StorePackController::class, 'index'])->name('store.packs.index');
Route::get('/pack/{slug}', [StorePackController::class, 'show'])->name('store.packs.show');
Route::get('/buscar', StoreSearchController::class)->name('store.search');
Route::post('/ubicacion/region-stock', [LocalStockRegionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('store.location.stock-region');
Route::middleware(['auth', 'customer.region'])->group(function () {
    Route::get('/carrito', [StoreCartController::class, 'index'])->name('store.cart.index');
    Route::post('/carrito/agregar', [StoreCartController::class, 'add'])->name('store.cart.add');
    Route::post('/carrito/actualizar', [StoreCartController::class, 'update'])->name('store.cart.update');
    Route::post('/carrito/eliminar', [StoreCartController::class, 'remove'])->name('store.cart.remove');
    Route::post('/carrito/vaciar', [StoreCartController::class, 'clear'])->name('store.cart.clear');
    Route::post('/carrito/cupon/aplicar', [StoreCouponController::class, 'apply'])->name('store.cart.coupon.apply');
    Route::post('/carrito/cupon/quitar', [StoreCouponController::class, 'remove'])->name('store.cart.coupon.remove');
    Route::get('/checkout', [StoreCheckoutController::class, 'index'])->name('store.checkout.index');
    Route::post('/checkout/customer', [StoreCheckoutController::class, 'customer'])->name('store.checkout.customer');
    Route::match(['get', 'post'], '/checkout/shipping-address', [StoreCheckoutController::class, 'shippingAddress'])->name('store.checkout.shipping-address');
    Route::match(['get', 'post'], '/checkout/billing-address', [StoreCheckoutController::class, 'billingAddress'])->name('store.checkout.billing-address');
    Route::match(['get', 'post'], '/checkout/shipping-method', [StoreCheckoutController::class, 'shippingMethod'])->name('store.checkout.shipping-method');
    Route::match(['get', 'post'], '/checkout/payment-method', [StoreCheckoutController::class, 'paymentMethod'])->name('store.checkout.payment-method');
    Route::get('/checkout/review', [StoreCheckoutController::class, 'review'])->name('store.checkout.review');
    Route::post('/checkout/confirm', [StoreOrderController::class, 'confirm'])->name('store.checkout.confirm');
});
Route::get('/pedido/confirmado/{order_number}', [StoreOrderController::class, 'confirmed'])->middleware('auth')->name('store.orders.confirmed');
Route::get('/{page}', [StorePageController::class, 'show'])
    ->whereIn('page', ['sobre-nosotros', 'contacto', 'politicas-de-envio', 'politicas-de-devolucion', 'terminos-y-condiciones', 'politica-de-privacidad'])
    ->name('store.pages.show');

Route::get('/dashboard', DashboardRedirectController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super-admin,administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('users', UserController::class)->except(['show', 'destroy']);
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::patch('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');

        Route::resource('brands', BrandController::class)->except(['show']);
        Route::patch('/brands/{brand}/toggle-active', [BrandController::class, 'toggleActive'])->name('brands.toggle-active');

        Route::resource('suppliers', SupplierController::class)->except(['show', 'destroy']);
        Route::patch('/suppliers/{supplier}/toggle-active', [SupplierController::class, 'toggleActive'])->name('suppliers.toggle-active');

        Route::resource('tags', TagController::class)->except(['show']);
        Route::patch('/tags/{tag}/toggle-active', [TagController::class, 'toggleActive'])->name('tags.toggle-active');

        Route::resource('measurement-units', MeasurementUnitController::class)->except(['show']);

        Route::resource('taxes', TaxController::class)->except(['show']);
        Route::patch('/taxes/{tax}/toggle-active', [TaxController::class, 'toggleActive'])->name('taxes.toggle-active');

        Route::resource('origin-countries', OriginCountryController::class)->except(['show']);
        Route::patch('/origin-countries/{origin_country}/toggle-active', [OriginCountryController::class, 'toggleActive'])->name('origin-countries.toggle-active');

        Route::resource('products', ProductController::class);
        Route::patch('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
        Route::delete('/products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.destroy');
        Route::patch('/products/{product}/images/{image}/primary', [ProductController::class, 'makePrimaryImage'])->name('products.images.primary');
        Route::prefix('imports/products')->name('imports.products.')->group(function () {
            Route::get('/', [ProductImportController::class, 'index'])->name('index');
            Route::get('/create', [ProductImportController::class, 'create'])->name('create');
            Route::post('/', [ProductImportController::class, 'store'])->name('store');
            Route::get('/{import}', [ProductImportController::class, 'show'])->name('show');
        });

        Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants.index');
        Route::get('/products/{product}/variants/create', [ProductVariantController::class, 'create'])->name('products.variants.create');
        Route::post('/products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');
        Route::get('/products/{product}/variants/{variant}/edit', [ProductVariantController::class, 'edit'])->name('products.variants.edit');
        Route::put('/products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])->name('products.variants.update');
        Route::patch('/products/{product}/variants/{variant}/toggle-active', [ProductVariantController::class, 'toggleActive'])->name('products.variants.toggle-active');
        Route::delete('/products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('products.variants.destroy');

        Route::resource('attributes', AttributeController::class)->except(['destroy']);
        Route::patch('/attributes/{attribute}/toggle-active', [AttributeController::class, 'toggleActive'])->name('attributes.toggle-active');
        Route::post('/attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
        Route::get('/attributes/{attribute}/values/{value}/edit', [AttributeController::class, 'editValue'])->name('attributes.values.edit');
        Route::put('/attributes/{attribute}/values/{value}', [AttributeController::class, 'updateValue'])->name('attributes.values.update');
        Route::patch('/attributes/{attribute}/values/{value}/toggle-active', [AttributeController::class, 'toggleValue'])->name('attributes.values.toggle-active');

        Route::resource('price-lists', PriceListController::class);
        Route::patch('/price-lists/{price_list}/toggle-active', [PriceListController::class, 'toggleActive'])->name('price-lists.toggle-active');
        Route::resource('promotions', PromotionController::class);
        Route::patch('/promotions/{promotion}/toggle-active', [PromotionController::class, 'toggleActive'])->name('promotions.toggle-active');
        Route::resource('product-packs', ProductPackController::class);
        Route::patch('/product-packs/{product_pack}/toggle-active', [ProductPackController::class, 'toggleActive'])->name('product-packs.toggle-active');
        Route::resource('coupons', CouponController::class);
        Route::patch('/coupons/{coupon}/toggle-active', [CouponController::class, 'toggleActive'])->name('coupons.toggle-active');
        Route::resource('quantity-discounts', QuantityDiscountController::class);
        Route::patch('/quantity-discounts/{quantity_discount}/toggle-active', [QuantityDiscountController::class, 'toggleActive'])->name('quantity-discounts.toggle-active');
        Route::match(['get', 'post'], '/pricing/calculator', PricingCalculatorController::class)->name('pricing.calculator');

        Route::resource('customers', CustomerController::class)->except(['destroy']);
        Route::patch('/customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggle-active');
        Route::get('/customers/{customer}/addresses/create', [CustomerAddressController::class, 'create'])->name('customers.addresses.create');
        Route::post('/customers/{customer}/addresses', [CustomerAddressController::class, 'store'])->name('customers.addresses.store');
        Route::get('/customers/{customer}/addresses/{address}/edit', [CustomerAddressController::class, 'edit'])->name('customers.addresses.edit');
        Route::put('/customers/{customer}/addresses/{address}', [CustomerAddressController::class, 'update'])->name('customers.addresses.update');
        Route::get('/customers/{customer}/companies/create', [CustomerCompanyController::class, 'create'])->name('customers.companies.create');
        Route::post('/customers/{customer}/companies', [CustomerCompanyController::class, 'store'])->name('customers.companies.store');
        Route::get('/customers/{customer}/companies/{company}/edit', [CustomerCompanyController::class, 'edit'])->name('customers.companies.edit');
        Route::put('/customers/{customer}/companies/{company}', [CustomerCompanyController::class, 'update'])->name('customers.companies.update');
        Route::post('/customers/{customer}/documents', [CustomerDocumentController::class, 'store'])->name('customers.documents.store');
        Route::delete('/customers/{customer}/documents/{document}', [CustomerDocumentController::class, 'destroy'])->name('customers.documents.destroy');
        Route::post('/customers/{customer}/rewards', [CustomerRewardController::class, 'store'])->name('customers.rewards.store');
        Route::post('/customers/{customer}/notes', [CustomerNoteController::class, 'store'])->name('customers.notes.store');
        Route::resource('customer-tags', CustomerTagController::class)->only(['index', 'store', 'update']);

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'status'])->name('orders.status');
        Route::patch('/orders/{order}/payment-status', [OrderController::class, 'paymentStatus'])->name('orders.payment-status');
        Route::get('/orders/{order}/fulfillment', [OrderFulfillmentController::class, 'show'])->name('orders.fulfillment');
        Route::patch('/orders/{order}/fulfillment', [OrderFulfillmentController::class, 'update'])->name('orders.fulfillment.update');
        Route::get('/orders/{order}/cancel', [OrderCancellationController::class, 'create'])->name('orders.cancel');
        Route::post('/orders/{order}/cancel', [OrderCancellationController::class, 'store'])->name('orders.cancel.store');
        Route::get('/order-returns', [OrderReturnController::class, 'index'])->name('order-returns.index');
        Route::get('/order-returns/{return}', [OrderReturnController::class, 'show'])->name('order-returns.show');
        Route::patch('/order-returns/{return}/approve', [OrderReturnController::class, 'approve'])->name('order-returns.approve');
        Route::patch('/order-returns/{return}/receive', [OrderReturnController::class, 'receive'])->name('order-returns.receive');

        Route::prefix('shipping')->name('shipping.')->group(function () {
            Route::resource('carriers', ShippingCarrierController::class)->except(['show', 'destroy']);
            Route::patch('/carriers/{carrier}/toggle', [ShippingCarrierController::class, 'toggle'])->name('carriers.toggle');
            Route::resource('services', ShippingServiceController::class)->except(['show', 'destroy']);
            Route::patch('/services/{service}/toggle', [ShippingServiceController::class, 'toggle'])->name('services.toggle');
            Route::resource('zones', ShippingZoneController::class)->except(['show', 'destroy']);
            Route::patch('/zones/{zone}/toggle', [ShippingZoneController::class, 'toggle'])->name('zones.toggle');
            Route::resource('rates', ShippingRateController::class)->except(['show', 'destroy']);
            Route::patch('/rates/{rate}/toggle', [ShippingRateController::class, 'toggle'])->name('rates.toggle');
            Route::resource('imports', ShippingRateImportController::class)->only(['index', 'create', 'store', 'show']);
            Route::get('/imports/{import}/errors', [ShippingRateImportController::class, 'downloadErrors'])->name('imports.errors');
            Route::match(['get', 'post'], '/quote-calculator', ShippingQuoteController::class)->name('quote-calculator');
            Route::get('/labels', [ShippingLabelController::class, 'index'])->name('labels.index');
            Route::post('/labels/orders/{order}', [ShippingLabelController::class, 'generate'])->name('labels.generate');
            Route::get('/labels/{label}', [ShippingLabelController::class, 'show'])->name('labels.show');
            Route::get('/labels/{label}/print', [ShippingLabelController::class, 'print'])->name('labels.print');
            Route::get('/tracking', [ShippingTrackingController::class, 'index'])->name('tracking.index');
            Route::post('/tracking', [ShippingTrackingController::class, 'store'])->name('tracking.store');
            Route::get('/integrations', [ShippingIntegrationController::class, 'index'])->name('integrations.index');
            Route::post('/integrations', [ShippingIntegrationController::class, 'store'])->name('integrations.store');
        });

        Route::prefix('pos')->name('pos.')->group(function () {
            Route::resource('terminals', PosTerminalController::class)->except(['show', 'destroy']);
            Route::patch('/terminals/{terminal}/toggle', [PosTerminalController::class, 'toggle'])->name('terminals.toggle');
        });

        Route::get('/cash-registers', [CashRegisterAdminController::class, 'index'])->name('cash-registers.index');
        Route::get('/cash-registers/{session}', [CashRegisterAdminController::class, 'show'])->name('cash-registers.show');

        Route::resource('integrations', IntegrationController::class)->except(['destroy']);
        Route::post('/integrations/{integration}/test', [IntegrationController::class, 'test'])->name('integrations.test');
        Route::post('/integrations/{integration}/sync-stock', [IntegrationController::class, 'syncStock'])->name('integrations.sync-stock');
        Route::post('/integrations/{integration}/sync-prices', [IntegrationController::class, 'syncPrices'])->name('integrations.sync-prices');
        Route::post('/integrations/{integration}/import-orders', [IntegrationController::class, 'importOrders'])->name('integrations.import-orders');
        Route::get('/integration-logs', [IntegrationLogController::class, 'index'])->name('integration-logs.index');
        Route::get('/integration-logs/{integrationLog}', [IntegrationLogController::class, 'show'])->name('integration-logs.show');
        Route::resource('external-product-mappings', ExternalProductMappingController::class)->except(['show', 'destroy']);
        Route::get('/external-order-mappings', [ExternalOrderMappingController::class, 'index'])->name('external-order-mappings.index');
        Route::get('/external-order-mappings/{externalOrderMapping}', [ExternalOrderMappingController::class, 'show'])->name('external-order-mappings.show');
        Route::get('/webhook-events', [WebhookEventController::class, 'index'])->name('webhook-events.index');
        Route::get('/webhook-events/{webhookEvent}', [WebhookEventController::class, 'show'])->name('webhook-events.show');
        Route::get('/sync-jobs', [SyncJobController::class, 'index'])->name('sync-jobs.index');
        Route::get('/sync-jobs/{syncJob}', [SyncJobController::class, 'show'])->name('sync-jobs.show');
        Route::resource('api-clients', ApiClientController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/api-clients/{apiClient}/revoke', [ApiClientController::class, 'revoke'])->name('api-clients.revoke');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/products', [ReportController::class, 'products'])->name('products');
            Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
            Route::get('/cash', [ReportController::class, 'cash'])->name('cash');
            Route::get('/shipping', [ReportController::class, 'shipping'])->name('shipping');
            Route::get('/promotions', [ReportController::class, 'promotions'])->name('promotions');
            Route::get('/integrations', [ReportController::class, 'integrations'])->name('integrations');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
        });

        Route::prefix('system')->name('system.')->group(function () {
            Route::get('/health', SystemHealthController::class)->name('health');
            Route::get('/logs', SystemLogController::class)->name('logs');
            Route::resource('backups', SystemBackupController::class)->only(['index', 'store', 'show']);
        });

        Route::get('/security', [SecurityController::class, 'index'])->name('security.index');
        Route::get('/security/users', [SecurityController::class, 'users'])->name('security.users');
        Route::get('/security/permissions', [PermissionController::class, 'index'])->name('security.permissions');
        Route::post('/security/permissions', [PermissionController::class, 'store'])->name('security.permissions.store');
        Route::put('/security/roles/{role}/permissions', [PermissionController::class, 'syncRole'])->name('security.roles.permissions');
        Route::resource('companies', CompanyController::class)->except(['show', 'destroy']);
        Route::resource('branches', BranchController::class)->except(['show', 'destroy']);
        Route::resource('currencies', CurrencyController::class)->except(['show', 'destroy']);
        Route::get('/api-docs', ApiDocumentationController::class)->name('api-docs.index');
    });

Route::middleware(['auth', 'role:super-admin,administrador,vendedor-pos'])
    ->prefix('admin/reports')
    ->name('admin.reports.')
    ->group(function () {
        Route::get('/pos', [ReportController::class, 'pos'])->name('pos');
    });

Route::middleware(['auth', 'role:super-admin,administrador,vendedor-pos'])
    ->prefix('pos')
    ->name('pos.')
    ->group(function () {
        Route::get('/', PosDashboardController::class)->name('dashboard');
        Route::get('/sale', [PosSaleController::class, 'create'])->name('sale.create');
        Route::get('/cash', [PosCashRegisterController::class, 'current'])->name('cash.current');
        Route::get('/cash/open', [PosCashRegisterController::class, 'open'])->name('cash.open');
        Route::post('/cash/open', [PosCashRegisterController::class, 'storeOpen'])->name('cash.open.store');
        Route::get('/cash/close', [PosCashRegisterController::class, 'close'])->name('cash.close');
        Route::post('/cash/close', [PosCashRegisterController::class, 'storeClose'])->name('cash.close.store');
        Route::get('/cash/income', [PosCashMovementController::class, 'income'])->name('cash.income');
        Route::post('/cash/income', [PosCashMovementController::class, 'storeIncome'])->name('cash.income.store');
        Route::get('/cash/expense', [PosCashMovementController::class, 'expense'])->name('cash.expense');
        Route::post('/cash/expense', [PosCashMovementController::class, 'storeExpense'])->name('cash.expense.store');
        Route::get('/cash/movements', [PosCashMovementController::class, 'movements'])->name('cash.movements');
        Route::get('/cash/report', PosCashReportController::class)->name('cash.report');
        Route::get('/search', PosSearchController::class)->name('search');
        Route::post('/barcode/add', [PosBarcodeController::class, 'add'])->name('barcode.add');
        Route::post('/cart/add', [PosCartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [PosCartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove', [PosCartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [PosCartController::class, 'clear'])->name('cart.clear');
        Route::get('/customer/search', [PosCustomerController::class, 'search'])->name('customer.search');
        Route::post('/customer/quick-create', [PosCustomerController::class, 'quickCreate'])->name('customer.quick-create');
        Route::post('/coupon/apply', [PosCouponController::class, 'apply'])->name('coupon.apply');
        Route::post('/coupon/remove', [PosCouponController::class, 'remove'])->name('coupon.remove');
        Route::post('/payment/add', [PosPaymentController::class, 'add'])->name('payment.add');
        Route::post('/payment/remove', [PosPaymentController::class, 'remove'])->name('payment.remove');
        Route::post('/discount/request', [PosDiscountController::class, 'request'])->name('discount.request');
        Route::patch('/discount/{discount}/approve', [PosDiscountController::class, 'approve'])->name('discount.approve');
        Route::patch('/discount/{discount}/reject', [PosDiscountController::class, 'reject'])->name('discount.reject');
        Route::post('/confirm-sale', [PosSaleController::class, 'confirm'])->name('confirm-sale');
        Route::get('/quotes', [PosQuoteController::class, 'index'])->name('quotes.index');
        Route::post('/quotes/create-from-cart', [PosQuoteController::class, 'createFromCart'])->name('quotes.create-from-cart');
        Route::get('/quotes/{quote}', [PosQuoteController::class, 'show'])->name('quotes.show');
        Route::post('/quotes/{quote}/convert', [PosQuoteController::class, 'convert'])->name('quotes.convert');
        Route::get('/reservations', [PosReservationController::class, 'index'])->name('reservations.index');
        Route::post('/reservations/create-from-cart', [PosReservationController::class, 'createFromCart'])->name('reservations.create-from-cart');
        Route::get('/reservations/{reservation}', [PosReservationController::class, 'show'])->name('reservations.show');
        Route::post('/reservations/{reservation}/convert', [PosReservationController::class, 'convert'])->name('reservations.convert');
        Route::get('/orders/{order}/receipt', [PosReceiptController::class, 'show'])->name('orders.receipt');
        Route::get('/sales/{order}/cancel', [PosCancellationController::class, 'create'])->name('sales.cancel');
        Route::post('/sales/{order}/cancel', [PosCancellationController::class, 'store'])->name('sales.cancel.store');
        Route::get('/cancellations/{cancellation}', [PosCancellationController::class, 'show'])->name('cancellations.show');
        Route::get('/sales/{order}/refund', [PosRefundController::class, 'create'])->name('sales.refund');
        Route::post('/sales/{order}/refund', [PosRefundController::class, 'store'])->name('sales.refund.store');
        Route::get('/refunds/{refund}', [PosRefundController::class, 'show'])->name('refunds.show');
    });

Route::middleware(['auth'])
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
        Route::get('/companies', [AccountController::class, 'companies'])->name('companies');
        Route::post('/companies', [AccountController::class, 'storeCompany'])->name('companies.store');
        Route::get('/favorites', [AccountController::class, 'favorites'])->name('favorites');
        Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
        Route::get('/documents', [AccountController::class, 'documents'])->name('documents');
        Route::get('/points', [AccountController::class, 'rewards'])->name('rewards');
        Route::get('/coupons', [AccountController::class, 'coupons'])->name('coupons');
        Route::get('/security', [AccountController::class, 'security'])->name('security');
        Route::put('/security/password', [AccountController::class, 'updatePassword'])->name('security.password');
    });

Route::middleware(['auth'])
    ->prefix('mi-cuenta')
    ->name('store.account.')
    ->group(function () {
        Route::get('/pedidos', [StoreOrderController::class, 'accountIndex'])->name('orders.index');
        Route::get('/pedidos/{order_number}', [StoreOrderController::class, 'accountShow'])->name('orders.show');
    });

Route::middleware(['auth', 'role:super-admin,administrador,bodeguero'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('warehouses', WarehouseController::class);
        Route::patch('/warehouses/{warehouse}/toggle-active', [WarehouseController::class, 'toggleActive'])->name('warehouses.toggle-active');
        Route::get('/warehouses/{warehouse}/locations', [WarehouseLocationController::class, 'index'])->name('warehouses.locations.index');
        Route::get('/warehouses/{warehouse}/locations/create', [WarehouseLocationController::class, 'create'])->name('warehouses.locations.create');
        Route::post('/warehouses/{warehouse}/locations', [WarehouseLocationController::class, 'store'])->name('warehouses.locations.store');
        Route::get('/warehouses/{warehouse}/locations/{location}/edit', [WarehouseLocationController::class, 'edit'])->name('warehouses.locations.edit');
        Route::put('/warehouses/{warehouse}/locations/{location}', [WarehouseLocationController::class, 'update'])->name('warehouses.locations.update');

        Route::get('/stock', [StockLevelController::class, 'index'])->name('stock.index');
        Route::get('/stock/{stock}', [StockLevelController::class, 'show'])->name('stock.show');
        Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');

        Route::resource('stock-adjustments', StockAdjustmentController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/stock-adjustments/{stock_adjustment}/approve', [StockAdjustmentController::class, 'approve'])->name('stock-adjustments.approve');
        Route::patch('/stock-adjustments/{stock_adjustment}/reject', [StockAdjustmentController::class, 'reject'])->name('stock-adjustments.reject');

        Route::resource('stock-transfers', StockTransferController::class)->only(['index', 'create', 'store', 'show']);
        Route::patch('/stock-transfers/{stock_transfer}/send', [StockTransferController::class, 'send'])->name('stock-transfers.send');
        Route::patch('/stock-transfers/{stock_transfer}/receive', [StockTransferController::class, 'receive'])->name('stock-transfers.receive');

        Route::get('/stock-reservations', [StockReservationController::class, 'index'])->name('stock-reservations.index');
        Route::patch('/stock-reservations/{stock_reservation}/release', [StockReservationController::class, 'release'])->name('stock-reservations.release');
        Route::patch('/stock-reservations/{stock_reservation}/consume', [StockReservationController::class, 'consume'])->name('stock-reservations.consume');

        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/kardex', [ReportController::class, 'kardex'])->name('reports.kardex');
    });

require __DIR__.'/auth.php';
