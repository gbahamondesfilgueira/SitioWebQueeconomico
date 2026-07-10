<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingLabel;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShippingLabelController extends Controller
{
    public function index(): View { return view('admin.shipping.labels.index', ['labels' => ShippingLabel::query()->with('order')->latest()->paginate(15)]); }
    public function generate(Order $order): RedirectResponse
    {
        $label = ShippingLabel::query()->firstOrCreate(['order_id' => $order->id], ['shipping_carrier_id' => $order->shipment?->shipping_carrier_id, 'label_number' => 'LBL-'.$order->order_number, 'tracking_number' => 'TRK'.now()->format('YmdHis'), 'status' => 'generated', 'generated_by' => Auth::id(), 'generated_at' => now()]);
        AuditLogger::record('generated', 'shipping_labels', "Etiqueta generada {$label->label_number}");
        return redirect()->route('admin.shipping.labels.show', $label);
    }
    public function show(ShippingLabel $label): View { return view('admin.shipping.labels.show', compact('label')); }
    public function print(ShippingLabel $label): View { $label->update(['status' => 'printed', 'printed_at' => now()]); AuditLogger::record('printed', 'shipping_labels', "Etiqueta impresa {$label->label_number}"); return view('admin.shipping.labels.print', compact('label')); }
}
