<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Services\CustomerCrmService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerNoteController extends Controller
{
    public function store(Request $request, CustomerProfile $customer, CustomerCrmService $service): RedirectResponse
    {
        $data = $request->validate([
            'note' => ['required', 'string'],
            'is_private' => ['nullable', 'boolean'],
        ]);

        $data['is_private'] = $request->boolean('is_private', true);
        $service->addNote($customer, $data);

        return back()->with('success', 'Nota guardada.');
    }
}
