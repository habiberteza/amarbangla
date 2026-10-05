@extends('admin.layout')

@section('title', 'Shops Commission - Amar Bangla Super Admin')
@section('page-title', 'Shop Commission Management (super_admin/shops_commission)')

@section('content')
<div class="space-y-6">

    <!-- Card Navigation Tabs matching CI 3 / Live Super Admin -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50/70 px-4 pt-3">
            <ul class="flex flex-wrap -mb-px text-xs font-bold text-center text-gray-500" id="commissionTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg active tab-btn"
                            id="custom-tabs-four-home-tab" data-tab-target="custom-tabs-four-home" type="button" role="tab">
                        <i class="fa fa-percent text-blue-600"></i>
                        <span>Shops Commission Rates</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-blue-100 text-blue-800 rounded-full">{{ $shops->total() }}</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-profile-tab" data-tab-target="custom-tabs-four-profile" type="button" role="tab">
                        <i class="fa fa-clock text-amber-500"></i>
                        <span>Unpaid Commissions</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-amber-100 text-amber-800 rounded-full">{{ $unpaidOrders->count() }}</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-messages-tab" data-tab-target="custom-tabs-four-messages" type="button" role="tab">
                        <i class="fa fa-circle-check text-emerald-600"></i>
                        <span>Paid History</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-800 rounded-full">{{ $paidOrders->count() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-6">
            <!-- TAB 1: Shops Commission Rates -->
            <div id="custom-tabs-four-home" class="tab-pane space-y-6">
                <!-- Global Platform Commission Card -->
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-1 text-center md:text-left">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-blue-100 uppercase tracking-wider">Marketplace Policy</span>
                        <h3 class="text-xl font-black">Default Global Marketplace Commission</h3>
                        <p class="text-xs text-blue-200 max-w-lg">Applied automatically to newly onboarded merchant shops unless overridden by custom negotiated terms below.</p>
                    </div>

                    <form action="{{ route('admin.shops_commission.update-global') }}" method="POST" class="flex items-center gap-3 bg-white/10 p-2.5 rounded-2xl backdrop-blur-sm border border-white/20">
                        @csrf
                        <div class="flex items-center gap-1.5 px-3">
                            <input type="number" step="0.01" min="0" max="100" name="platform_commission" value="{{ $globalCommission }}" required
                                   class="w-20 py-2 px-3 bg-white text-gray-900 font-extrabold text-sm rounded-xl text-center focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <span class="font-bold text-sm">%</span>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-400 text-white font-bold text-xs rounded-xl shadow transition whitespace-nowrap">
                            Update Global Rate
                        </button>
                    </form>
                </div>

                <!-- Filter & Search Bar -->
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <form method="GET" action="{{ route('admin.shops_commission.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search shop by name or contact phone..."
                                   class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-xs font-semibold transition">
                            Filter
                        </button>

                        @if(request('search'))
                            <a href="{{ route('admin.shops_commission.index') }}" class="text-xs text-gray-500 hover:text-rose-600 underline">Reset</a>
                        @endif
                    </form>

                    <div class="text-xs text-gray-500 font-medium">
                        Showing <span class="font-bold text-gray-800">{{ $shops->total() }}</span> registered shops
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Shop Details</th>
                                    <th class="py-3 px-4">Vendor Owner</th>
                                    <th class="py-3 px-4">Location</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-center">Current Commission</th>
                                    <th class="py-3 px-4 text-right">Update Custom Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium">
                                @forelse($shops as $shop)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($shop->logo)
                                                <img src="{{ $shop->logo }}" alt="" class="w-9 h-9 rounded-xl object-cover border border-gray-200">
                                            @else
                                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                                                    {{ substr($shop->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $shop->name }}</h4>
                                                <p class="text-[11px] text-gray-500 font-mono">{{ $shop->phone }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-3.5 px-4 text-gray-700">
                                        {{ $shop->vendor ? $shop->vendor->name : 'N/A' }}
                                    </td>

                                    <td class="py-3.5 px-4 text-gray-600">
                                        {{ $shop->district ?: 'Dhaka' }}, {{ $shop->division ?: 'Bangladesh' }}
                                    </td>

                                    <td class="py-3.5 px-4 text-center">
                                        @if($shop->status === 'active')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-700">{{ ucfirst($shop->status) }}</span>
                                        @endif
                                    </td>

                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-mono font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ number_format($shop->commission_rate, 2) }}%
                                        </span>
                                    </td>

                                    <td class="py-3.5 px-4 text-right">
                                        <form action="{{ route('admin.shops_commission.update-shop', $shop->id) }}" method="POST" class="inline-flex items-center justify-end gap-1.5">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ $shop->commission_rate }}" required
                                                   class="w-16 py-1.5 px-2 bg-gray-50 border border-gray-300 rounded-lg text-xs font-mono text-center focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                            <span class="text-xs font-bold text-gray-500">%</span>
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold transition">
                                                Set Rate
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">
                                        No shops found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($shops->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $shops->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- TAB 2: Unpaid Commissions -->
            <div id="custom-tabs-four-profile" class="tab-pane hidden space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Unpaid Marketplace Commissions</h4>
                        <p class="text-xs text-gray-500">Pending commissions from customer orders awaiting settlement.</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-50 text-amber-700 font-bold rounded-xl text-xs border border-amber-200">
                        {{ $unpaidOrders->count() }} Orders Pending
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Invoice / Order #</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4 text-right">Order Subtotal</th>
                                <th class="py-3 px-4 text-center">Commission %</th>
                                <th class="py-3 px-4 text-right">Commission Due</th>
                                <th class="py-3 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($unpaidOrders as $ord)
                            @php
                                $commPct = floatval($globalCommission);
                                $commAmount = ($ord->subtotal * $commPct) / 100;
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                                    {{ $ord->order_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900">{{ $ord->customer_name }}</div>
                                    <div class="text-[11px] text-gray-500 font-mono">{{ $ord->customer_phone }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold">
                                    ৳{{ number_format($ord->subtotal, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono">
                                    {{ number_format($commPct, 2) }}%
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold text-amber-600">
                                    ৳{{ number_format($commAmount, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                        {{ ucfirst($ord->payment_status ?: 'Unpaid') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    No pending unpaid commissions found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: Paid Commission History -->
            <div id="custom-tabs-four-messages" class="tab-pane hidden space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Settled Commission History</h4>
                        <p class="text-xs text-gray-500">Record of successfully realized commissions on paid orders.</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-xs border border-emerald-200">
                        {{ $paidOrders->count() }} Settled Orders
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Invoice / Order #</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4 text-right">Order Subtotal</th>
                                <th class="py-3 px-4 text-center">Commission %</th>
                                <th class="py-3 px-4 text-right">Commission Realized</th>
                                <th class="py-3 px-4 text-center">Date Paid</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($paidOrders as $ord)
                            @php
                                $commPct = floatval($globalCommission);
                                $commAmount = ($ord->subtotal * $commPct) / 100;
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-blue-600">
                                    {{ $ord->order_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900">{{ $ord->customer_name }}</div>
                                    <div class="text-[11px] text-gray-500 font-mono">{{ $ord->payment_method ?: 'Online/COD' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold">
                                    ৳{{ number_format($ord->subtotal, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono">
                                    {{ number_format($commPct, 2) }}%
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-600">
                                    ৳{{ number_format($commAmount, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-gray-500">
                                    {{ $ord->updated_at ? $ord->updated_at->format('d M Y, h:i A') : 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    No paid commission records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    const tabMap = {
        '#custom-tabs-four-home': 'custom-tabs-four-home',
        '#custom-tabs-four-profile': 'custom-tabs-four-profile',
        '#custom-tabs-four-messages': 'custom-tabs-four-messages',
        '#shops_list': 'custom-tabs-four-home',
        '#unpaid_list': 'custom-tabs-four-profile',
        '#paid_list': 'custom-tabs-four-messages',
        '#rates': 'custom-tabs-four-home',
        '#unpaid': 'custom-tabs-four-profile',
        '#paid': 'custom-tabs-four-messages'
    };

    function switchTab(targetId) {
        tabButtons.forEach(btn => {
            if (btn.getAttribute('data-tab-target') === targetId) {
                btn.classList.add('active', 'text-blue-600', 'border-blue-600');
                btn.classList.remove('border-transparent', 'text-gray-500');
            } else {
                btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });

        tabPanes.forEach(pane => {
            if (pane.id === targetId) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        });
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.getAttribute('data-tab-target');
            switchTab(target);
            window.location.hash = target;
        });
    });

    if (window.location.hash && tabMap[window.location.hash]) {
        switchTab(tabMap[window.location.hash]);
    }

    window.addEventListener('hashchange', function() {
        if (window.location.hash && tabMap[window.location.hash]) {
            switchTab(tabMap[window.location.hash]);
        }
    });
});
</script>
@endsection
