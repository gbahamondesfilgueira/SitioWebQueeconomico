<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalogResources;
use App\Http\Controllers\Controller;
use App\Models\OriginCountry;

class OriginCountryController extends Controller
{
    use ManagesCatalogResources;

    protected string $modelClass = OriginCountry::class;
    protected string $tableName = 'origin_countries';
    protected string $viewPrefix = 'admin.catalog';
    protected string $routePrefix = 'admin.origin-countries';
    protected string $auditModule = 'origin_countries';
    protected string $title = 'Países de origen';
    protected string $singularLabel = 'País de origen';
    protected bool $hasActiveToggle = true;
    protected bool $allowDelete = true;
    protected array $searchColumns = ['name', 'iso_code'];
    protected array $columns = ['name' => 'Nombre', 'iso_code' => 'Código ISO'];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'iso_code', 'label' => 'Código ISO', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:3']],
    ];
}
