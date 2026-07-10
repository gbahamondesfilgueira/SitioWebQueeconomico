<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSimpleCommercialResources;
use App\Http\Controllers\Controller;
use App\Models\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    use ManagesSimpleCommercialResources;

    protected string $modelClass = PriceList::class;
    protected string $tableName = 'price_lists';
    protected string $routePrefix = 'admin.price-lists';
    protected string $auditModule = 'price_lists';
    protected string $title = 'Listas de precios';
    protected string $singularLabel = 'Lista de precios';
    protected string $codeColumn = 'code';
    protected array $showRelations = ['items.product', 'items.variant'];
    protected array $booleanFields = ['is_default', 'is_active'];
    protected array $columns = ['name' => 'Nombre', 'code' => 'Código', 'currency' => 'Moneda', 'is_default' => 'Default', 'is_active' => 'Activa'];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'rules' => ['required', 'string', 'max:50'], 'unique' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ['name' => 'currency', 'label' => 'Moneda', 'type' => 'text', 'rules' => ['required', 'string', 'max:10']],
        ['name' => 'starts_at', 'label' => 'Inicio', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ['name' => 'ends_at', 'label' => 'Término', 'type' => 'datetime-local', 'rules' => ['nullable', 'date', 'after_or_equal:starts_at']],
        ['name' => 'is_default', 'label' => 'Lista por defecto', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
        ['name' => 'is_active', 'label' => 'Activa', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
    ];

    protected function afterPersist($item, Request $request): void
    {
        if ($item->is_default) {
            PriceList::query()->whereKeyNot($item->id)->update(['is_default' => false]);
        }
    }
}
