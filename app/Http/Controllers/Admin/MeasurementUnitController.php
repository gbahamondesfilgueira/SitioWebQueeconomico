<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalogResources;
use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use Illuminate\Validation\Rule;

class MeasurementUnitController extends Controller
{
    use ManagesCatalogResources;

    protected string $modelClass = MeasurementUnit::class;
    protected string $tableName = 'measurement_units';
    protected string $viewPrefix = 'admin.catalog';
    protected string $routePrefix = 'admin.measurement-units';
    protected string $auditModule = 'measurement_units';
    protected string $title = 'Unidades de medida';
    protected string $singularLabel = 'Unidad de medida';
    protected bool $hasActiveToggle = false;
    protected bool $allowDelete = true;
    protected array $searchColumns = ['name', 'code', 'type'];
    protected array $columns = ['name' => 'Nombre', 'code' => 'Código', 'type' => 'Tipo'];
    protected array $typeOptions = [
        'weight' => 'Peso',
        'dimension' => 'Dimensión',
        'quantity' => 'Cantidad',
        'volume' => 'Volumen',
        'unit' => 'Unidad',
    ];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'code', 'label' => 'Código', 'type' => 'text', 'rules' => ['required', 'string', 'max:20'], 'unique' => true],
        ['name' => 'type', 'label' => 'Tipo', 'type' => 'select', 'rules' => ['required']],
    ];

    protected function rulesForField(array $field, $item = null): array
    {
        if ($field['name'] === 'type') {
            return ['required', Rule::in(MeasurementUnit::TYPES)];
        }

        $rules = $field['rules'] ?? [];

        if (($field['unique'] ?? false) === true) {
            $rules[] = Rule::unique($this->tableName, $field['name'])->ignore($item);
        }

        return $rules;
    }
}
