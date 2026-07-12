<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = ucfirst(fake()->unique()->words(3, true));
        $costPrice = fake()->numberBetween(1000, 50000);
        $regularPrice = $costPrice + fake()->numberBetween(1000, 50000);

        return [
            'category_id' => Category::factory(),
            'brand_id' => null,
            'supplier_id' => null,
            'tax_id' => null,
            'origin_country_id' => null,

            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'sku' => 'SKU-'.Str::upper(Str::random(10)),
            'barcode' => fake()->unique()->numerify('#############'),
            'product_type' => 'simple',

            'short_description' => fake()->sentence(),
            'long_description' => fake()->paragraph(),
            'technical_description' => fake()->paragraph(),

            'cost_price' => $costPrice,
            'regular_price' => $regularPrice,
            'sale_price' => null,
            'sale_starts_at' => null,
            'sale_ends_at' => null,

            'weight' => fake()->randomFloat(3, 0.1, 20),
            'height' => null,
            'width' => null,
            'length' => null,
            'weight_unit_id' => null,
            'dimension_unit_id' => null,

            'is_active' => true,
            'is_featured' => false,
            'is_visible' => true,

            'seo_title' => $name,
            'seo_description' => fake()->sentence(),
            'seo_keywords' => implode(', ', fake()->words(4)),
        ];
    }
}
