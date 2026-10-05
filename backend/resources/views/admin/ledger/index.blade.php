@extends('admin.layout')

@section('title', 'Financial Ledger - Amar Bangla Super Admin')
@section('page-title', 'Financial Ledger & Accounts (super_admin/ledger)')

@section('content')
<div class="space-y-6">

    <!-- Top Account Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Inflow (Credits)</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-0.5">৳{{ number_format($totalCredit, 2) }}</h3>
                <span class="text-[10px] text-gray-400">Order payments, platform fees</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base">
                <i class="fa fa-arrow-down-left"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Outflow (Debits)</p>
                <h3 class="text-2xl font-black text-rose-600 mt-0.5">৳{{ number_format($totalDebit, 2) }}</h3>
                <span class="text-[10px] text-gray-400">Seller payouts, rider & agent fees</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-base">
                <i class="fa fa-arrow-up-right"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Net Platform Balance</p>
                <h3 class="text-2xl font-black {{ $netBalance >= 0 ? 'text-blue-600' : 'text-rose-600' }} mt-0.5">
                    ৳{{ number_format($netBalance, 2) }}
                </h3>
                <span class="text-[10px] text-gray-400">System liquid ledger reserve</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                <i class="fa fa-vault"></i>
            </div>
        </div>
    </div>

    <!-- AdminLTE Card Tabs Navigation -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50/70 px-4 pt-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <ul class="flex flex-wrap -mb-px text-xs font-bold text-center text-gray-500" id="ledgerTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg active tab-btn"
                            id="custom-tabs-four-home-tab" data-tab-target="custom-tabs-four-home" type="button" role="tab">
                        <i class="fa fa-list text-blue-600"></i>
                        <span>All Statements</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-blue-100 text-blue-800 rounded-full">{{ $ledgers->total() }}</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-profile-tab" data-tab-target="custom-tabs-four-profile" type="button" role="tab">
                        <i class="fa fa-arrow-down-left text-emerald-600"></i>
                        <span>Credits (Inflow)</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-800 rounded-full">{{ $credits->count() }}</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-messages-tab" data-tab-target="custom-tabs-four-messages" type="button" role="tab">
                        <i class="fa fa-arrow-up-right text-rose-600"></i>
                        <span>Debits (Outflow)</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-rose-100 text-rose-800 rounded-full">{{ $debits->count() }}</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-settings-tab" data-tab-target="custom-tabs-four-settings" type="button" role="tab">
                        <i class="fa fa-plus-circle text-purple-600"></i>
                        <span>Record Entry</span>
                    </button>
                </li>
            </ul>

            <button type="button" onclick="document.getElementById('custom-tabs-four-settings-tab').click()"
                    class="mb-2 sm:mb-0 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5 self-start sm:self-center">
                <i class="fa fa-plus"></i>
                <span>Quick Transaction</span>
            </button>
        </div>

        <div class="p-6">
            <!-- TAB 1: All Statements -->
            <div id="custom-tabs-four-home" class="tab-pane space-y-6">
                <!-- Filter Bar -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <form method="GET" action="{{ route('admin.ledger.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Txn #, party name, order reference..."
                                   class="w-full pl-9 pr-3 py-2 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                        </div>

                        <select name="type" onchange="this.form.submit()" class="py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="">All Types</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credits (Inflow)</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debits (Outflow)</option>
                        </select>

                        <select name="category" onchange="this.form.submit()" class="py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="">All Categories</option>
                            <option value="order_income" {{ request('category') == 'order_income' ? 'selected' : '' }}>Order Income</option>
                            <option value="shop_payout" {{ request('category') == 'shop_payout' ? 'selected' : '' }}>Shop Payout</option>
                            <option value="agent_commission" {{ request('category') == 'agent_commission' ? 'selected' : '' }}>Agent Commission</option>
                            <option value="delivery_fee" {{ request('category') == 'delivery_fee' ? 'selected' : '' }}>Delivery Fee</option>
                            <option value="platform_fee" {{ request('category') == 'platform_fee' ? 'selected' : '' }}>Platform Fee</option>
                            <option value="refund" {{ request('category') == 'refund' ? 'selected' : '' }}>Refund</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-xs font-semibold transition">
                            Filter
                        </button>

                        @if(request('search') || request('type') || request('category'))
                            <a href="{{ route('admin.ledger.index') }}" class="text-xs text-gray-500 hover:text-rose-600 underline">Reset</a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Txn #</th>
                                <th class="py-3 px-4">Date & Time</th>
                                <th class="py-3 px-4 text-center">Type</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Party & Reference</th>
                                <th class="py-3 px-4">Method</th>
                                <th class="py-3 px-4 text-right">Amount</th>
                                <th class="py-3 px-4 text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($ledgers as $ledger)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-gray-800">
                                    {{ $ledger->txn_number }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-500 text-[11px] whitespace-nowrap">
                                    {{ $ledger->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($ledger->type === 'credit')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center justify-center gap-1 w-max mx-auto">
                                            <i class="fa fa-arrow-down text-[9px]"></i> Credit
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200 flex items-center justify-center gap-1 w-max mx-auto">
                                            <i class="fa fa-arrow-up text-[9px]"></i> Debit
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-gray-100 text-gray-700 capitalize">
                                        {{ str_replace('_', ' ', $ledger->category) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-900">{{ $ledger->party_name ?: 'System Internal' }}</div>
                                    @if($ledger->reference_id)
                                        <div class="text-[11px] text-gray-500 font-mono">Ref: {{ $ledger->reference_id }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 font-medium">
                                    {{ $ledger->payment_method ?: 'Internal' }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-extrabold text-sm {{ $ledger->type === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $ledger->type === 'credit' ? '+' : '-' }}৳{{ number_format($ledger->amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-semibold text-gray-700">
                                    ৳{{ number_format($ledger->balance_after, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-400">
                                    No ledger entries found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ledgers->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $ledgers->links() }}
                </div>
                @endif
            </div>

            <!-- TAB 2: Credits (Inflow) -->
            <div id="custom-tabs-four-profile" class="tab-pane hidden space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Credits / Revenue Inflow Entries</h4>
                        <p class="text-xs text-gray-500">Customer payments, marketplace commission collections, and incoming funds.</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-xs border border-emerald-200">
                        Total Credits: ৳{{ number_format($totalCredit, 2) }}
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Txn #</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Source / Party</th>
                                <th class="py-3 px-4">Payment Method</th>
                                <th class="py-3 px-4 text-right">Inflow Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($credits as $c)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">{{ $c->txn_number }}</td>
                                <td class="py-3.5 px-4 text-gray-500">{{ $c->created_at->format('d M Y, h:i A') }}</td>
                                <td class="py-3.5 px-4 capitalize">{{ str_replace('_', ' ', $c->category) }}</td>
                                <td class="py-3.5 px-4 font-bold text-gray-800">{{ $c->party_name ?: 'Platform' }}</td>
                                <td class="py-3.5 px-4 text-gray-600">{{ $c->payment_method ?: 'Digital' }}</td>
                                <td class="py-3.5 px-4 text-right font-mono font-extrabold text-emerald-600">+৳{{ number_format($c->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">No credit entries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: Debits (Outflow) -->
            <div id="custom-tabs-four-messages" class="tab-pane hidden space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Debits / Outflow Disbursements</h4>
                        <p class="text-xs text-gray-500">Shop vendor payouts, delivery rider commissions, agent fees, and refunds.</p>
                    </div>
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 font-bold rounded-xl text-xs border border-rose-200">
                        Total Debits: ৳{{ number_format($totalDebit, 2) }}
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Txn #</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Payee / Party</th>
                                <th class="py-3 px-4">Payment Method</th>
                                <th class="py-3 px-4 text-right">Disbursed Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($debits as $d)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4 font-mono font-bold text-rose-700">{{ $d->txn_number }}</td>
                                <td class="py-3.5 px-4 text-gray-500">{{ $d->created_at->format('d M Y, h:i A') }}</td>
                                <td class="py-3.5 px-4 capitalize">{{ str_replace('_', ' ', $d->category) }}</td>
                                <td class="py-3.5 px-4 font-bold text-gray-800">{{ $d->party_name ?: 'Disbursement' }}</td>
                                <td class="py-3.5 px-4 text-gray-600">{{ $d->payment_method ?: 'Bank/MFS' }}</td>
                                <td class="py-3.5 px-4 text-right font-mono font-extrabold text-rose-600">-৳{{ number_format($d->amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">No debit entries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: Record Entry / Manual Entry Form -->
            <div id="custom-tabs-four-settings" class="tab-pane hidden space-y-6 max-w-2xl mx-auto">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Record Manual Ledger Transaction</h3>
                    <p class="text-xs text-gray-500 mt-1">Directly post journal entries, merchant payouts, or administrative cash reconciliations.</p>
                </div>

                <form action="{{ route('admin.ledger.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Transaction Type <span class="text-rose-500">*</span></label>
                            <select name="type" required class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="credit">Credit (Money Received / Inflow)</option>
                                <option value="debit">Debit (Money Paid Out / Outflow)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Category <span class="text-rose-500">*</span></label>
                            <select name="category" required class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="order_income">Order Income</option>
                                <option value="shop_payout">Shop Payout</option>
                                <option value="agent_commission">Agent Commission</option>
                                <option value="delivery_fee">Delivery Fee</option>
                                <option value="platform_fee">Platform Fee</option>
                                <option value="refund">Refund</option>
                                <option value="adjustment">Manual Adjustment</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Amount (৳) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00"
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Method</label>
                            <select name="payment_method" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="bKash">bKash</option>
                                <option value="Nagad">Nagad</option>
                                <option value="Rocket">Rocket</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Internal">Internal Adjustment</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Party Type</label>
                            <select name="party_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="shop">Shop / Vendor</option>
                                <option value="customer">Customer</option>
                                <option value="agent">Field Agent</option>
                                <option value="delivery_boy">Delivery Rider</option>
                                <option value="platform">Platform Internal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Party / Entity Name</label>
                            <input type="text" name="party_name" placeholder="e.g. Bengal Fashion Store"
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Reference ID (Order # / Payout voucher)</label>
                        <input type="text" name="reference_id" placeholder="e.g. ORD-2026-001"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Description / Notes</label>
                        <textarea name="note" rows="3" placeholder="Enter explanation for this transaction..."
                                  class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa fa-floppy-disk"></i>
                            <span>Save Transaction Entry</span>
                        </button>
                    </div>
                </form>
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
        '#custom-tabs-four-settings': 'custom-tabs-four-settings',
        '#all': 'custom-tabs-four-home',
        '#credits': 'custom-tabs-four-profile',
        '#credit': 'custom-tabs-four-profile',
        '#debits': 'custom-tabs-four-messages',
        '#debit': 'custom-tabs-four-messages',
        '#add': 'custom-tabs-four-settings',
        '#record': 'custom-tabs-four-settings',
        '#ledger_super': 'custom-tabs-four-home'
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
