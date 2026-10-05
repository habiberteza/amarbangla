@extends('admin.layout')

@section('title', 'Dashboard - Amar Bangla Super Admin')
@section('page-title', 'Overview & Performance Analytics')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Shops</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_shops'] }}</h3>
                <span class="inline-block mt-1 text-[11px] text-emerald-600 font-medium">
                    <i class="fa fa-arrow-up"></i> {{ $stats['active_shops'] }} Active Outlets
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa fa-store"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Products</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_products'] }}</h3>
                <span class="inline-block mt-1 text-[11px] text-blue-600 font-medium">In Catalog</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_orders'] }}</h3>
                <span class="inline-block mt-1 text-[11px] text-amber-600 font-medium">{{ $stats['pending_orders'] }} Pending Review</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa fa-shopping-bag"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Sales</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">৳{{ number_format($stats['total_sales'], 2) }}</h3>
                <span class="inline-block mt-1 text-[11px] text-purple-600 font-medium">Completed Payments</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- Quick Shortcuts & Recent Data -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-base">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">View All Orders &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Order #</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Payment</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 font-bold text-gray-900">{{ $order->order_number }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-gray-800">{{ $order->customer_name }}</div>
                                <div class="text-[11px] text-gray-400">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gray-800">৳{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="uppercase text-[10px] font-bold px-2 py-0.5 rounded {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->payment_method }} ({{ $order->payment_status }})
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="capitalize text-[10px] font-bold px-2 py-0.5 rounded {{ $order->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : ($order->order_status === 'pending' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 font-semibold">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Registered Outlets (1 col) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-base">Top Outlets</h2>
                <a href="{{ route('admin.shops.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">All Shops &rarr;</a>
            </div>
            <div class="p-5 space-y-4 flex-1">
                @foreach($recentShops as $shop)
                <div class="flex items-center gap-3.5 p-3 rounded-lg border border-gray-100 hover:border-gray-200 transition">
                    <img src="{{ $shop->logo ?? 'https://via.placeholder.com/80' }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 border border-gray-200 flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-gray-800 text-xs truncate">{{ $shop->name }}</h4>
                            <span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded {{ $shop->type === 'express' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $shop->type }}
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-400 truncate">{{ $shop->address ?? 'Bangladesh' }}</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">{{ $shop->commission_rate }}%</span>
                </div>
                @endforeach
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
                <a href="{{ route('admin.shops.create') }}" class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-800">
                    <i class="fa fa-plus"></i> Register New Vendor Shop
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
