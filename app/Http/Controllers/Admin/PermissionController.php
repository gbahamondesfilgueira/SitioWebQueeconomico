<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.security.permissions', [
            'permissions' => Permission::query()->orderBy('module')->orderBy('name')->get(),
            'roles' => Role::query()->with('permissions')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug'],
            'module' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Permission::query()->create($validated);
        AuditLogger::record('created', 'permissions', 'Permiso creado.');

        return back()->with('success', 'Permiso creado correctamente.');
    }

    public function syncRole(Request $request, Role $role)
    {
        $validated = $request->validate(['permissions' => ['array'], 'permissions.*' => ['integer', 'exists:permissions,id']]);
        $role->permissions()->sync($validated['permissions'] ?? []);
        AuditLogger::record('assigned', 'permissions', "Permisos asignados al rol {$role->name}.");

        return back()->with('success', 'Permisos actualizados.');
    }
}
