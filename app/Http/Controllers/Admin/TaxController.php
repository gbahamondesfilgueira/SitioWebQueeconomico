<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalogResources;
use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    use ManagesCatalogResources;

    protected string $modelClass = Tax::class;
    protected string $tableName = 'taxes';
    protected string $viewPrefix = 'admin.catalog';
    protected string $routePrefix = 'admin.taxes';
    protected string $auditModule = 'taxes';
    protected string $title = 'Impuestos';
    protected string $singularLabel = 'Impuesto';
    protected bool $hasActiveToggle = true;
    protected bool $allowDelete = true;
    protected array $searchColumns = ['name'];
    protected array $columns = ['name' => 'Nombre', 'percentage' => 'Porcentaje', 'is_default' => 'Por defecto'];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'percentage', 'label' => 'Porcentaje', 'type' => 'number', 'rules' => ['required', 'numeric', 'min:0', 'max:100']],
        ['name' => 'is_default', 'label' => 'Impuesto por defecto', 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']],
    ];

    protected function prepareData(Request $request, array $data, $item = null): array
    {
        $data['is_active'] = $request->boolean('is_active');
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            Tax::query()
                ->when($item, fn ($query) => $query->whereKeyNot($item->id))
                ->update(['is_default' => false]);
        }

        return $data;
    }
}
