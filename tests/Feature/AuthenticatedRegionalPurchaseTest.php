<?php

namespace Tests\Feature;

use App\Models\CartSession;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\CartService;
use App\Services\DeliveryRegionService;
use App\Services\ProductDisplayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedRegionalPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_browse_but_cannot_open_or_modify_a_cart(): void
    {
        $product = Product::factory()->create();

        $this->get(route('store.products.show', $product->slug))
            ->assertOk()
            ->assertSee('Inicia sesión para consultar stock')
            ->assertSee('Iniciar sesión para comprar')
            ->assertDontSee('Inicia sesión para comprar');

        $this->get(route('store.cart.index'))->assertRedirect(route('login'));
        $this->postJson(route('store.cart.add'), [
            'item_type' => 'product',
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertUnauthorized();
    }

    public function test_an_authenticated_user_without_a_delivery_region_cannot_buy(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->postJson(route('store.cart.add'), [
            'item_type' => 'product',
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertUnprocessable()
            ->assertJsonPath('redirect', route('account.addresses'));
    }

    public function test_the_default_account_address_is_the_only_source_for_regional_stock(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $user = $this->customerWithShippingRegion('Región del Biobío');
        $product = Product::factory()->create();
        $centralStock = $this->stock($central, $centralLocation, $product, 10);
        $regionalStock = $this->stock($regional, $regionalLocation, $product, 3);

        $response = $this->actingAs($user)
            ->withSession(['store.destination_region' => 'metropolitana'])
            ->postJson(route('store.cart.add'), [
                'item_type' => 'product',
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response->assertOk();
        $cart = CartSession::query()->where('user_id', $user->id)->firstOrFail();
        $this->assertSame('biobio', app(DeliveryRegionService::class)->currentRegion($user));
        $this->assertSame('biobio', $cart->destination_region_code);
        $this->assertSame(2, $regionalStock->refresh()->reserved_stock);
        $this->assertSame(0, $centralStock->refresh()->reserved_stock);
    }

    public function test_an_existing_cart_is_reallocated_when_the_default_account_region_changes(): void
    {
        [$central, $regional, $centralLocation, $regionalLocation] = $this->warehouses();
        $user = $this->customerWithShippingRegion('Región del Biobío');
        $product = Product::factory()->create();
        $centralStock = $this->stock($central, $centralLocation, $product, 5);
        $regionalStock = $this->stock($regional, $regionalLocation, $product, 5);
        $cart = CartSession::query()->create([
            'user_id' => $user->id,
            'status' => 'active',
            'destination_region_code' => 'metropolitana',
            'expires_at' => now()->addMinutes(30),
        ]);
        app(CartService::class)->addProduct($cart, $product->id);
        $this->assertSame(1, $centralStock->refresh()->reserved_stock);

        $synced = app(CartService::class)->getOrCreateCart($user);

        $this->assertSame('biobio', $synced->destination_region_code);
        $this->assertSame(0, $centralStock->refresh()->reserved_stock);
        $this->assertSame(1, $regionalStock->refresh()->reserved_stock);
    }

    public function test_guests_do_not_receive_central_stock_as_a_default_region(): void
    {
        [$central, , $centralLocation] = $this->warehouses();
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 20);

        $display = app(ProductDisplayService::class)->presentProduct($product);

        $this->assertFalse($display['can_purchase']);
        $this->assertSame(0, $display['stock']);
        $this->assertSame('Inicia sesión para consultar stock', $display['stock_label']);
    }

    public function test_the_manual_delivery_region_endpoint_no_longer_exists(): void
    {
        $this->post('/region-despacho', ['region' => 'biobio'])->assertNotFound();
    }

    private function customerWithShippingRegion(string $region): User
    {
        $user = User::factory()->create();
        $profile = $user->customerProfile()->create([
            'customer_type' => 'individual',
            'first_name' => 'Cliente',
            'last_name' => 'Regional',
            'email' => $user->email,
            'is_active' => true,
        ]);
        $profile->addresses()->create([
            'address_type' => 'shipping',
            'address_label' => 'main',
            'contact_name' => 'Cliente Regional',
            'phone' => '999999999',
            'country' => 'Chile',
            'region' => $region,
            'commune' => 'Concepción',
            'city' => 'Concepción',
            'street' => 'Calle Uno',
            'number' => '123',
            'is_default' => true,
            'is_active' => true,
        ]);

        return $user;
    }

    private function warehouses(): array
    {
        $central = Warehouse::factory()->create([
            'code' => 'ECOM',
            'type' => 'ecommerce',
            'region' => 'metropolitana',
            'is_central' => true,
            'fulfillment_priority' => 1,
        ]);
        $regional = Warehouse::factory()->create([
            'code' => 'BIOBIO',
            'type' => 'branch',
            'region' => 'biobio',
            'is_central' => false,
            'fulfillment_priority' => 10,
        ]);

        return [$central, $regional, $this->location($central), $this->location($regional)];
    }

    private function location(Warehouse $warehouse): WarehouseLocation
    {
        return $warehouse->locations()->create([
            'name' => 'General',
            'code' => 'GENERAL',
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
