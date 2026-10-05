<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerPortalController extends Controller
{
    public function dashboard()
    {
        $seller = Auth::user();

        $commissionRate = $seller->commission_rate ?? 3.00;
        $balance = $seller->balance ?? 0.00;

        // Total referred sales
        $totalSales = OrderItem::where('status', 'delivered')->sum('subtotal') * 0.05; // sample calculation
        $totalEarned = $balance;

        $products = Product::where('is_active', true)
            ->with(['shop', 'category'])
            ->latest()
            ->take(8)
            ->get();

        return view('seller.dashboard', compact(
            'seller',
            'commissionRate',
            'balance',
            'totalEarned',
            'products'
        ));
    }

    public function products(Request $request)
    {
        $seller = Auth::user();

        $query = Product::where('is_active', true)->with(['shop', 'category']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->paginate(20)->withQueryString();

        return view('seller.products', compact('seller', 'products'));
    }

    public function ledger()
    {
        $seller = Auth::user();

        $transactions = Ledger::where('user_id', $seller->id)
            ->latest()
            ->paginate(15);

        return view('seller.ledger', compact('seller', 'transactions'));
    }
}
