<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerDocumentRequest;
use App\Models\CustomerDocument;
use App\Models\CustomerProfile;
use App\Services\CustomerCrmService;
use Illuminate\Http\RedirectResponse;

class CustomerDocumentController extends Controller
{
    public function store(CustomerDocumentRequest $request, CustomerProfile $customer, CustomerCrmService $service): RedirectResponse
    {
        $service->storeDocument($customer, $request->file('document'), $request->validated());

        return back()->with('success', 'Documento cargado.');
    }

    public function destroy(CustomerProfile $customer, CustomerDocument $document, CustomerCrmService $service): RedirectResponse
    {
        abort_unless($document->customer_profile_id === $customer->id, 404);
        $service->deleteDocument($document);

        return back()->with('success', 'Documento eliminado.');
    }
}
