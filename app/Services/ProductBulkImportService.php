<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductImport;
use App\Models\ProductVariant;
use App\Models\Tag;
use App\Models\Tax;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ProductBulkImportService
{
    private array $wcProductMap = [];
    private array $summary = [
        'products_created' => 0,
        'products_updated' => 0,
        'variants_created' => 0,
        'variants_updated' => 0,
        'categories_created' => 0,
        'brands_created' => 0,
        'tags_created' => 0,
        'attributes_created' => 0,
        'images_linked' => 0,
        'stock_loaded' => 0,
    ];

    public function importWooCommerceCsv(ProductImport $import): ProductImport
    {
        $import->update(['status' => 'processing', 'started_at' => now()]);

        $errors = [];

        try {
            $rows = $this->readCsv(storage_path('app/'.$import->file_path));
            $import->update(['total_rows' => $rows->count()]);

            DB::transaction(function () use ($rows, &$errors, $import): void {
                foreach ($rows->where('Tipo', '!=', 'variation') as $index => $row) {
                    try {
                        $this->importProductRow($row);
                        $import->increment('successful_rows');
                    } catch (Throwable $exception) {
                        $errors[] = $this->error($index, $row, $exception);
                        $import->increment('failed_rows');
                    }
                }

                foreach ($rows->where('Tipo', 'variation') as $index => $row) {
                    try {
                        $this->importVariantRow($row);
                        $import->increment('successful_rows');
                    } catch (Throwable $exception) {
                        $errors[] = $this->error($index, $row, $exception);
                        $import->increment('failed_rows');
                    }
                }
            });

            $import->update([
                'status' => empty($errors) ? 'completed' : 'completed',
                'summary' => $this->summary,
                'errors' => $errors,
                'finished_at' => now(),
            ]);

            AuditLogger::record('imported', 'products', 'Importación masiva de productos completada.');
        } catch (Throwable $exception) {
            $import->update([
                'status' => 'failed',
                'errors' => [['message' => $exception->getMessage()]],
                'finished_at' => now(),
            ]);

            AuditLogger::record('failed', 'products', 'Importación masiva fallida: '.$exception->getMessage());
        }

        return $import->fresh();
    }

    private function readCsv(string $path): Collection
    {
        if (! is_file($path)) {
            throw new \RuntimeException('No se encontró el archivo CSV.');
        }

        $handle = fopen($path, 'rb');
        $headers = fgetcsv($handle);

        if (! $headers || ! in_array('Nombre', $headers, true)) {
            throw new \RuntimeException('El CSV no parece ser una exportación de productos WooCommerce válida.');
        }

        $rows = collect();

        while (($data = fgetcsv($handle)) !== false) {
            if (count(array_filter($data, fn ($value) => filled($value))) === 0) {
                continue;
            }

            $rows->push(collect($headers)->combine(array_pad($data, count($headers), null))->map(fn ($value) => is_string($value) ? trim($value) : $value)->all());
        }

        fclose($handle);

        return $rows;
    }

    private function importProductRow(array $row): Product
    {
        $wcId = $this->value($row, 'ID');
        $incomingSku = $this->nullable($row, 'SKU');
        $existing = $incomingSku ? Product::query()->where('sku', $incomingSku)->first() : Product::query()->where('slug', Str::slug($this->value($row, 'Nombre')))->first();
        $sku = $incomingSku ?: ($existing?->sku ?: $this->generateNextSku());

        $category = $this->firstCategory($row);
        $brand = $this->firstBrand($row);
        $type = $this->value($row, 'Tipo') === 'variable' ? 'variable' : 'simple';

        [$longDescription, $technicalDescription] = $this->splitTechnicalDescription($this->nullable($row, 'Descripción'));

        $payload = [
            'category_id' => $category?->id,
            'brand_id' => $brand?->id,
            'tax_id' => Tax::query()->where('is_default', true)->value('id'),
            'name' => $this->value($row, 'Nombre'),
            'sku' => $sku,
            'barcode' => $this->firstFilled($row, ['GTIN, UPC, EAN o ISBN', 'GTIN13 / EAN', 'GTIN12 / UPC', 'GTIN14 / ITF-14', 'GTIN8']),
            'product_type' => $type,
            'short_description' => $this->cleanHtml($this->nullable($row, 'Descripción corta')),
            'long_description' => $longDescription,
            'technical_description' => $technicalDescription,
            'regular_price' => $this->decimal($row, 'Precio normal'),
            'sale_price' => $this->decimal($row, 'Precio rebajado'),
            'sale_starts_at' => $this->date($row, 'Día en que empieza el precio rebajado'),
            'sale_ends_at' => $this->date($row, 'Día en que termina el precio rebajado'),
            'weight' => $this->decimal($row, 'Peso (kg)'),
            'length' => $this->decimal($row, 'Longitud (cm)'),
            'width' => $this->decimal($row, 'Anchura (cm)'),
            'height' => $this->decimal($row, 'Altura (cm)'),
            'weight_unit_id' => MeasurementUnit::query()->where('code', 'kg')->value('id'),
            'dimension_unit_id' => MeasurementUnit::query()->where('code', 'cm')->value('id'),
            'is_active' => $this->value($row, 'Publicado', '1') === '1',
            'is_featured' => $this->value($row, '¿Está destacado?', '0') === '1',
            'is_visible' => $this->value($row, 'Visibilidad en el catálogo', 'visible') !== 'hidden',
        ];

        $product = $existing ?: new Product();
        $product->fill($payload)->save();
        $existing ? $this->summary['products_updated']++ : $this->summary['products_created']++;

        if ($wcId) {
            $this->wcProductMap[(string) $wcId] = $product->id;
        }

        $this->syncTags($product, $row);
        $this->syncImages($product, $row);
        $this->syncProductAttributes($row);
        if ($product->product_type === 'simple') {
            $this->syncInitialStock($product->id, null, $row);
        }

        return $product;
    }

    private function importVariantRow(array $row): ?ProductVariant
    {
        $parent = $this->parentProduct($row);
        if (! $parent) {
            throw new \RuntimeException('No se encontró el producto padre de la variación.');
        }

        $sku = $this->nullable($row, 'SKU');
        $variantName = $this->variantName($row);
        $variant = $sku ? ProductVariant::query()->where('sku', $sku)->first() : null;
        $variant ??= ProductVariant::query()->where('product_id', $parent->id)->where('name', $variantName)->first();
        $variant ??= new ProductVariant(['product_id' => $parent->id]);

        $variant->fill([
            'product_id' => $parent->id,
            'sku' => $sku,
            'barcode' => $this->firstFilled($row, ['GTIN, UPC, EAN o ISBN', 'GTIN13 / EAN', 'GTIN12 / UPC', 'GTIN14 / ITF-14', 'GTIN8']),
            'name' => $variantName,
            'regular_price' => $this->decimal($row, 'Precio normal'),
            'sale_price' => $this->decimal($row, 'Precio rebajado'),
            'sale_starts_at' => $this->date($row, 'Día en que empieza el precio rebajado'),
            'sale_ends_at' => $this->date($row, 'Día en que termina el precio rebajado'),
            'weight' => $this->decimal($row, 'Peso (kg)'),
            'length' => $this->decimal($row, 'Longitud (cm)'),
            'width' => $this->decimal($row, 'Anchura (cm)'),
            'height' => $this->decimal($row, 'Altura (cm)'),
            'weight_unit_id' => MeasurementUnit::query()->where('code', 'kg')->value('id'),
            'dimension_unit_id' => MeasurementUnit::query()->where('code', 'cm')->value('id'),
            'image_path' => $this->firstImage($row),
            'is_active' => $this->value($row, 'Publicado', '1') === '1',
        ])->save();

        $variant->wasRecentlyCreated ? $this->summary['variants_created']++ : $this->summary['variants_updated']++;
        $this->syncVariantAttributes($variant, $row);
        $this->syncInitialStock($parent->id, $variant->id, $row);

        return $variant;
    }

    private function firstCategory(array $row): ?Category
    {
        $paths = $this->splitList($this->nullable($row, 'Categorías'));
        $first = $paths[0] ?? null;

        return $first ? $this->categoryPath($first) : null;
    }

    private function categoryPath(string $path): ?Category
    {
        $parent = null;
        $category = null;

        foreach (preg_split('/\s*>\s*/', html_entity_decode($path)) as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }

            $category = Category::query()
                ->where('name', $name)
                ->where('parent_id', $parent?->id)
                ->first();

            if (! $category) {
                $category = Category::query()->create(['name' => $name, 'parent_id' => $parent?->id, 'is_active' => true, 'sort_order' => 0]);
                $this->summary['categories_created']++;
            }

            $parent = $category;
        }

        return $category;
    }

    private function firstBrand(array $row): ?Brand
    {
        $brandName = $this->splitList($this->nullable($row, 'Marcas'))[0] ?? null;

        if (! $brandName) {
            return null;
        }

        $brand = Brand::query()->where('name', $brandName)->first();
        if (! $brand) {
            $brand = Brand::query()->create(['name' => $brandName, 'is_active' => true]);
            $this->summary['brands_created']++;
        }

        return $brand;
    }

    private function syncTags(Product $product, array $row): void
    {
        $ids = [];
        foreach ($this->splitList($this->nullable($row, 'Etiquetas')) as $tagName) {
            $tag = Tag::query()->where('name', $tagName)->first();
            if (! $tag) {
                $tag = Tag::query()->create(['name' => $tagName, 'is_active' => true]);
                $this->summary['tags_created']++;
            }
            $ids[] = $tag->id;
        }

        $product->tags()->sync($ids);
    }

    private function syncImages(Product $product, array $row): void
    {
        $images = $this->splitList($this->nullable($row, 'Imágenes'));
        if (empty($images)) {
            return;
        }

        ProductImage::query()->where('product_id', $product->id)->delete();
        foreach ($images as $index => $image) {
            ProductImage::query()->create([
                'product_id' => $product->id,
                'image_path' => $image,
                'alt_text' => $product->name,
                'sort_order' => $index,
                'is_primary' => $index === 0,
            ]);
            $this->summary['images_linked']++;
        }
    }

    private function syncProductAttributes(array $row): void
    {
        foreach ($this->attributePairs($row) as [$name, $values]) {
            $attribute = $this->attribute($name);
            foreach ($values as $value) {
                $this->attributeValue($attribute, $value);
            }
        }
    }

    private function syncVariantAttributes(ProductVariant $variant, array $row): void
    {
        $sync = [];
        foreach ($this->attributePairs($row) as [$name, $values]) {
            $attribute = $this->attribute($name);
            foreach ($values as $value) {
                $attributeValue = $this->attributeValue($attribute, $value);
                $sync[$attributeValue->id] = ['attribute_id' => $attribute->id];
            }
        }

        $variant->attributeValues()->sync($sync);
    }

    private function attribute(string $name): Attribute
    {
        $attribute = Attribute::query()->where('name', $name)->first();
        if (! $attribute) {
            $attribute = Attribute::query()->create(['name' => $name, 'type' => $this->attributeType($name), 'is_active' => true, 'sort_order' => 0]);
            $this->summary['attributes_created']++;
        }

        return $attribute;
    }

    private function attributeValue(Attribute $attribute, string $value): AttributeValue
    {
        return AttributeValue::query()->firstOrCreate(
            ['attribute_id' => $attribute->id, 'slug' => Str::slug($value)],
            ['value' => $value, 'is_active' => true, 'sort_order' => 0]
        );
    }

    private function parentProduct(array $row): ?Product
    {
        $parent = $this->nullable($row, 'Superior');
        $parentId = $parent && preg_match('/(\d+)/', $parent, $matches) ? $matches[1] : null;

        if ($parentId && isset($this->wcProductMap[$parentId])) {
            return Product::query()->find($this->wcProductMap[$parentId]);
        }

        if ($parent && ! is_numeric($parent)) {
            $bySku = Product::query()->where('sku', $parent)->first();
            if ($bySku) {
                return $bySku;
            }
        }

        $name = $this->nullable($row, 'Nombre');
        $parentName = $name && str_contains($name, ' - ') ? Str::before($name, ' - ') : null;

        return $parentName ? Product::query()->where('name', $parentName)->first() : null;
    }

    private function attributePairs(array $row): array
    {
        $pairs = [];
        foreach ($row as $key => $value) {
            if (! preg_match('/^Nombre del atributo (\d+)$/', $key, $matches) || blank($value)) {
                continue;
            }

            $values = $this->splitAttributeValues($row['Valor(es) del atributo '.$matches[1]] ?? null);
            if (! empty($values)) {
                $pairs[] = [trim($value), $values];
            }
        }

        return $pairs;
    }

    private function variantName(array $row): ?string
    {
        $parts = collect($this->attributePairs($row))->flatMap(fn ($pair) => $pair[1])->filter()->values();

        return $parts->isEmpty() ? $this->nullable($row, 'Nombre') : $parts->implode(' / ');
    }

    private function splitList(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(str_getcsv($value))->flatMap(fn ($item) => explode('|', $item))->map(fn ($item) => trim($item))->filter()->unique()->values()->all();
    }

    private function splitAttributeValues(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(preg_split('/\s*\|\s*|\s*,\s*/', $value))->map(fn ($item) => trim($item))->filter()->unique()->values()->all();
    }

    private function firstImage(array $row): ?string
    {
        return $this->splitList($this->nullable($row, 'Imágenes'))[0] ?? null;
    }

    private function firstFilled(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $this->nullable($row, $key);
            if ($value) {
                return $value;
            }
        }

        return null;
    }

    private function attributeType(string $name): string
    {
        return str_contains(Str::lower($name), 'color') ? 'color' : (str_contains(Str::lower($name), 'talla') ? 'size' : 'select');
    }

    private function cleanHtml(?string $value): ?string
    {
        return $value ? trim(str_replace('\n', "\n", $value)) : null;
    }

    private function splitTechnicalDescription(?string $value): array
    {
        $html = $this->cleanHtml($value);

        if (! $html || ! str_contains(Str::lower($html), '<table')) {
            return [$html, null];
        }

        $lower = Str::lower($html);
        $tablePosition = strpos($lower, '<table');
        $headingPosition = strrpos(substr($lower, 0, $tablePosition), '<h');
        $splitPosition = $headingPosition !== false ? $headingPosition : $tablePosition;

        return [
            trim(substr($html, 0, $splitPosition)) ?: null,
            trim(substr($html, $splitPosition)) ?: null,
        ];
    }

    private function decimal(array $row, string $key): ?float
    {
        $value = str_replace(['$', '.', ','], ['', '', '.'], $this->nullable($row, $key) ?? '');

        return is_numeric($value) ? (float) $value : null;
    }

    private function date(array $row, string $key): ?string
    {
        $value = $this->nullable($row, $key);

        return $value ? date('Y-m-d H:i:s', strtotime($value)) : null;
    }

    private function value(array $row, string $key, ?string $default = null): ?string
    {
        return $row[$key] ?? $default;
    }

    private function nullable(array $row, string $key): ?string
    {
        $value = $this->value($row, $key);

        return filled($value) ? trim((string) $value) : null;
    }

    private function error(int|string $index, array $row, Throwable $exception): array
    {
        return ['row' => ((int) $index) + 2, 'name' => $row['Nombre'] ?? null, 'message' => $exception->getMessage()];
    }

    private function syncInitialStock(int $productId, ?int $variantId, array $row): void
    {
        $quantity = $this->decimal($row, 'Inventario');
        if (! $quantity || $quantity <= 0) {
            return;
        }

        $warehouseId = Warehouse::query()
            ->where('code', 'MAIN')
            ->orWhere('code', 'POS01')
            ->orWhere('is_active', true)
            ->value('id');

        if (! $warehouseId) {
            return;
        }

        $inventory = app(InventoryService::class);
        $level = $inventory->getStockLevel((int) $warehouseId, $productId, $variantId);
        if ((float) $level->physical_stock > 0) {
            return;
        }

        $inventory->increaseStock((int) $warehouseId, $productId, $variantId, null, (float) $quantity, 'initial', 'Stock inicial desde importacion masiva');
        $this->summary['stock_loaded']++;
    }

    private function generateNextSku(): string
    {
        $lastSku = Product::withTrashed()
            ->where('sku', 'like', 'QE-%')
            ->orderByDesc('id')
            ->value('sku');

        $next = 1;
        if ($lastSku && preg_match('/QE-(\d+)/', $lastSku, $matches)) {
            $next = ((int) $matches[1]) + 1;
        } else {
            $next = Product::withTrashed()->count() + 1;
        }

        do {
            $sku = 'QE-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            $next++;
        } while (
            Product::withTrashed()->where('sku', $sku)->exists()
            || ProductVariant::withTrashed()->where('sku', $sku)->exists()
        );

        return $sku;
    }
}
