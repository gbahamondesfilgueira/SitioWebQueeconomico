<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\Setting;
use App\Models\StockAdjustment;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\SystemHealthCheck;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalUsers' => User::query()->count(),
            'activeUsers' => User::query()->where('is_active', true)->count(),
            'inactiveUsers' => User::query()->where('is_active', false)->count(),
            'setting' => Setting::current(),
            'auditLogs' => AuditLog::query()
                ->with('user')
                ->latest('created_at')
                ->limit(8)
                ->get(),
            'stockedProducts' => StockLevel::query()->distinct('product_id')->count('product_id'),
            'lowStockProducts' => StockLevel::query()->whereRaw('(physical_stock - reserved_stock) <= minimum_stock')->count(),
            'activeWarehouses' => Warehouse::query()->where('is_active', true)->count(),
            'latestStockMovements' => StockMovement::query()->with(['product', 'warehouse'])->latest('created_at')->limit(5)->get(),
            'pendingAdjustments' => StockAdjustment::query()->where('status', 'pending')->count(),
            'inTransitTransfers' => StockTransfer::query()->where('status', 'in_transit')->count(),
            'registeredCustomers' => CustomerProfile::query()->count(),
            'activeCustomers' => CustomerProfile::query()->where('is_active', true)->count(),
            'newCustomers' => CustomerProfile::query()->where('created_at', '>=', now()->subDays(30))->count(),
            'vipCustomers' => CustomerProfile::query()->whereHas('tags', fn ($query) => $query->where('slug', 'vip'))->count(),
            'companyCustomers' => CustomerProfile::query()->where('customer_type', 'company')->count(),
            'newsletterCustomers' => CustomerProfile::query()->where('newsletter', true)->count(),
            'inactiveCustomers' => CustomerProfile::query()->where('is_active', false)->count(),
            'pendingOrders' => Order::query()->where('order_status', 'pending')->count(),
            'newOrders' => Order::query()->where('created_at', '>=', now()->subDay())->whereNotIn('order_status', ['cancelled', 'refunded'])->count(),
            'latestOrders' => Order::query()->latest()->limit(6)->get(),
            'paidOrders' => Order::query()->where('payment_status', 'paid')->count(),
            'preparingOrders' => Order::query()->where('fulfillment_status', 'picking')->count(),
            'readyOrders' => Order::query()->where('fulfillment_status', 'ready')->count(),
            'cancelledOrders' => Order::query()->where('order_status', 'cancelled')->count(),
            'todaySales' => Order::query()->whereDate('created_at', today())->whereNotIn('order_status', ['cancelled', 'refunded'])->sum('grand_total'),
            'monthSales' => Order::query()->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->whereNotIn('order_status', ['cancelled', 'refunded'])->sum('grand_total'),
            'stockIntegrity' => SystemHealthCheck::query()->where('check_name', 'Conciliación de stock')->latest('checked_at')->first(),
        ]);
    }
}
