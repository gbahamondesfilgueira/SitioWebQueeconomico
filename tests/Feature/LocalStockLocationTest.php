<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Services\GeoRegionService;
use App\Services\ProductDisplayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalStockLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_known_chilean_coordinates_resolve_to_the_correct_region(): void
    {
        $regions = app(GeoRegionService::class);

        $this->assertSame('metropolitana', $regions->regionForCoordinates(-33.4489, -70.6693));
        $this->assertSame('biobio', $regions->regionForCoordinates(-36.8270, -73.0503));
        $this->assertSame('antofagasta', $regions->regionForCoordinates(-23.6509, -70.3975));
        $this->assertSame('los-lagos', $regions->regionForCoordinates(-41.4689, -72.9411));
        $this->assertNull($regions->regionForCoordinates(40.4168, -3.7038));
    }

    public function test_location_endpoint_stores_only_the_resolved_region(): void
    {
        $this->postJson(route('store.location.stock-region'), [
            'latitude' => -36.8270,
            'longitude' => -73.0503,
            'accuracy' => 25,
        ])->assertOk()
            ->assertJsonPath('region', 'biobio')
            ->assertJsonPath('region_label', 'Región del Biobío')
            ->assertJsonPath('differs_from_account', false);

        $this->assertSame('biobio', session('store.local_stock_region'));
        $this->assertNull(session('store.local_stock_latitude'));
        $this->assertNull(session('store.local_stock_longitude'));
    }

    public function test_detected_location_does_not_change_the_accounts_purchase_region(): void
    {
        $user = $this->customerWithShippingRegion('Región Metropolitana');

        $this->actingAs($user)->postJson(route('store.location.stock-region'), [
            'latitude' => -36.8270,
            'longitude' => -73.0503,
        ])->assertOk()
            ->assertJsonPath('region', 'biobio')
            ->assertJsonPath('account_region', 'metropolitana')
            ->assertJsonPath('differs_from_account', true);
    }

    public function test_guest_can_view_nearby_regional_stock_but_still_cannot_purchase(): void
    {
        [$central, $centralLocation] = $this->warehouse('ECOM', 'ecommerce', 'metropolitana', true, 1);
        [$regional, $regionalLocation] = $this->warehouse('BIOBIO', 'branch', 'biobio', false, 10);
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 20);
        $this->stock($regional, $regionalLocation, $product, 4);

        $this->withSession([
            'store.local_stock_region' => 'biobio',
            'store.local_stock_detected_at' => now()->timestamp,
        ]);

        $display = app(ProductDisplayService::class)->presentProduct($product);

        $this->assertFalse($display['can_purchase']);
        $this->assertSame(0, $display['stock']);
        $this->assertTrue($display['show_local_stock']);
        $this->assertSame(4, $display['local_stock']);
        $this->assertSame('Región del Biobío', $display['local_stock_region']);
        $this->assertSame($regional->name, $display['local_stock_warehouse']);
    }

    public function test_nearby_stock_does_not_show_central_fallback_as_local_stock(): void
    {
        [$central, $centralLocation] = $this->warehouse('ECOM', 'ecommerce', 'metropolitana', true, 1);
        $product = Product::factory()->create();
        $this->stock($central, $centralLocation, $product, 20);

        $this->withSession([
            'store.local_stock_region' => 'biobio',
            'store.local_stock_detected_at' => now()->timestamp,
        ]);

        $display = app(ProductDisplayService::class)->presentProduct($product);

        $this->assertSame(0, $display['local_stock']);
        $this->assertSame('Sin stock', $display['local_stock_label']);
        $this->assertNull($display['local_stock_warehouse']);
    }

    private function customerWithShippingRegion(string $region): User
    {
        $user = User::factory()->create();
        $profile = $user->customerProfile()->create([
            'customer_type' => 'individual',
            'first_name' => 'Cliente',
            'last_name' => 'Ubicación',
            'email' => $user->email,
            'is_active' => true,
        ]);
        $profile->addresses()->create([
            'address_type' => 'shipping',
            'address_label' => 'main',
            'contact_name' => 'Cliente Ubicación',
            'phone' => '999999999',
            'country' => 'Chile',
            'region' => $region,
            'commune' => 'Santiago',
            'city' => 'Santiago',
            'street' => 'Calle Uno',
            'number' => '123',
            'is_default' => true,
            'is_active' => true,
        ]);

        return $user;
    }

    private function warehouse(string $code, string $type, string $region, bool $central, int $priority): array
    {
        $warehouse = Warehouse::factory()->create([
            'code' => $code,
            'type' => $type,
            'region' => $region,
            'is_central' => $central,
            'fulfillment_priority' => $priority,
        ]);
        $location = $warehouse->locations()->create([
            'name' => 'General',
            'code' => 'GENERAL',
            'is_active' => true,
            'is_sellable' => true,
        ]);

        return [$warehouse, $location];
    }

    private function stock(Warehouse $warehouse, WarehouseLocation $location, Product $product, int $quantity): void
    {
        StockLevel::query()->create([
            'warehouse_id' => $warehouse->id,
            'warehouse_location_id' => $location->id,
            'product_id' => $product->id,
            'physical_stock' => $quantity,
            'reserved_stock' => 0,
            'minimum_stock' => 0,
        ]);
    }
}
