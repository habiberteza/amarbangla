@extends('admin.layout')

@section('title', 'Seller Orders - ' . $vendor->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Seller Orders (super_admin/sellers_order/' . $vendor->id . ')')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.vendors.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Seller Management System</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">{{ $vendor->name }}</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Orders</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-shopping-cart text-blue-600"></i>
                <span>Orders for {{ $vendor->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Track and manage customer purchases placed with this seller's shop outlets</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>super_admin/sellers_order/{{ $vendor->id }}</span>
        </span>
    </div>

    <!-- Seller Module Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1">
        <a href="/super_admin/sellers_update/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-user-pen"></i>
            <span>Registration & Profile</span>
        </a>

        <a href="/super_admin/sellers_order/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-shopping-cart"></i>
            <span>Orders</span>
            <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] bg-white/20 text-white font-mono">{{ $totalOrders }}</span>
        </a>

        <a href="/super_admin/sellers_commission/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-percent"></i>
            <span>Commission</span>
        </a>

        <a href="/super_admin/sellers_ledger/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-wallet"></i>
            <span>Financial Ledger</span>
        </a>
    </div>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Orders</p>
                <h3 class="text-2xl font-black text-gray-900 mt-0.5">{{ number_format($totalOrders) }}</h3>
                <span class="text-[10px] text-blue-600 font-medium">All Lifetime Orders</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-boxes-packing"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Revenue</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-0.5 font-mono">৳ {{ number_format($totalAmount, 2) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Gross Sales Value</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-bangladeshi-taka-sign"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Delivered Orders</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-0.5">{{ number_format($deliveredOrders) }}</h3>
                <span class="text-[10px] text-indigo-600 font-medium">Completed Deliveries</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Pending Orders</p>
                <h3 class="text-2xl font-black text-amber-600 mt-0.5">{{ number_format($pendingOrders) }}</h3>
                <span class="text-[10px] text-amber-600 font-medium">Awaiting Processing</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Toolbar Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-filter"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Filter Seller Orders</h4>
                    <p class="text-[11px] text-gray-500">Filter by status, payment method, dates, or customer info</p>
                </div>
            </div>

            <!-- Export Buttons: copy | CSV | xl | pdf | print -->
            <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-gray-50 p-0.5 text-xs font-bold">
                <button type="button"
                        onclick="copyOrderTable()"
                        title="Copy orders table to clipboard"
                        class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-blue-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-copy text-gray-500"></i>
                    <span>copy</span>
                </button>

                <a href="{{ route('admin.sellers.orders.export', [$vendor->id, 'format' => 'csv']) }}?{{ http_build_query(request()->query()) }}"
                   title="Export filtered orders as CSV"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-file-csv text-emerald-600"></i>
                    <span>CSV</span>
                </a>

                <a href="{{ route('admin.sellers.orders.export', [$vendor->id, 'format' => 'excel']) }}?{{ http_build_query(request()->query()) }}"
                   title="Export filtered orders as Excel (xl)"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-700 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-file-excel text-emerald-600"></i>
                    <span>xl</span>
                </a>

                <a href="{{ route('admin.sellers.orders.export', [$vendor->id, 'format' => 'pdf']) }}?{{ http_build_query(request()->query()) }}"
                   target="_blank"
                   title="Export printable PDF format"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-rose-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-file-pdf text-rose-500"></i>
                    <span>pdf</span>
                </a>

                <a href="{{ route('admin.sellers.orders.export', [$vendor->id, 'format' => 'print']) }}?{{ http_build_query(request()->query()) }}"
                   target="_blank"
                   title="Direct Print Layout"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-indigo-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-print text-indigo-600"></i>
                    <span>print</span>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.sellers.orders', $vendor->id) }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <!-- Search Keyword -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Order #, Name, Phone..."
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Order Status -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Order Status</label>
                    <select name="status" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Payment Status</label>
                    <select name="payment_status" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Payment</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                @if(request()->hasAny(['search', 'status', 'payment_status', 'payment_method', 'date_from', 'date_to']))
                    <a href="{{ route('admin.sellers.orders', $vendor->id) }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                        <i class="fa fa-rotate-left text-[11px]"></i>
                        <span>Reset Filters</span>
                    </a>
                @endif

                <button type="submit"
                        class="px-5 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-2">
                    <i class="fa fa-magnifying-glass text-[11px]"></i>
                    <span>Apply Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="sellerOrderTable" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[170px]">Order Number</th>
                        <th class="py-3.5 px-4 min-w-[190px]">Customer</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Items / Outlet</th>
                        <th class="py-3.5 px-4 text-right min-w-[120px]">Total Amount</th>
                        <th class="py-3.5 px-4 text-center min-w-[130px]">Payment</th>
                        <th class="py-3.5 px-4 text-center min-w-[130px]">Status</th>
                        <th class="py-3.5 px-4 text-center min-w-[100px]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($orders as $order)
                    @php
                        $rowNumber = ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration;
                        $statusColors = [
                            'pending'    => 'bg-amber-100 text-amber-800',
                            'confirmed'  => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-indigo-100 text-indigo-800',
                            'shipped'    => 'bg-purple-100 text-purple-800',
                            'delivered'  => 'bg-emerald-100 text-emerald-800',
                            'cancelled'  => 'bg-rose-100 text-rose-800',
                        ];
                    @endphp
                    <tr class="hover:bg-blue-50/40 transition group">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-500 font-mono">
                            {{ $rowNumber }}
                        </td>

                        <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                            <div>{{ $order->order_number }}</div>
                            <div class="text-[10px] text-gray-400 font-sans font-normal mt-0.5">
                                {{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-[11px] text-gray-500 font-mono">{{ $order->customer_phone }}</div>
                            @if($order->shipping_address)
                                <div class="text-[10px] text-gray-400 truncate max-w-[200px]" title="{{ $order->shipping_address }}">
                                    {{ $order->shipping_address }}
                                </div>
                            @endif
                        </td>

                        <td class="py-3.5 px-4">
                            @if($order->items && $order->items->count() > 0)
                                <div class="space-y-1">
                                    @foreach($order->items->take(2) as $item)
                                        <div class="text-[11px] text-gray-700 truncate max-w-[220px]">
                                            <span class="font-bold">{{ $item->quantity }}x</span> {{ $item->product_name }}
                                            @if($item->shop)
                                                <span class="text-[9px] text-blue-600 block">({{ $item->shop->name }})</span>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <span class="text-[10px] text-gray-400">+{{ $order->items->count() - 2 }} more item(s)</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 italic">Direct Order</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-right font-mono font-bold text-gray-900 text-xs">
                            ৳ {{ number_format($order->total_amount, 2) }}
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <div class="inline-block">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $order->payment_status ?? 'unpaid' }}
                                </span>
                                <div class="text-[9px] text-gray-400 uppercase font-mono mt-0.5">
                                    {{ $order->payment_method ?? 'COD' }}
                                </div>
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $order->order_status ?? 'pending' }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-center space-x-1 whitespace-nowrap">
                            <a href="{{ route('admin.orders.invoice', $order->id) }}"
                               target="_blank"
                               title="View Invoice"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition">
                                <i class="fa fa-file-invoice text-[11px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-2 text-base">
                                <i class="fa fa-bag-shopping"></i>
                            </div>
                            <p class="text-xs font-semibold">No orders recorded for this seller yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
            </span>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<script>
function copyOrderTable() {
    const table = document.getElementById('sellerOrderTable');
    if (!table) return;

    let text = '';
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        cols.forEach(col => {
            rowData.push(col.innerText.trim().replace(/\s+/g, ' '));
        });
        if (rowData.length > 0) {
            text += rowData.join('\t') + '\n';
        }
    });

    navigator.clipboard.writeText(text).then(() => {
        alert('Seller orders table copied to clipboard (TSV format).');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endsection
