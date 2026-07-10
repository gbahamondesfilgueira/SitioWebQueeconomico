<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()
                ->with('role')
                ->latest()
                ->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::query()->create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLogger::record('created', 'users', "Usuario creado: {$user->email}");

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('role'),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:50'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $targetRole = Role::query()->findOrFail($validated['role_id']);
        $requestedActive = $request->boolean('is_active');

        if ($user->isSuperAdmin() && $targetRole->slug !== 'super-admin' && $this->isLastSuperAdmin($user)) {
            return back()
                ->withErrors(['role_id' => 'No se puede quitar el rol al último Super Admin.'])
                ->withInput();
        }

        if ($user->isSuperAdmin() && ! $requestedActive && $this->isLastSuperAdmin($user)) {
            return back()
                ->withErrors(['is_active' => 'No se puede desactivar el último Super Admin.'])
                ->withInput();
        }

        if ($request->user()->is($user) && $user->isSuperAdmin() && ! $requestedActive) {
            return back()
                ->withErrors(['is_active' => 'No puedes desactivar tu propio usuario Super Admin.'])
                ->withInput();
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
            'is_active' => $requestedActive,
        ]);

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AuditLogger::record('updated', 'users', "Usuario editado: {$user->email}");

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user) && $user->isSuperAdmin()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario Super Admin.');
        }

        if ($user->isSuperAdmin() && $user->is_active && $this->isLastSuperAdmin($user)) {
            return back()->with('error', 'No se puede desactivar el último Super Admin.');
        }

        $user->forceFill(['is_active' => ! $user->is_active])->save();

        AuditLogger::record(
            $user->is_active ? 'activated' : 'deactivated',
            'users',
            ($user->is_active ? 'Usuario activado: ' : 'Usuario desactivado: ').$user->email,
        );

        return back()->with('success', 'Estado del usuario actualizado.');
    }

    private function isLastSuperAdmin(User $user): bool
    {
        return User::query()
            ->whereHas('role', fn ($query) => $query->where('slug', 'super-admin'))
            ->where('is_active', true)
            ->whereKeyNot($user->getKey())
            ->doesntExist();
    }
}
