<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_factory_can_create_a_product(): void
    {
        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sku' => $product->sku,
        ]);

        $this->assertInstanceOf(Product::class, $product);
    }

    public function test_product_belongs_to_a_category(): void
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertSame(
            $category->id,
            $product->category->id
        );
    }

    public function test_product_generates_a_slug_from_its_name(): void
    {
        $product = Product::factory()->create([
            'name' => 'Bota de Invierno Mujer Negro',
            'slug' => null,
        ]);

        $this->assertSame(
            Str::slug('Bota de Invierno Mujer Negro'),
            $product->fresh()->slug
        );
    }

    public function test_product_can_store_prices_and_visibility_status(): void
    {
        $product = Product::factory()->create([
            'regular_price' => 19990,
            'sale_price' => 14990,
            'is_active' => true,
            'is_visible' => true,
            'is_featured' => true,
        ]);

        $product->refresh();

        $this->assertSame('19990.00', $product->regular_price);
        $this->assertSame('14990.00', $product->sale_price);
        $this->assertTrue($product->is_active);
        $this->assertTrue($product->is_visible);
        $this->assertTrue($product->is_featured);
    }

    public function test_product_can_be_soft_deleted(): void
    {
        $product = Product::factory()->create();

        $product->delete();

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }
}