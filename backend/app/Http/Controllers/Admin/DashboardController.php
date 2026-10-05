<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_shops' => Shop::count(),
            'active_shops' => Shop::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'total_sales' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_users' => User::count(),
        ];

        $recentOrders = Order::with(['items.shop'])->latest()->take(6)->get();
        $recentShops = Shop::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentShops'));
    }
}
