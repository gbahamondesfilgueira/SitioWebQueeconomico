<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderShipment;
use App\Models\ShippingTrackingEvent;
use App\Services\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingTrackingController extends Controller
{
    public function index(): View { return view('admin.shipping.tracking.index', ['events' => ShippingTrackingEvent::query()->with('shipment.order')->latest()->paginate(20), 'shipments' => OrderShipment::query()->with('order')->latest()->limit(50)->get()]); }
    public function store(Request $request, ShippingService $shipping): RedirectResponse
    {
        $data = $request->validate(['order_shipment_id' => ['required', 'exists:order_shipments,id'], 'status' => ['required'], 'description' => ['nullable'], 'location' => ['nullable']]);
        $shipping->registerTracking(OrderShipment::query()->findOrFail($data['order_shipment_id']), $data['status'], $data['description'] ?? null, $data['location'] ?? null);
        return back()->with('success', 'Tracking registrado.');
    }
}
