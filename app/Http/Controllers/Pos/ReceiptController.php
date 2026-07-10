<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\PosService;

class ReceiptController extends Controller
{
    public function show(Order $order, PosService $posService)
    {
        abort_unless($order->order_channel === 'pos', 404);
        return view('pos.receipt', [
            'order' => $posService->generateReceipt($order),
            'settings' => Setting::current(),
        ]);
    }
}
