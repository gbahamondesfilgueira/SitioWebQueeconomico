<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleCommercialResources;
use App\Http\Controllers\Controller;
use App\Models\Promotion;

class PromotionController extends Controller
{
    use ManagesSimpleCommercialResources;

    protected string $modelClass = Promotion::class;
    protected string $tableName = 'promotions';
    protected string $routePrefix = 'admin.promotions';
    protected string $auditModule = 'promotions';
    protected string $title = 'Promociones';
    protected string $singularLabel = 'Promoción';
    protected string $codeColumn = 'code';
    protected array $showRelations = [];
    protected array $booleanFields = ['is_stackable', 'is_active'];
    protected array $columns = ['name' => 'Nombre', 'code' => 'Código', 'promotion_type' => 'Tipo', 'priority' => 'Prioridad', 'is_active' => 'Activa'];
    protected array $options = ['promotion_type' => Promotion::TYPES];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:80'], 'unique' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ['name' => 'promotion_type', 'label' => 'Tipo', 'type' => 'select', 'rules' => ['required', 'in:percentage_discount,fixed_discount,fixed_price,quantity_discount,buy_x_get_y,free_shipping,gift_product,bundle_discount']],
        ['name' => 'starts_at', 'label' => 'Inicio', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ['name' => 'ends_at', 'label' => 'Término', 'type' => 'datetime-local', 'rules' => ['nullable', 'date', 'after_or_equal:starts_at']],
        ['name' => 'priority', 'label' => 'Prioridad', 'type' => 'number', 'rules' => ['required', 'integer', 'min:0']],
        ['name' => 'discount_percentage', 'label' => '% descuento', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0', 'max:100']],
        ['name' => 'discount_amount', 'label' => 'Monto descuento', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'fixed_price', 'label' => 'Precio fijo', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
        ['name' => 'min_quantity', 'label' => 'Cantidad mínima', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:1']],
        ['name' => 'usage_limit', 'label' => 'Límite uso', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:1']],
        ['name' => 'is_stackable', 'label' => 'Acumulable', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
        ['name' => 'is_active', 'label' => 'Activa', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
    ];
}
