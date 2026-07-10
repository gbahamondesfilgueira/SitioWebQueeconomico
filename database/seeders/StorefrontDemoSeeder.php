<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\OriginCountry;
use App\Models\Product;
use App\Models\ProductPack;
use App\Models\ProductVariant;
use App\Models\StockLevel;
use App\Models\Tax;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StorefrontDemoSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->where('slug', 'calzado')->first() ?? Category::query()->first();
        $brand = Brand::query()->where('slug', 'likeshop')->first() ?? Brand::query()->first();
        $tax = Tax::query()->where('is_default', true)->first();
        $origin = OriginCountry::query()->where('iso_code', 'CL')->first();
        $weightUnit = MeasurementUnit::query()->where('code', 'kg')->first();
        $dimensionUnit = MeasurementUnit::query()->where('code', 'cm')->first();
        $warehouse = Warehouse::query()->where('code', 'ECOM')->first() ?? Warehouse::query()->first();

        if (! $category || ! $brand || ! $warehouse) {
            return;
        }

        $simple = Product::query()->updateOrCreate(
            ['sku' => 'QE-ZAPATO-DEMO'],
            [
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'tax_id' => $tax?->id,
                'origin_country_id' => $origin?->id,
                'name' => 'Zapato Casual Demo',
                'slug' => 'zapato-casual-demo',
                'barcode' => '780000000001',
                'product_type' => 'simple',
                'short_description' => 'Producto simple de demostración para la tienda pública.',
                'long_description' => 'Calzado cómodo preparado para mostrar precio, oferta, stock y SEO público.',
                'technical_description' => 'Material sintético, suela liviana, uso diario.',
                'regular_price' => 24990,
                'sale_price' => 19990,
                'sale_ends_at' => now()->addDays(20),
                'weight' => 0.8,
                'height' => 12,
                'width' => 22,
                'length' => 32,
                'weight_unit_id' => $weightUnit?->id,
                'dimension_unit_id' => $dimensionUnit?->id,
                'is_active' => true,
                'is_featured' => true,
                'is_visible' => true,
                'seo_title' => 'Zapato Casual Demo',
                'seo_description' => 'Zapato casual demo disponible en Qué Económico.',
            ],
        );

        StockLevel::query()->updateOrCreate(
            ['warehouse_id' => $warehouse->id, 'warehouse_location_id' => null, 'product_id' => $simple->id, 'product_variant_id' => null],
            ['physical_stock' => 12, 'reserved_stock' => 0, 'minimum_stock' => 2],
        );

        $variable = Product::query()->updateOrCreate(
            ['sku' => 'QE-BOTA-DEMO'],
            [
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'tax_id' => $tax?->id,
                'origin_country_id' => $origin?->id,
                'name' => 'Bota Mujer Impermeable Demo',
                'slug' => 'bota-mujer-impermeable-demo',
                'barcode' => '780000000002',
                'product_type' => 'variable',
                'short_description' => 'Producto variable de demostración con tallas y color.',
                'long_description' => 'Bota preparada para mostrar selector de variantes, precios por variante y stock disponible.',
                'technical_description' => 'Capellada sintética, interior textil, suela antideslizante.',
                'regular_price' => 39990,
                'weight_unit_id' => $weightUnit?->id,
                'dimension_unit_id' => $dimensionUnit?->id,
                'is_active' => true,
                'is_featured' => true,
                'is_visible' => true,
            ],
        );

        $color = Attribute::query()->where('slug', 'color')->first();
        $size = Attribute::query()->where('slug', 'talla')->first();
        $black = AttributeValue::query()->where('attribute_id', $color?->id)->where('slug', 'negro')->first();

        foreach (['36', '37'] as $index => $sizeValue) {
            $sizeModel = AttributeValue::query()->where('attribute_id', $size?->id)->where('slug', $sizeValue)->first();
            $variant = ProductVariant::query()->updateOrCreate(
                ['sku' => 'QE-BOTA-NEGRO-'.$sizeValue],
                [
                    'product_id' => $variable->id,
                    'barcode' => '78000000010'.$index,
                    'name' => 'Negro / '.$sizeValue,
                    'regular_price' => 39990,
                    'sale_price' => $index === 0 ? 34990 : null,
                    'sale_ends_at' => $index === 0 ? now()->addDays(10) : null,
                    'weight' => 1.1,
                    'height' => 15,
                    'width' => 24,
                    'length' => 34,
                    'weight_unit_id' => $weightUnit?->id,
                    'dimension_unit_id' => $dimensionUnit?->id,
                    'is_active' => true,
                ],
            );

            $sync = collect([$black?->id, $sizeModel?->id])->filter()->mapWithKeys(fn ($id) => [$id => ['attribute_id' => AttributeValue::query()->find($id)?->attribute_id]])->all();
            $variant->attributeValues()->sync($sync);

            StockLevel::query()->updateOrCreate(
                ['warehouse_id' => $warehouse->id, 'warehouse_location_id' => null, 'product_id' => $variable->id, 'product_variant_id' => $variant->id],
                ['physical_stock' => $index === 0 ? 4 : 8, 'reserved_stock' => 0, 'minimum_stock' => 2],
            );
        }

        $simple->relatedProducts()->syncWithoutDetaching([$variable->id => ['relation_type' => 'related']]);

        $pack = ProductPack::query()->updateOrCreate(
            ['slug' => 'pack-calzado-demo'],
            [
                'name' => 'Pack Calzado Demo',
                'sku' => 'QE-PACK-CALZADO',
                'description' => 'Pack visible para probar el ecommerce público.',
                'regular_price' => 64980,
                'pack_price' => 54990,
                'is_active' => true,
                'is_visible' => true,
            ],
        );

        $pack->items()->updateOrCreate(['product_id' => $simple->id, 'product_variant_id' => null], ['quantity' => 1]);
        $pack->items()->updateOrCreate(['product_id' => $variable->id, 'product_variant_id' => $variable->variants()->first()?->id], ['quantity' => 1]);
    }
}
