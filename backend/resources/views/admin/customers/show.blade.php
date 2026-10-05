@extends('admin.layout')

@section('title', 'Customer Profile - ' . $customer->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Customer 360 Profile: ' . $customer->name)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">

    <!-- Top Action & Navigation Bar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="p-2 text-gray-400 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition" title="Back to Customers">
                <i class="fa fa-arrow-left"></i>
            </a>
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-base flex-shrink-0 shadow-sm">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h2>
                    @if($customer->customerType)
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $customer->customerType->name }}
                        </span>
                    @endif
                    <span class="capitalize text-[10px] font-bold px-2 py-0.5 rounded
                        {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $customer->status }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">
                    Member since {{ $customer->created_at->format('d M Y') }} ({{ $customer->created_at->diffForHumans() }})
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                <i class="fa fa-pen-to-square"></i>
                <span>Edit Profile</span>
            </a>

            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                  onsubmit="return confirm('Permanently delete customer {{ $customer->name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl border border-rose-200 transition" title="Delete Customer">
                    <i class="fa fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOrders }}</h3>
                <span class="text-[11px] text-gray-400">All-time purchases</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa fa-shopping-bag"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Spent</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">৳{{ number_format($totalSpent, 2) }}</h3>
                <span class="text-[11px] text-emerald-600 font-medium">Completed payments</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Wallet Balance</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">৳{{ number_format($customer->balance, 2) }}</h3>
                <span class="text-[11px] text-purple-600 font-medium">Store Credit</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa fa-coins"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Discount Tier</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $customer->customerType ? $customer->customerType->discount_percentage . '%' : '0%' }}
                </h3>
                <span class="text-[11px] text-blue-600 font-medium">
                    {{ $customer->customerType ? $customer->customerType->name : 'General' }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa fa-percent"></i>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1: Customer Profile Overview -->
        <div class="space-y-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Contact Information</h3>

                <div class="text-xs space-y-3">
                    <div>
                        <span class="text-gray-400 block text-[11px]">Primary Phone</span>
                        <a href="tel:{{ $customer->phone }}" class="font-bold text-gray-900 text-sm hover:text-blue-600 font-mono">
                            {{ $customer->phone }}
                        </a>
                    </div>

                    @if($customer->email)
                    <div>
                        <span class="text-gray-400 block text-[11px]">Email Address</span>
                        <a href="mailto:{{ $customer->email }}" class="font-medium text-gray-800 hover:text-blue-600">
                            {{ $customer->email }}
                        </a>
                    </div>
                    @endif

                    <div>
                        <span class="text-gray-400 block text-[11px]">Default Shipping Destination</span>
                        <p class="font-medium text-gray-800 leading-relaxed mt-0.5">
                            {{ $customer->address ?? 'No street address provided' }}
                        </p>
                        <p class="text-gray-500 text-[11px] mt-0.5">
                            {{ $customer->upazila ? $customer->upazila . ', ' : '' }}{{ $customer->district ? $customer->district . ', ' : '' }}{{ $customer->division }}
                        </p>
                    </div>

                    @if($customer->notes)
                    <div class="p-3 bg-amber-50 border border-amber-200/60 rounded-xl text-amber-900 text-[11px]">
                        <strong>Notes:</strong> {{ $customer->notes }}
                    </div>
                    @endif

                    @if($customer->user)
                    <div class="pt-3 border-t border-gray-100 text-[11px] text-gray-500 flex items-center justify-between">
                        <span>Web Account Linked:</span>
                        <span class="font-mono font-semibold text-emerald-700"><i class="fa fa-circle-check"></i> User #{{ $customer->user->id }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Column 2 & 3: Order History -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Purchase History</h3>
                    <p class="text-xs text-gray-400 mt-0.5">All orders made by this customer</p>
                </div>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 font-bold text-xs rounded-lg">
                    {{ $orders->total() }} Orders
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3.5">Order #</th>
                            <th class="px-5 py-3.5">Date</th>
                            <th class="px-5 py-3.5">Items</th>
                            <th class="px-5 py-3.5">Total</th>
                            <th class="px-5 py-3.5">Payment</th>
                            <th class="px-5 py-3.5">Order Status</th>
                            <th class="px-5 py-3.5 text-right">View</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4 font-bold text-blue-600 font-mono">
                                <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-[11px]">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-700">
                                {{ $order->items->count() }} items
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-900">
                                ৳{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="uppercase text-[10px] font-bold px-2 py-0.5 rounded {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $order->payment_method }} • {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="capitalize text-[10px] font-bold px-2.5 py-1 rounded
                                    {{ $order->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' :
                                       ($order->order_status === 'pending' ? 'bg-blue-100 text-blue-800' :
                                       ($order->order_status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')) }}">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                    Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                No purchases recorded for this customer yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
            @endif
        </div>

    </div>

</div>
@endsection
