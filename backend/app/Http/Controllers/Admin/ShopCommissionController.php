<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopCommissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::with('vendor');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $shops = $query->orderBy('name')->paginate(15)->withQueryString();
        $globalCommission = Setting::get('platform_commission', '5.00');

        $unpaidOrders = \App\Models\Order::whereIn('payment_status', ['pending', 'unpaid'])
            ->latest()
            ->take(20)
            ->get();

        $paidOrders = \App\Models\Order::where('payment_status', 'paid')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.shops_commission.index', compact('shops', 'globalCommission', 'unpaidOrders', 'paidOrders'));
    }

    public function updateGlobal(Request $request)
    {
        $validated = $request->validate([
            'platform_commission' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('platform_commission', $validated['platform_commission']);

        return back()->with('success', 'Global default commission rate updated successfully.');
    }

    public function updateShop(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $shop->update(['commission_rate' => $validated['commission_rate']]);

        return back()->with('success', "Commission rate updated for {$shop->name}.");
    }
}
