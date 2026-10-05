@extends('admin.layout')

@section('title', 'Orders Pipeline - Amar Bangla Super Admin')
@section('page-title', 'Customer Orders & Dispatch Pipeline')

@section('content')
<div class="space-y-6">

    <!-- Flash message alerts -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center justify-between shadow-sm animate-fadeIn">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Status Tabs: All, Pending, Accepted, Complete, Not Accepted, Cancelled -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-semibold">
        @php
            $currentStatus = request('status', '');
            $tabs = [
                ''             => ['label' => 'All Orders', 'count' => $statusCounts['all'] ?? 0, 'icon' => 'fa-list', 'color' => 'blue'],
                'pending'      => ['label' => 'Pending', 'count' => $statusCounts['pending'] ?? 0, 'icon' => 'fa-clock', 'color' => 'amber'],
                'accepted'     => ['label' => 'Accepted', 'count' => $statusCounts['accepted'] ?? 0, 'icon' => 'fa-clipboard-check', 'color' => 'cyan'],
                'complete'     => ['label' => 'Complete', 'count' => $statusCounts['complete'] ?? 0, 'icon' => 'fa-circle-check', 'color' => 'emerald'],
                'not_accepted' => ['label' => 'Not Accepted', 'count' => $statusCounts['not_accepted'] ?? 0, 'icon' => 'fa-triangle-exclamation', 'color' => 'orange'],
                'cancelled'    => ['label' => 'Cancelled', 'count' => $statusCounts['cancelled'] ?? 0, 'icon' => 'fa-ban', 'color' => 'rose'],
            ];
        @endphp

        @foreach($tabs as $key => $tab)
            @php
                $isActive = ($currentStatus === $key) || ($key === 'complete' && $currentStatus === 'compliet') || ($key === 'accepted' && $currentStatus === 'accept') || ($key === 'not_accepted' && $currentStatus === 'not_accept') || ($key === 'cancelled' && $currentStatus === 'cancel');
                $url = route('admin.orders.index', array_merge(request()->except(['status', 'page']), $key !== '' ? ['status' => $key] : []));
            @endphp
            <a href="{{ $url }}"
               class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl transition whitespace-nowrap shadow-sm border
               {{ $isActive 
                   ? 'bg-blue-600 text-white border-blue-600 shadow-blue-500/20 font-bold' 
                   : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200' }}">
                <i class="fa {{ $tab['icon'] }} text-[11px] {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
                <span>{{ $tab['label'] }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                    {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-800' }}">
                    {{ number_format($tab['count']) }}
                </span>
            </a>
        @endforeach
    </div>

    <!-- Advanced Filter & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" id="orderFilterForm">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Top Row: Geographic Location Filters (Division, District, Upazila, Pourashava/Union, Ward, Search) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <!-- Search input -->
                <div class="lg:col-span-2 relative">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Search Orders</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa fa-search text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, Invoice #, Customer, Phone..."
                               class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <!-- Division Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Division</label>
                    <select name="division" id="divisionSelect" onchange="onDivisionChange(this.value)"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Divisions</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" {{ request('division') == $div ? 'selected' : '' }}>
                                {{ $div }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- District Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">District</label>
                    <select name="district" id="districtSelect" onchange="onDistrictChange(this.value)"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Districts</option>
                        @foreach($districts as $dst)
                            <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>
                                {{ $dst }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Upazila Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Upazila</label>
                    <select name="upazila" id="upazilaSelect"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Upazilas</option>
                        @foreach($upazilas as $upz)
                            <option value="{{ $upz }}" {{ request('upazila') == $upz ? 'selected' : '' }}>
                                {{ $upz }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pourashava / Union Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Pourashava / Union</label>
                    <select name="pourashava"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Areas</option>
                        @foreach($pourashavas as $p)
                            <option value="{{ $p }}" {{ request('pourashava') == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Second Row: Ward, Shop, Agent, Payment & Actions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 pt-3 border-t border-gray-100">
                <!-- Ward Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Ward</label>
                    <select name="ward"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Wards</option>
                        @foreach($wards as $w)
                            <option value="{{ $w }}" {{ request('ward') == $w ? 'selected' : '' }}>
                                {{ $w }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Shop by Order Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                        <i class="fa fa-store text-blue-500 mr-1"></i>Shop by Order
                    </label>
                    <select name="shop_id"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Shops</option>
                        @foreach($shops as $sh)
                            <option value="{{ $sh->sch_id }}" {{ (request('shop_id') == $sh->sch_id || request('shop') == $sh->sch_id) ? 'selected' : '' }}>
                                {{ $sh->name }} (#{{ $sh->sch_id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Agent Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
                        <i class="fa fa-user-tie text-emerald-500 mr-1"></i>Agent Filter
                    </label>
                    <select name="agent_id"
                            class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none font-medium">
                        <option value="">All Agents</option>
                        @foreach($agents as $ag)
                            <option value="{{ $ag->id }}" {{ (request('agent_id') == $ag->id || request('agent') == $ag->id) ? 'selected' : '' }}>
                                {{ $ag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Payment</label>
                    <select name="payment_status" class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Payments</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid / Due</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Sort By</label>
                    <select name="sort" class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="amount_high" {{ request('sort') === 'amount_high' ? 'selected' : '' }}>Highest Amount</option>
                        <option value="amount_low" {{ request('sort') === 'amount_low' ? 'selected' : '' }}>Lowest Amount</option>
                    </select>
                </div>

                <!-- Filter & Reset Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center justify-center gap-1.5 h-[38px]">
                        <i class="fa fa-filter text-xs"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->hasAny(['search', 'status', 'division', 'district', 'upazila', 'pourashava', 'ward', 'shop_id', 'shop', 'agent_id', 'agent', 'payment_status', 'payment_method', 'date_from', 'date_to', 'sort']))
                    <a href="{{ route('admin.orders.index') }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition flex items-center justify-center h-[38px] w-[38px]" title="Clear Filters">
                        <i class="fa fa-rotate-left"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Date Range & Export Toolbar -->
            <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-gray-400 font-semibold text-[11px] uppercase tracking-wider">Date Range:</span>
                    <div class="flex items-center gap-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="py-1.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <span class="text-gray-400">to</span>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="py-1.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <!-- Export CSV Button -->
                <div>
                    @php
                        $exportUrl = route('admin.orders.export', request()->query());
                    @endphp
                    <a href="{{ $exportUrl }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold transition">
                        <i class="fa fa-file-csv"></i>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold border-b border-gray-100 text-[11px]">
                    <tr>
                        <th class="px-4 py-3.5 text-center w-14">No</th>
                        <th class="px-5 py-3.5">Invoice Id</th>
                        <th class="px-5 py-3.5">Total</th>
                        <th class="px-5 py-3.5">Create date</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Delivery Status</th>
                        <th class="px-5 py-3.5 text-center">Customer order list</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- No -->
                        <td class="px-4 py-4 text-center font-bold text-gray-500 font-mono">
                            {{ $orders->firstItem() + $loop->index }}
                        </td>

                        <!-- Invoice Id -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="font-bold text-blue-600 hover:text-blue-800 font-mono text-sm block">
                                Inv_{{ $order->invoice_id ?: $order->id }}
                            </a>
                            <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1.5">
                                <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="hover:underline text-blue-500 font-medium">View Invoice</a>
                                <span>&bull;</span>
                                <span class="text-gray-500 font-mono text-[10px]">{{ $order->order_number }}</span>
                            </div>
                        </td>

                        <!-- Total -->
                        <td class="px-5 py-4 whitespace-nowrap font-mono font-bold text-gray-900 text-sm">
                            ৳ {{ number_format($order->total_amount, 2) }}
                            @if($order->shipping_fee > 0)
                                <div class="text-[10px] text-gray-400 font-normal">incl. ৳{{ number_format($order->shipping_fee, 2) }} del.</div>
                            @endif
                        </td>

                        <!-- Create date -->
                        <td class="px-5 py-4 whitespace-nowrap text-gray-700 font-medium">
                            <div class="text-xs font-semibold">{{ $order->created_at ? $order->created_at->format('d-m-Y') : 'N/A' }}</div>
                            <div class="text-[10px] text-gray-400">{{ $order->created_at ? $order->created_at->format('h:i A') : '' }}</div>
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            @php
                                $st = strtolower($order->order_status);
                                $badgeClass = match($st) {
                                    'complete', 'delivered' => 'bg-emerald-100 text-emerald-800',
                                    'accepted', 'processing' => 'bg-cyan-100 text-cyan-800',
                                    'shipped' => 'bg-indigo-100 text-indigo-800',
                                    'cancelled', 'cancel' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                                $dotClass = match($st) {
                                    'complete', 'delivered' => 'bg-emerald-500',
                                    'accepted', 'processing' => 'bg-cyan-500',
                                    'shipped' => 'bg-indigo-500',
                                    'cancelled', 'cancel' => 'bg-rose-500',
                                    default => 'bg-amber-500',
                                };
                            @endphp
                            <span class="capitalize text-[11px] font-bold px-3 py-1 rounded-full inline-flex items-center gap-1.5 {{ $badgeClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                {{ $order->order_status }}
                            </span>
                        </td>

                        <!-- Delivery Status -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            @php
                                $dst = strtolower($order->delivery_status);
                            @endphp
                            @if($dst === 'complete')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-lg text-[10px] font-bold inline-block">
                                    <i class="fa fa-circle-check text-emerald-600 mr-1"></i>Complete
                                </span>
                            @elseif($dst === 'accepted')
                                <span class="px-2.5 py-1 bg-cyan-100 text-cyan-800 rounded-lg text-[10px] font-bold inline-block">
                                    <i class="fa fa-truck-fast text-cyan-600 mr-1"></i>Accepted
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-lg text-[10px] font-bold inline-block">
                                    <i class="fa fa-clock text-amber-600 mr-1"></i>Not Accepted
                                </span>
                            @endif

                            @if($order->deliveryBoy)
                                <div class="text-[10px] text-gray-500 mt-1">
                                    By: <strong>{{ $order->deliveryBoy->name }}</strong>
                                    @if($order->deliveryBoy->mobile || $order->deliveryBoy->phone)
                                        <div class="font-mono text-gray-400">{{ $order->deliveryBoy->phone ?: $order->deliveryBoy->mobile }}</div>
                                    @endif
                                </div>
                            @endif
                        </td>

                        <!-- Customer order list -->
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            @php
                                $targetCustomerId = $order->customer_id;
                            @endphp
                            @if($targetCustomerId)
                                <a href="{{ route('admin.customers.orders', $targetCustomerId) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition"
                                   title="View all order history of {{ $order->customer_name ?: 'Customer' }}">
                                    <i class="fa fa-list text-[10px]"></i>
                                    <span>Order list</span>
                                </a>
                            @elseif($order->customer_phone)
                                <a href="{{ route('admin.orders.index', ['search' => $order->customer_phone]) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition"
                                   title="View all orders of {{ $order->customer_phone }}">
                                    <i class="fa fa-list text-[10px]"></i>
                                    <span>Order list</span>
                                </a>
                            @else
                                <span class="text-gray-400 text-xs italic">N/A</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
                                   class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="Print A4 Invoice">
                                    <i class="fa fa-print"></i>
                                </a>

                                <a href="{{ route('admin.orders.pos', $order) }}" target="_blank"
                                   class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition" title="Print POS Thermal Receipt">
                                    <i class="fa fa-receipt"></i>
                                </a>

                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition" title="View Details">
                                    Details
                                </a>

                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                      onsubmit="return confirm('Permanently delete Order #{{ $order->order_number }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                        <i class="fa fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 text-xl mx-auto mb-3">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <h4 class="font-bold text-gray-700 text-sm">No Orders Found</h4>
                            <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">There are no orders matching your selected filters or search terms.</p>
                            @if(request()->hasAny(['search', 'status', 'division', 'district', 'upazila', 'pourashava', 'ward', 'shop_id', 'shop', 'agent_id', 'agent', 'payment_status', 'payment_method', 'date_from', 'date_to', 'sort']))
                            <div class="mt-4">
                                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-xl transition">
                                    Clear all filters
                                </a>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-4 text-xs">
            <div class="text-gray-500">
                Showing <strong>{{ $orders->firstItem() ?? 0 }}</strong> to <strong>{{ $orders->lastItem() ?? 0 }}</strong> of <strong>{{ number_format($orders->total()) }}</strong> orders
            </div>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- Client-side Dynamic Cascading Javascript for Division -> District -> Upazila -->
<script>
    const geoHierarchy = @json($hierarchy ?? []);
    const selectedDivision = "{{ request('division', '') }}";
    const selectedDistrict = "{{ request('district', '') }}";
    const selectedUpazila = "{{ request('upazila', '') }}";

    function onDivisionChange(divName) {
        const dstSelect = document.getElementById('districtSelect');
        const upzSelect = document.getElementById('upazilaSelect');

        dstSelect.innerHTML = '<option value="">All Districts</option>';
        upzSelect.innerHTML = '<option value="">All Upazilas</option>';

        if (!divName || !geoHierarchy[divName]) {
            // Populate all districts from hierarchy
            const allDists = new Set();
            for (const d in geoHierarchy) {
                for (const dist in geoHierarchy[d]) {
                    allDists.add(dist);
                }
            }
            Array.from(allDists).sort().forEach(dist => {
                const opt = document.createElement('option');
                opt.value = dist;
                opt.textContent = dist;
                dstSelect.appendChild(opt);
            });
            return;
        }

        const districts = Object.keys(geoHierarchy[divName]).sort();
        districts.forEach(dist => {
            const opt = document.createElement('option');
            opt.value = dist;
            opt.textContent = dist;
            if (dist === selectedDistrict) opt.selected = true;
            dstSelect.appendChild(opt);
        });

        if (selectedDistrict) {
            onDistrictChange(selectedDistrict);
        }
    }

    function onDistrictChange(distName) {
        const divSelect = document.getElementById('divisionSelect');
        const upzSelect = document.getElementById('upazilaSelect');

        upzSelect.innerHTML = '<option value="">All Upazilas</option>';
        if (!distName) return;

        // Find which division contains this district if division is not selected
        let divName = divSelect.value;
        if (!divName) {
            for (const d in geoHierarchy) {
                if (geoHierarchy[d][distName]) {
                    divName = d;
                    divSelect.value = d;
                    break;
                }
            }
        }

        if (divName && geoHierarchy[divName] && geoHierarchy[divName][distName]) {
            const upazilas = geoHierarchy[divName][distName].slice().sort();
            upazilas.forEach(upz => {
                const opt = document.createElement('option');
                opt.value = upz;
                opt.textContent = upz;
                if (upz === selectedUpazila) opt.selected = true;
                upzSelect.appendChild(opt);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (selectedDivision) {
            onDivisionChange(selectedDivision);
        }
    });
</script>
@endsection
