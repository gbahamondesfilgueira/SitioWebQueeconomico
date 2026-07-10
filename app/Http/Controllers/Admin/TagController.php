<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesCatalogResources;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    use ManagesCatalogResources;

    protected string $modelClass = Tag::class;
    protected string $tableName = 'tags';
    protected string $viewPrefix = 'admin.catalog';
    protected string $routePrefix = 'admin.tags';
    protected string $auditModule = 'tags';
    protected string $title = 'Etiquetas';
    protected string $singularLabel = 'Etiqueta';
    protected bool $hasActiveToggle = true;
    protected bool $allowDelete = true;
    protected array $searchColumns = ['name', 'slug', 'description'];
    protected array $columns = ['name' => 'Nombre', 'slug' => 'Slug', 'description' => 'Descripción'];
    protected array $fields = [
        ['name' => 'name', 'label' => 'Nombre', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
        ['name' => 'slug', 'label' => 'Slug', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255'], 'unique' => true],
        ['name' => 'description', 'label' => 'Descripción', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
    ];
}
