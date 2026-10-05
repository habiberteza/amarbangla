<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorPortalController extends Controller
{
    private function getShop(): ?Shop
    {
        $user = Auth::user();
        if (!$user) return null;
        return Shop::where('user_id', $user->id)->first() ?? Shop::first();
    }

    public function dashboard()
    {
        $user = Auth::user();
        $shop = $this->getShop();

        $totalProducts = Product::where('shop_id', $shop->id)->count();
        $totalOrders = OrderItem::where('shop_id', $shop->id)->count();
        $totalSales = OrderItem::where('shop_id', $shop->id)
            ->where('status', '!=', 'cancelled')
            ->sum('subtotal');

        $commissionPayable = ($totalSales * ($shop->commission_rate ?? 5.00)) / 100;

        $recentOrders = OrderItem::where('shop_id', $shop->id)
            ->with(['order', 'product'])
            ->latest()
            ->take(8)
            ->get();

        return view('vendor.dashboard', compact(
            'user',
            'shop',
            'totalProducts',
            'totalOrders',
            'totalSales',
            'commissionPayable',
            'recentOrders'
        ));
    }

    public function products()
    {
        $shop = $this->getShop();
        $products = Product::where('shop_id', $shop->id)
            ->with('category')
            ->latest()
            ->paginate(15);

        return view('vendor.products', compact('shop', 'products'));
    }

    public function createProduct()
    {
        $shop = $this->getShop();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('vendor.product_create', compact('shop', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $shop = $this->getShop();

        $validated = $request->validate([
            'name'           => 'required|string|max:200',
            'category_id'    => 'required|exists:categories,id',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'is_express'     => 'nullable|boolean',
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        $validated['is_active'] = true;
        $validated['is_express'] = $request->has('is_express');

        Product::create($validated);

        return redirect()->route('vendor.products')->with('success', 'পণ্য সফলভাবে যুক্ত হয়েছে!');
    }

    public function settings()
    {
        $shop = $this->getShop();
        return view('vendor.settings', compact('shop'));
    }

    public function updateSettings(Request $request)
    {
        $shop = $this->getShop();

        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'phone'         => 'required|string|max:20',
            'shop_type'     => 'required|in:local,regular',
            'address'       => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:1000',
        ]);

        $shop->update($validated);

        return back()->with('success', 'শপের সেটিংস সফলভাবে আপডেট করা হয়েছে!');
    }
}
