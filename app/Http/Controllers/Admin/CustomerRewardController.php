<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Services\CustomerCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerRewardController extends Controller
{
    public function store(Request $request, CustomerProfile $customer, CustomerCrmService $service): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['earn', 'redeem', 'adjustment'])],
            'points' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'integer'],
        ]);

        $service->addRewardTransaction($customer, $data);

        return back()->with('success', 'Movimiento de puntos registrado.');
    }
}
