<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalogResources;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;

class SupplierController extends Controller
{
    use ManagesCatalogResources;

    protected string $modelClass = Supplier::class;
    protected string $tableName = 'suppliers';
    protected string $viewPrefix = 'admin.catalog';
    protected string $routePrefix = 'admin.suppliers';
    protected string $auditModule = 'suppliers';
    protected string $title = 'Proveedores';
    protected string $singularLabel = 'Proveedor';
    protected bool $hasActiveToggle = true;
    protected bool $allowDelete = false;
    protected array $searchColumns = ['name', 'rut', 'contact_name', 'email', 'phone', 'city', 'country'];
    protected array $columns = [
        'name' => 'Nombre',
        'rut' => 'RUT',
        'contact_name' => 'Contacto',
        'email' => 'Email',
        'phone' => 'Teléfono',
        'city' => 'Ciudad',
        'country' => 'País',
    ];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'rut', 'label' => 'RUT', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:50']],
        ['name' => 'contact_name', 'label' => 'Nombre de contacto', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'rules' => ['nullable', 'email', 'max:255']],
        ['name' => 'phone', 'label' => 'Teléfono', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:50']],
        ['name' => 'address', 'label' => 'Dirección', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        ['name' => 'city', 'label' => 'Ciudad', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:100']],
        ['name' => 'country', 'label' => 'País', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:100']],
        ['name' => 'notes', 'label' => 'Notas', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];

    protected function applyIndexQuery($query): Builder
    {
        return $query->orderBy('name');
    }
}
