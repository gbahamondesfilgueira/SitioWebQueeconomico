<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pos\Concerns\ResolvesPosTerminal;
use App\Models\CustomerProfile;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\PosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    use ResolvesPosTerminal;

    public function search(Request $request)
    {
        $q = (string) $request->query('q');
        return response()->json(CustomerProfile::query()
            ->where('is_active', true)
            ->where(fn ($query) => $query->where('first_name', 'like', "%{$q}%")->orWhere('last_name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->orWhere('rut', 'like', "%{$q}%"))
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email', 'phone', 'rut', 'reward_points', 'customer_type']));
    }

    public function quickCreate(Request $request, PosService $posService)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'rut' => ['nullable', 'string', 'max:30'],
        ]);

        $email = $data['email'] ?: 'cliente-pos-'.uniqid().'@local';
        $user = User::query()->create([
            'name' => trim($data['first_name'].' '.($data['last_name'] ?? '')),
            'email' => $email,
            'password' => Hash::make(str()->random(16)),
            'role_id' => Role::query()->where('slug', 'cliente')->value('id'),
            'is_active' => true,
        ]);
        $customer = CustomerProfile::query()->create([
            'user_id' => $user->id,
            'customer_type' => 'individual',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
            'email' => $email,
            'phone' => $data['phone'] ?? '-',
            'rut' => $data['rut'] ?? null,
            'newsletter' => false,
            'is_active' => true,
        ]);
        $cart = $posService->getActiveCart($this->terminal($request)->id, $request->user()->id);
        $cart->update(['customer_profile_id' => $customer->id]);
        AuditLogger::record('created', 'customers', "Cliente rápido POS creado {$customer->display_name}");

        return back()->with('success', 'Cliente asociado a la venta.');
    }
}
