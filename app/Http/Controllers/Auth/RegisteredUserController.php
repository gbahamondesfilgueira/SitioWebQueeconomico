<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:50'],
            'rut' => ['nullable', 'string', 'max:30'],
            'region' => ['required', 'string', 'max:100'],
            'commune' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $clientRole = Role::query()->firstOrCreate([
            'slug' => 'cliente',
        ], [
            'name' => 'Cliente',
            'description' => 'Acceso a cuenta cliente.',
        ]);

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $clientRole->id,
            'password' => Hash::make(Str::password(32)),
            'auth_provider' => 'email',
            'is_active' => true,
            'phone' => $request->phone,
        ]);

        $firstName = strtok($request->name, ' ') ?: $request->name;
        $lastName = trim(str_replace($firstName, '', $request->name)) ?: '-';
        $profile = $user->customerProfile()->create([
            'customer_type' => 'individual',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'rut' => $request->rut,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        $address = [
            'contact_name' => $request->name,
            'phone' => $request->phone,
            'country' => 'Chile',
            'region' => $request->region,
            'commune' => $request->commune,
            'city' => $request->city,
            'street' => $request->street,
            'number' => $request->number,
            'apartment' => $request->apartment,
            'reference' => $request->reference,
            'address_label' => 'main',
            'is_default' => true,
            'is_active' => true,
        ];

        $profile->addresses()->create($address + ['address_type' => 'shipping']);
        $profile->addresses()->create($address + ['address_type' => 'billing']);

        event(new Registered($user));

        Password::sendResetLink($request->only('email'));
        AuditLogger::record('register_password_link_sent', 'auth', 'Cliente registrado; enlace para crear password enviado.', $request, $user->id);

        return redirect()->route('login')->with('status', 'Cuenta creada. Te enviamos un correo con el enlace para crear tu password.');
    }
}
