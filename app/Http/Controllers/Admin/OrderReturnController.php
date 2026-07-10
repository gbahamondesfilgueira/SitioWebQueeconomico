<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderReturnController extends Controller
{
    public function index(): View
    {
        return view('admin.order_returns.index', ['returns' => OrderReturn::query()->with('order')->latest()->paginate(15)]);
    }

    public function show(OrderReturn $return): View
    {
        return view('admin.order_returns.show', ['return' => $return->load('order', 'items.orderItem')]);
    }

    public function approve(OrderReturn $return, OrderService $service): RedirectResponse
    {
        $service->approveReturn($return);
        return back()->with('success', 'Devolución aprobada.');
    }

    public function receive(OrderReturn $return, OrderService $service): RedirectResponse
    {
        $service->receiveReturn($return);
        return back()->with('success', 'Devolución recibida.');
    }
}
