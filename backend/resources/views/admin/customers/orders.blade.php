@extends('admin.layout')

@section('title', 'Customer Orders - ' . $customer->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Customer Order List (super_admin/customer_order_list/' . ($customer->customer_id ?? $customer->id) . ')')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Customer Directory</span>
                </a>
                <span>/</span>
                <a href="{{ route('admin.customers.show', $customer) }}" class="text-gray-700 hover:text-blue-600 font-semibold">
                    {{ $customer->name }}
                </a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Order History</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-shopping-bag text-emerald-600"></i>
                <span>Orders for {{ $customer->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Track and inspect all historical purchases and invoices placed by this customer</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.customers.index') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa fa-arrow-left"></i>
                <span>Back to Customer List</span>
            </a>
            <a href="{{ route('admin.customers.show', $customer) }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                <i class="fa fa-user"></i>
                <span>Customer Profile</span>
            </a>
        </div>
    </div>

    <!-- Customer Summary Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-extrabold text-xl shadow-md flex-shrink-0">
                {{ strtoupper(substr($customer->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-lg font-black text-gray-900">{{ $customer->name }}</h3>
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                    <span class="text-xs text-gray-400 font-mono">#{{ $customer->customer_id ?? $customer->id }}</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500 mt-1 flex-wrap">
                    <a href="tel:{{ $customer->phone }}" class="hover:text-blue-600 flex items-center gap-1 font-semibold text-gray-800">
                        <i class="fa fa-phone text-blue-500 text-[10px]"></i>
                        <span>{{ $customer->phone }}</span>
                    </a>
                    @if($customer->email)
                        <span class="flex items-center gap-1">
                            <i class="fa fa-envelope text-gray-400 text-[10px]"></i>
                            <span>{{ $customer->email }}</span>
                        </span>
                    @endif
                    @if($customer->resolved_upazila || $customer->resolved_district)
                        <span class="flex items-center gap-1 text-gray-600">
                            <i class="fa fa-location-dot text-rose-500 text-[10px]"></i>
                            <span>{{ implode(', ', array_filter([$customer->resolved_upazila, $customer->resolved_district, $customer->resolved_division])) }}</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 border-t md:border-t-0 pt-3 md:pt-0">
            <div class="text-right">
                <span class="text-[10px] font-bold uppercase text-gray-400 block tracking-wider">Wallet Balance</span>
                <span class="text-lg font-black text-blue-700 font-mono">৳ {{ number_format($customer->balance, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Orders</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ number_format($totalInvoicesCount) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Invoices Generated</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-boxes-packing"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Spent</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5 font-mono">৳ {{ number_format($totalInvoicesAmount, 2) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Gross Purchase Value</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-bangladeshi-taka-sign"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Delivered Orders</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5">{{ number_format($deliveredCount) }}</h3>
                <span class="text-[10px] text-indigo-600 font-medium">Completed Purchases</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Due</p>
                <h3 class="text-xl font-extrabold {{ $totalInvoicesDue > 0 ? 'text-rose-600' : 'text-gray-800' }} mt-0.5 font-mono">
                    ৳ {{ number_format($totalInvoicesDue, 2) }}
                </h3>
                <span class="text-[10px] {{ $totalInvoicesDue > 0 ? 'text-rose-600' : 'text-gray-500' }} font-medium">Outstanding Balance</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                <i class="fa fa-hand-holding-dollar"></i>
            </div>
        </div>
    </div>

    <!-- Orders Table Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa fa-list-check text-blue-600"></i>
                <h4 class="text-sm font-bold text-gray-800">Customer Order & Invoice History</h4>
            </div>
            <span class="text-xs text-gray-500">
                Found <strong class="text-gray-800">{{ $invoices->total() }}</strong> total invoices
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3.5 w-14 text-center">No</th>
                        <th class="px-5 py-3.5">Invoice #</th>
                        <th class="px-5 py-3.5">Order Date</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-5 py-3.5 text-right">Due</th>
                        <th class="px-5 py-3.5 text-center">Delivery Type</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invoices as $inv)
                    <tr class="hover:bg-gray-50/80 transition">
                        <!-- No -->
                        <td class="px-4 py-3.5 text-center font-bold text-gray-400 font-mono">
                            {{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                        </td>

                        <!-- Invoice ID -->
                        <td class="px-5 py-3.5 font-bold font-mono text-gray-900">
                            <a href="{{ url('super_admin/order_invoice/' . $inv->invoice_id) }}" target="_blank" class="hover:text-blue-600 flex items-center gap-1">
                                <i class="fa fa-file-invoice text-blue-500"></i>
                                <span>#INV-{{ $inv->invoice_id }}</span>
                            </a>
                        </td>

                        <!-- Date -->
                        <td class="px-5 py-3.5 text-gray-600">
                            @if($inv->createdDtm && $inv->createdDtm !== '0000-00-00 00:00:00')
                                {{ date('d M Y, h:i A', strtotime($inv->createdDtm)) }}
                            @elseif($inv->timestamp)
                                {{ date('d M Y, h:i A', strtotime($inv->timestamp)) }}
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>

                        <!-- Amount -->
                        <td class="px-5 py-3.5 text-right font-mono font-bold text-gray-900">
                            ৳ {{ number_format($inv->final_amount ?: $inv->amount, 2) }}
                        </td>

                        <!-- Due -->
                        <td class="px-5 py-3.5 text-right font-mono font-bold {{ $inv->due > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            ৳ {{ number_format($inv->due, 2) }}
                        </td>

                        <!-- Delivery Type -->
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $inv->delivery_type ?: 'General' }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $inv->status_badge_class }}">
                                {{ $inv->status_label }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="px-5 py-3.5 text-right">
                            <a href="{{ url('super_admin/order_invoice/' . $inv->invoice_id) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs shadow-xs transition"
                               title="View Order Invoice">
                                <i class="fa fa-eye"></i>
                                <span>Invoice</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa fa-cart-shopping"></i>
                            </div>
                            <p class="font-bold text-gray-600 text-sm">No orders found for this customer</p>
                            <p class="text-xs text-gray-400 mt-1">This customer has not placed any orders or invoices yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-xs text-gray-500">
                Showing {{ $invoices->firstItem() ?? 0 }} to {{ $invoices->lastItem() ?? 0 }} of {{ $invoices->total() }} orders
            </div>
            <div>
                {{ $invoices->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
