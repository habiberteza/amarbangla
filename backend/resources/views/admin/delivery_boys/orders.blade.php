@extends('admin.layout')

@section('title', 'Assigned Orders - ' . $deliveryBoy->name)
@section('page-title', 'Orders for Delivery Boy: ' . $deliveryBoy->name . ' (super_admin/delivery_boy_order/' . $deliveryBoy->id . ')')

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
            <a href="{{ route('admin.delivery_boys.commissions', $deliveryBoy->id) }}"
               class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa fa-percent text-[11px]"></i>
                <span>Commission</span>
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

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Delivery Boy Banner -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @if($deliveryBoy->image)
                <img src="{{ asset($deliveryBoy->image) }}" alt="{{ $deliveryBoy->name }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200 shadow-sm">
            @else
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white font-extrabold text-xl flex items-center justify-center shadow-sm">
                    {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 class="text-base font-extrabold text-gray-900">{{ $deliveryBoy->name }}</h2>
                <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5 font-mono">
                    <span><i class="fa fa-phone text-[10px] text-blue-500 mr-1"></i>{{ $deliveryBoy->phone }}</span>
                    <span>&bull;</span>
                    <span class="text-gray-700 font-sans"><i class="fa fa-location-dot text-[10px] text-emerald-500 mr-1"></i>{{ $deliveryBoy->upazila ?? $deliveryBoy->district ?? 'All City' }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-sky-50 border border-sky-100 rounded-xl px-4 py-2 text-center">
                <span class="text-[10px] uppercase font-bold text-sky-600 tracking-wider block">Assigned Orders</span>
                <span class="text-lg font-extrabold text-sky-800 font-mono">{{ $assignedOrders->total() }}</span>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2 text-center">
                <span class="text-[10px] uppercase font-bold text-emerald-600 tracking-wider block">Delivered</span>
                <span class="text-lg font-extrabold text-emerald-800 font-mono">{{ \App\Models\Order::where('delivery_boy_id', $deliveryBoy->id)->where('order_status', 'delivered')->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Assign New Order Section -->
    @if($availableOrders->count() > 0)
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-3">
        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
            <i class="fa fa-plus-circle text-blue-600 text-xs"></i>
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Assign Pending Order to {{ $deliveryBoy->name }}</h4>
        </div>
        <form action="{{ route('admin.delivery_boys.assign_order', $deliveryBoy->id) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
            @csrf
            <select name="order_id" required class="flex-1 w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <option value="">-- Select an unassigned order to dispatch --</option>
                @foreach($availableOrders as $avail)
                    <option value="{{ $avail->id }}">
                        Order #{{ $avail->order_number }} - ৳{{ number_format($avail->total_amount, 2) }} ({{ $avail->customer_name }} - {{ $avail->upazila ?? $avail->district ?? 'Address' }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa fa-paper-plane"></i>
                <span>Assign Order</span>
            </button>
        </form>
    </div>
    @endif

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Assigned Parcels & Orders</h3>
            <span class="text-xs text-gray-400 font-mono">{{ $assignedOrders->total() }} records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[10px] tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Order #</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Delivery Address</th>
                        <th class="px-5 py-3.5">Amount / Method</th>
                        <th class="px-5 py-3.5">Order Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assignedOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-mono font-bold text-blue-600">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                #{{ $order->order_number }}
                            </a>
                            <div class="text-[10px] text-gray-400 font-sans mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-gray-800">{{ $order->customer_name }}</div>
                            <a href="tel:{{ $order->customer_phone }}" class="text-[11px] text-gray-500 hover:text-blue-600 font-mono">
                                {{ $order->customer_phone }}
                            </a>
                        </td>
                        <td class="px-5 py-4 max-w-xs">
                            <div class="text-gray-700 text-xs">{{ $order->shipping_address }}</div>
                            <div class="text-[10px] text-gray-400">{{ $order->upazila ?? $order->district }}</div>
                        </td>
                        <td class="px-5 py-4 font-mono font-bold text-gray-800">
                            ৳{{ number_format($order->total_amount, 2) }}
                            <div class="text-[10px] text-gray-400 uppercase font-normal font-sans">{{ $order->payment_method }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $order->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' :
                                   ($order->order_status === 'processing' ? 'bg-sky-100 text-sky-800' :
                                   ($order->order_status === 'shipped' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800')) }}">
                                {{ $order->order_status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1">
                                <i class="fa fa-eye text-[10px]"></i>
                                <span>View</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400 text-xs">
                            No orders assigned to {{ $deliveryBoy->name }} yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assignedOrders->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $assignedOrders->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
