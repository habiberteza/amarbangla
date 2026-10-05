@extends('admin.layout')

@section('title', 'Delivery Commissions - ' . $deliveryBoy->name)
@section('page-title', 'Commissions & Earnings: ' . $deliveryBoy->name . ' (super_admin/delivery_boy_commission/' . $deliveryBoy->id . ')')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('admin.delivery_boys.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Delivery Boy List</span>
        </a>

        <!-- Quick Switch Action Links -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.delivery_boys.orders', $deliveryBoy->id) }}"
               class="px-3 py-1.5 bg-sky-50 hover:bg-sky-600 text-sky-700 hover:text-white border border-sky-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa fa-boxes-packing text-[11px]"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('admin.delivery_boys.ledger', $deliveryBoy->id) }}"
               class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa fa-book text-[11px]"></i>
                <span>Ledger</span>
            </a>
            <a href="{{ route('admin.delivery_boys.edit', $deliveryBoy->id) }}"
               class="px-3 py-1.5 bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white border border-amber-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa fa-pen-to-square text-[11px]"></i>
                <span>Update</span>
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Delivered Orders</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5 font-mono">{{ $totalDeliveredCount }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Completed Deliveries</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Rate Per Delivery</p>
                <h3 class="text-xl font-extrabold text-blue-600 mt-0.5 font-mono">৳{{ number_format($ratePerDelivery, 2) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Standard Commission</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-tag"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Earned</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5 font-mono">৳{{ number_format($totalEarned, 2) }}</h3>
                <span class="text-[10px] text-indigo-600 font-medium">Accumulated Commission</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fa fa-hand-holding-dollar"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Current Balance</p>
                <h3 class="text-xl font-extrabold {{ $deliveryBoy->balance < 0 ? 'text-rose-600' : 'text-gray-800' }} mt-0.5 font-mono">
                    ৳{{ number_format($deliveryBoy->balance, 2) }}
                </h3>
                <span class="text-[10px] text-gray-500 font-medium">In Ledger Account</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                <i class="fa fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- Delivered Orders Table Generating Commission -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Completed Delivery Records & Commissions</h3>
            <span class="text-xs text-gray-400 font-mono">{{ $deliveredOrders->total() }} records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[10px] tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Delivered Date</th>
                        <th class="px-5 py-3.5">Order Number</th>
                        <th class="px-5 py-3.5">Customer & Destination</th>
                        <th class="px-5 py-3.5">Order Amount</th>
                        <th class="px-5 py-3.5">Commission Earned</th>
                        <th class="px-5 py-3.5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($deliveredOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-gray-500 font-mono text-xs">
                            {{ $order->updated_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-5 py-4 font-mono font-bold text-blue-600">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-gray-800">{{ $order->customer_name }}</div>
                            <div class="text-[11px] text-gray-400">{{ $order->upazila ?? $order->district }}</div>
                        </td>
                        <td class="px-5 py-4 font-mono font-bold text-gray-800">
                            ৳{{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-5 py-4 font-mono font-extrabold text-emerald-700">
                            + ৳{{ number_format($ratePerDelivery, 2) }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                Credited
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-xs">
                            No delivered orders found for {{ $deliveryBoy->name }} yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deliveredOrders->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $deliveredOrders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
