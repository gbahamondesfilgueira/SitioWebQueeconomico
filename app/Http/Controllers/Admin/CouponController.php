<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleCommercialResources;
use App\Http\Controllers\Controller;
use App\Models\Coupon;

class CouponController extends Controller
{
    use ManagesSimpleCommercialResources;

    protected string $modelClass = Coupon::class;
    protected string $tableName = 'coupons';
    protected string $routePrefix = 'admin.coupons';
    protected string $auditModule = 'coupons';
    protected string $title = 'Cupones';
    protected string $singularLabel = 'Cupón';
    protected string $codeColumn = 'code';
    protected array $showRelations = ['usages'];
    protected array $booleanFields = ['is_active'];
    protected array $columns = ['name' => 'Nombre', 'code' => 'Código', 'discount_type' => 'Tipo', 'discount_value' => 'Valor', 'is_active' => 'Activo'];
    protected array $options = ['discount_type' => ['percentage', 'fixed', 'free_shipping']];
    protected array $fields = [
        ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'rules' => ['required', 'string', 'max:80'], 'unique' => true],
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ['name' => 'discount_type', 'label' => 'Tipo', 'type' => 'select', 'rules' => ['required', 'in:percentage,fixed,free_shipping']],
        ['name' => 'discount_value', 'label' => 'Valor', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'starts_at', 'label' => 'Inicio', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ['name' => 'ends_at', 'label' => 'Término', 'type' => 'datetime-local', 'rules' => ['nullable', 'date', 'after_or_equal:starts_at']],
        ['name' => 'usage_limit', 'label' => 'Límite uso', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:1']],
        ['name' => 'usage_limit_per_customer', 'label' => 'Límite por cliente', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:1']],
        ['name' => 'min_subtotal', 'label' => 'Subtotal mínimo', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'min_quantity', 'label' => 'Cantidad mínima', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:1']],
        ['name' => 'is_active', 'label' => 'Activo', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
    ];
}
