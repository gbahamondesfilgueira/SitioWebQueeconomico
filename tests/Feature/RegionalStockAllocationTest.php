<?php

namespace Tests\Feature;

use App\Models\CartSession;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ShippingPackage;
use App\Models\StockLevel;
use App\Models\StockReservation;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ShippingService;
use App\Services\StockAllocationService;
use Database\Seeders\ShippingSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegionalStockAllocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_prefers_stock_from_the_customers_region_with_a_one_to_three_day_estimate(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 20);
        $regionalStock = $this->stock($regional, $regionalLocation, $product, 5);

        $allocation = app(StockAllocationService::class)->findProductAllocation($product->id, null, 2, 'Región del Biobío');

        $this->assertSame($regionalStock->id, $allocation['stock_level_id']);
        $this->assertSame('1 a 3 días hábiles', $allocation['estimate']['label']);
        $this->assertFalse($allocation['is_fallback']);
    }

    public function test_it_falls_back_to_the_central_warehouse_with_a_one_to_five_day_estimate(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $centralStock = $this->stock($central, $centralLocation, $product, 20);
        $this->stock($regional, $regionalLocation, $product, 1);

        $allocation = app(StockAllocationService::class)->findProductAllocation($product->id, null, 3, 'biobio');

        $this->assertSame($centralStock->id, $allocation['stock_level_id']);
        $this->assertSame('1 a 5 días hábiles', $allocation['estimate']['label']);
        $this->assertTrue($allocation['is_fallback']);
    }

    public function test_metropolitan_orders_use_the_central_warehouse_exclusively(): void
    {
        [$central, , $centralLocation] = $this->warehouses();
        $metropolitanBranch = Warehouse::factory()->create([
            'name' => 'Sucursal Metropolitana',
            'code' => 'RM-BRANCH',
            'type' => 'branch',
            'region' => 'Región Metropolitana',
            'fulfillment_priority' => 1,
        ]);
        $product = Product::factory()->create();
        $branchLocation = $this->location($metropolitanBranch, 'RM-BRANCH');
        $this->stock($metropolitanBranch, $branchLocation, $product, 20);
        $centralStock = $this->stock($central, $centralLocation, $product, 5);

        $allocation = app(StockAllocationService::class)->findProductAllocation($product->id, null, 2, 'metropolitana');

        $this->assertSame($centralStock->id, $allocation['stock_level_id']);
        $this->assertSame('1 a 3 días hábiles', $allocation['estimate']['label']);
    }

    public function test_it_does_not_sum_locations_when_no_single_location_can_cover_the_quantity(): void
    {
        [$central, $regional] = $this->warehouses();
        $product = Product::factory()->create();
        $locationOne = $this->location($regional, 'BIO-1');
        $locationTwo = $this->location($regional, 'BIO-2');
        $this->stock($regional, $locationOne, $product, 2);
        $this->stock($regional, $locationTwo, $product, 2);

        $allocation = app(StockAllocationService::class)->findProductAllocation($product->id, null, 3, 'biobio');

        $this->assertNull($allocation);
    }

    public function test_cart_reservation_uses_the_selected_regional_source(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 20);
        $regionalStock = $this->stock($regional, $regionalLocation, $product, 5);
        $cart = CartSession::query()->create([
            'session_id' => 'regional-test',
            'status' => 'active',
            'destination_region_code' => 'biobio',
            'expires_at' => now()->addMinutes(30),
        ]);

        $item = app(CartService::class)->addProduct($cart, $product->id, null, 2);

        $reservation = $item->reservation;
        $this->assertSame($regional->id, $reservation->warehouse_id);
        $this->assertSame($regionalLocation->id, $reservation->warehouse_location_id);
        $this->assertSame(2, $regionalStock->refresh()->reserved_stock);
    }

    public function test_changing_the_destination_region_reallocates_the_cart_atomically(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $centralStock = $this->stock($central, $centralLocation, $product, 20);
        $regionalStock = $this->stock($regional, $regionalLocation, $product, 5);
        $cart = CartSession::query()->create([
            'session_id' => 'reallocation-test',
            'status' => 'active',
            'destination_region_code' => 'metropolitana',
            'expires_at' => now()->addMinutes(30),
        ]);
        app(CartService::class)->addProduct($cart, $product->id, null, 2);

        app(CartService::class)->reallocateForRegion($cart, 'biobio');

        $activeReservation = $cart->items()->first()->reservation()->first();
        $this->assertSame($regional->id, $activeReservation->warehouse_id);
        $this->assertSame(0, $centralStock->refresh()->reserved_stock);
        $this->assertSame(2, $regionalStock->refresh()->reserved_stock);
    }

    public function test_non_sellable_locations_are_ignored(): void
    {
        [$central, $regional, $centralLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $centralStock = $this->stock($central, $centralLocation, $product, 5);
        $returns = $regional->locations()->create([
            'name' => 'Devoluciones',
            'code' => 'DEV',
            'is_active' => true,
            'is_sellable' => false,
        ]);
        $this->stock($regional, $returns, $product, 20);

        $allocation = app(StockAllocationService::class)->findProductAllocation($product->id, null, 1, 'biobio');

        $this->assertSame($centralStock->id, $allocation['stock_level_id']);
        $this->assertTrue($allocation['is_fallback']);
    }

    public function test_pack_falls_back_as_a_unit_instead_of_mixing_warehouses(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $first = Product::factory()->create();
        $second = Product::factory()->create();
        $pack = ProductPack::query()->create([
            'name' => 'Pack regional',
            'sku' => 'PACK-REGIONAL',
            'regular_price' => 20000,
            'pack_price' => 15000,
            'is_active' => true,
            'is_visible' => true,
        ]);
        $pack->items()->create(['product_id' => $first->id, 'quantity' => 1]);
        $pack->items()->create(['product_id' => $second->id, 'quantity' => 1]);
        $this->stock($regional, $regionalLocation, $first, 5);
        $this->stock($central, $centralLocation, $first, 5);
        $this->stock($central, $centralLocation, $second, 5);

        $allocations = app(StockAllocationService::class)->findPackAllocations($pack, 1, 'biobio');

        $this->assertCount(2, $allocations);
        $this->assertSame([$central->id], collect($allocations)->pluck('warehouse_id')->unique()->values()->all());
    }

    public function test_order_and_cancellation_keep_the_original_warehouse_and_location(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 20);
        $regionalStock = $this->stock($regional, $regionalLocation, $product, 5);
        $cart = CartSession::query()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'destination_region_code' => 'biobio',
            'expires_at' => now()->addMinutes(30),
        ]);
        app(CartService::class)->addProduct($cart, $product->id, null, 2);
        foreach (['shipping', 'billing'] as $type) {
            $cart->addresses()->create([
                'address_type' => $type,
                'contact_name' => 'Cliente Regional',
                'phone' => '999999999',
                'country' => 'Chile',
                'region' => 'Región del Biobío',
                'commune' => 'Concepción',
                'city' => 'Concepción',
                'street' => 'Calle Uno',
                'number' => '123',
            ]);
        }
        $cart->paymentMethod()->create(['payment_method' => 'bank_transfer', 'payment_label' => 'Transferencia', 'status' => 'selected']);
        $cart->shippingMethod()->create([
            'service_name' => 'Envío estándar',
            'shipping_type' => 'delivery',
            'estimated_price' => 3990,
            'estimated_days' => 3,
            'estimated_days_min' => 1,
            'estimated_days_max' => 3,
            'status' => 'selected',
        ]);

        $order = app(OrderService::class)->createOrderFromCart($cart, $user);
        $fulfillment = $order->fulfillments()->with('items')->first();

        $this->assertSame($regional->id, $fulfillment->warehouse_id);
        $this->assertSame($regionalLocation->id, $fulfillment->items->first()->warehouse_location_id);
        $this->assertSame(3, $regionalStock->refresh()->physical_stock);
        $this->assertSame(1, $order->shipment->estimated_days_min);
        $this->assertSame(3, $order->shipment->estimated_days_max);

        app(OrderService::class)->cancelOrder($order, 'Prueba de reposición', true);

        $this->assertSame(5, $regionalStock->refresh()->physical_stock);
    }

    public function test_shipping_quotes_sum_each_origin_warehouse_without_offering_multi_origin_pickup(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $this->seed(ShippingSeeder::class);
        $localProduct = Product::factory()->create(['weight' => 1]);
        $centralProduct = Product::factory()->create(['weight' => 1]);
        $this->stock($regional, $regionalLocation, $localProduct, 5);
        $this->stock($central, $centralLocation, $centralProduct, 5);
        $cart = CartSession::query()->create([
            'session_id' => 'multi-origin-shipping',
            'status' => 'active',
            'destination_region_code' => 'biobio',
            'expires_at' => now()->addMinutes(30),
        ]);
        app(CartService::class)->addProduct($cart, $localProduct->id);
        app(CartService::class)->addProduct($cart, $centralProduct->id);

        $quotes = app(ShippingService::class)->quoteCartShipping($cart, [
            'country' => 'Chile',
            'region' => 'Región del Biobío',
            'commune' => 'Concepción',
            'city' => 'Concepción',
        ]);

        $this->assertCount(1, $quotes);
        $this->assertSame(9980.0, (float) $quotes->first()->price);
        $this->assertTrue($quotes->first()->metadata['multi_origin']);
        $this->assertCount(2, $quotes->first()->metadata['origins']);
        $this->assertSame(2, ShippingPackage::query()->where('cart_session_id', $cart->id)->value('package_count'));
        $this->assertNotSame('pickup', $quotes->first()->service?->service_type);
    }

    public function test_stock_source_dimension_is_unique(): void
    {
        [$central, , $centralLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 5);

        $this->expectException(QueryException::class);
        $this->stock($central, $centralLocation, $product, 3);
    }

    public function test_a_second_cart_cannot_reserve_units_already_reserved_by_the_first_cart(): void
    {
        [, $regional, , $regionalLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $stock = $this->stock($regional, $regionalLocation, $product, 1);
        $firstCart = CartSession::query()->create(['session_id' => 'first-cart', 'status' => 'active', 'destination_region_code' => 'biobio']);
        $secondCart = CartSession::query()->create(['session_id' => 'second-cart', 'status' => 'active', 'destination_region_code' => 'biobio']);

        app(CartService::class)->addProduct($firstCart, $product->id);

        try {
            app(CartService::class)->addProduct($secondCart, $product->id);
            $this->fail('La segunda reserva debía ser rechazada.');
        } catch (ValidationException) {
            $this->assertSame(1, $stock->refresh()->reserved_stock);
            $this->assertSame(1, StockReservation::query()->where('status', 'active')->count());
        }
    }

    private function warehouses(): array
    {
        $central = Warehouse::factory()->create([
            'name' => 'Bodega Central',
            'code' => 'ECOM',
            'type' => 'ecommerce',
            'region' => 'Región Metropolitana',
            'is_central' => true,
            'fulfillment_priority' => 1,
        ]);
        $regional = Warehouse::factory()->create([
            'name' => 'Bodega Biobío',
            'code' => 'BIOBIO',
            'type' => 'branch',
            'region' => 'Región del Biobío',
            'fulfillment_priority' => 10,
        ]);

        return [$central, $regional, $this->location($central, 'CENTRAL'), $this->location($regional, 'BIOBIO')];
    }

    private function location(Warehouse $warehouse, string $code): WarehouseLocation
    {
        return $warehouse->locations()->create([
            'name' => $code,
            'code' => $code,
            'is_active' => true,
            'is_sellable' => true,
        ]);
    }

    private function stock(Warehouse $warehouse, WarehouseLocation $location, Product $product, int $quantity): StockLevel
    {
        return StockLevel::query()->create([
            'warehouse_id' => $warehouse->id,
            'warehouse_location_id' => $location->id,
            'product_id' => $product->id,
            'physical_stock' => $quantity,
            'reserved_stock' => 0,
            'minimum_stock' => 0,
        ]);
    }
}
