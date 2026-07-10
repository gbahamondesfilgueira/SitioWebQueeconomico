<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleCommercialResources;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuantityDiscount;

class QuantityDiscountController extends Controller
{
    use ManagesSimpleCommercialResources;

    protected string $modelClass = QuantityDiscount::class;
    protected string $tableName = 'quantity_discounts';
    protected string $routePrefix = 'admin.quantity-discounts';
    protected string $auditModule = 'quantity_discounts';
    protected string $title = 'Descuentos por cantidad';
    protected string $singularLabel = 'Descuento por cantidad';
    protected string $codeColumn = 'name';
    protected array $showRelations = ['product', 'variant', 'category', 'brand'];
    protected array $booleanFields = ['is_active'];
    protected array $columns = ['name' => 'Nombre', 'min_quantity' => 'Mín.', 'max_quantity' => 'Máx.', 'discount_type' => 'Tipo', 'discount_value' => 'Valor', 'is_active' => 'Activo'];
    protected array $options = ['discount_type' => ['percentage', 'fixed', 'fixed_price']];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'product_id', 'label' => 'Producto', 'type' => 'product', 'rules' => ['nullable', 'exists:products,id']],
        ['name' => 'product_variant_id', 'label' => 'Variante', 'type' => 'variant', 'rules' => ['nullable', 'exists:product_variants,id']],
        ['name' => 'category_id', 'label' => 'Categoría', 'type' => 'category', 'rules' => ['nullable', 'exists:categories,id']],
        ['name' => 'brand_id', 'label' => 'Marca', 'type' => 'brand', 'rules' => ['nullable', 'exists:brands,id']],
        ['name' => 'min_quantity', 'label' => 'Cantidad mínima', 'type' => 'number', 'rules' => ['required', 'integer', 'min:1']],
        ['name' => 'max_quantity', 'label' => 'Cantidad máxima', 'type' => 'number', 'rules' => ['nullable', 'integer', 'gte:min_quantity']],
        ['name' => 'discount_type', 'label' => 'Tipo', 'type' => 'select', 'rules' => ['required', 'in:percentage,fixed,fixed_price']],
        ['name' => 'discount_value', 'label' => 'Valor', 'type' => 'number', 'rules' => ['required', 'numeric', 'min:0']],
        ['name' => 'starts_at', 'label' => 'Inicio', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ['name' => 'ends_at', 'label' => 'Término', 'type' => 'datetime-local', 'rules' => ['nullable', 'date', 'after_or_equal:starts_at']],
        ['name' => 'is_active', 'label' => 'Activo', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
    ];

    protected function formData($item = null): array
    {
        return ['products' => Product::orderBy('name')->get(), 'variants' => ProductVariant::with('product')->get(), 'categories' => Category::orderBy('name')->get(), 'brands' => Brand::orderBy('name')->get()];
    }
}
