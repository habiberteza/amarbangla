@extends('admin.layout')

@section('title', 'Seller Ledger - ' . $vendor->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Seller Financial Ledger (super_admin/sellers_ledger/' . $vendor->id . ')')

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
                <span class="text-blue-600 font-semibold">Financial Ledger</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-wallet text-blue-600"></i>
                <span>Financial Ledger & Statement: {{ $vendor->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Official accounting ledger of all credits, payouts, debits, commission adjustments, and wallet balances</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>super_admin/sellers_ledger/{{ $vendor->id }}</span>
        </span>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Seller Module Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1">
        <a href="/super_admin/sellers_update/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-user-pen"></i>
            <span>Registration & Profile</span>
        </a>

        <a href="/super_admin/sellers_order/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-shopping-cart"></i>
            <span>Orders</span>
        </a>

        <a href="/super_admin/sellers_commission/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-percent"></i>
            <span>Commission</span>
        </a>

        <a href="/super_admin/sellers_ledger/{{ $vendor->id }}"
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-wallet"></i>
            <span>Financial Ledger</span>
        </a>
    </div>

    <!-- Top Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Current Wallet Balance</p>
                <h3 class="text-2xl font-black mt-0.5 font-mono {{ ($vendor->balance ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    ৳ {{ number_format($vendor->balance ?? 0, 2) }}
                </h3>
                <span class="text-[10px] text-gray-500 font-medium">Available Account Funds</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Credits (Inflow)</p>
                <h3 class="text-2xl font-black text-blue-600 mt-0.5 font-mono">৳ {{ number_format($totalCredit, 2) }}</h3>
                <span class="text-[10px] text-blue-600 font-medium">Earnings & Deposits</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-arrow-down-long"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Debits (Outflow)</p>
                <h3 class="text-2xl font-black text-rose-600 mt-0.5 font-mono">৳ {{ number_format($totalDebit, 2) }}</h3>
                <span class="text-[10px] text-rose-600 font-medium">Payouts & Deductions</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-arrow-up-long"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Transactions</p>
                <h3 class="text-2xl font-black text-gray-800 mt-0.5 font-mono">{{ number_format($ledgers->total()) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Ledger Audit Records</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Record Transaction Card -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-pen-to-square text-blue-600"></i>
                    <span>Record Financial Transaction Entry</span>
                </h3>
                <p class="text-xs text-gray-500">Record payments, manual adjustments, seller payouts, or commission deductions</p>
            </div>
            <span class="text-xs text-gray-400 font-mono">POST /super_admin/sellers_ledger/{{ $vendor->id }}</span>
        </div>

        <form action="{{ route('admin.sellers.ledger.store', $vendor->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Entry Type <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" required class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="credit">Credit (Inflow / Deposit / Add Funds)</option>
                        <option value="debit">Debit (Outflow / Withdrawal / Payout / Charge)</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Category <span class="text-rose-500">*</span>
                    </label>
                    <select name="category" required class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="shop_payout">Seller Payout (Withdrawal)</option>
                        <option value="order_income">Order Income / Sales Credit</option>
                        <option value="commission_deduction">Platform Commission Deduction</option>
                        <option value="platform_fee">Service / Platform Fee</option>
                        <option value="refund">Customer Refund</option>
                        <option value="adjustment">Manual Adjustment / Correction</option>
                    </select>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Amount (৳) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-xs text-gray-400 font-bold">৳</span>
                        <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00"
                               class="w-full pl-8 pr-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Payment Method
                    </label>
                    <select name="payment_method" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="bKash">bKash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>

                <!-- Reference ID -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Reference Number (Optional)
                    </label>
                    <input type="text" name="reference_id" placeholder="e.g. Order #, Bank Cheque #, Transaction ID"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Note -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Note / Description (Optional)
                    </label>
                    <input type="text" name="note" placeholder="Short description or accounting remarks..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-plus"></i>
                    <span>Record Entry to Ledger</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Filter & Toolbar Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-filter"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Filter Ledger Statement</h4>
                    <p class="text-[11px] text-gray-500">Filter by entry type, category, reference number, or date range</p>
                </div>
            </div>

            <!-- Export Buttons: copy | CSV | xl | pdf | print -->
            <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-gray-50 p-0.5 text-xs font-bold">
                <button type="button"
                        onclick="copyLedgerTable()"
                        title="Copy ledger table to clipboard"
                        class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-blue-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-copy text-gray-500"></i>
                    <span>copy</span>
                </button>

                <a href="{{ route('admin.sellers.ledger.export', [$vendor->id, 'format' => 'csv']) }}?{{ http_build_query(request()->query()) }}"
                   title="Export filtered ledger as CSV"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-file-csv text-emerald-600"></i>
                    <span>CSV</span>
                </a>

                <a href="{{ route('admin.sellers.ledger.export', [$vendor->id, 'format' => 'excel']) }}?{{ http_build_query(request()->query()) }}"
                   title="Export filtered ledger as Excel (xl)"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-700 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-file-excel text-emerald-600"></i>
                    <span>xl</span>
                </a>

                <a href="{{ route('admin.sellers.ledger.export', [$vendor->id, 'format' => 'pdf']) }}?{{ http_build_query(request()->query()) }}"
                   target="_blank"
                   title="Export printable PDF format"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-rose-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-file-pdf text-rose-500"></i>
                    <span>pdf</span>
                </a>

                <a href="{{ route('admin.sellers.ledger.export', [$vendor->id, 'format' => 'print']) }}?{{ http_build_query(request()->query()) }}"
                   target="_blank"
                   title="Direct Print Layout"
                   class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-indigo-600 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa fa-print text-indigo-600"></i>
                    <span>print</span>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.sellers.ledger', $vendor->id) }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <!-- Search Keyword -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Txn #, Note, Reference..."
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Type</label>
                    <select name="type" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Types</option>
                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit (Inflow)</option>
                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit (Outflow)</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Category</label>
                    <select name="category" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Categories</option>
                        <option value="shop_payout" {{ request('category') === 'shop_payout' ? 'selected' : '' }}>Payout</option>
                        <option value="order_income" {{ request('category') === 'order_income' ? 'selected' : '' }}>Order Income</option>
                        <option value="commission_deduction" {{ request('category') === 'commission_deduction' ? 'selected' : '' }}>Commission</option>
                        <option value="adjustment" {{ request('category') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
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
                @if(request()->hasAny(['search', 'type', 'category', 'date_from', 'date_to']))
                    <a href="{{ route('admin.sellers.ledger', $vendor->id) }}"
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

    <!-- Ledger Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="sellerLedgerTable" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[150px]">Txn Number</th>
                        <th class="py-3.5 px-4 min-w-[140px]">Date & Time</th>
                        <th class="py-3.5 px-4 min-w-[140px]">Category</th>
                        <th class="py-3.5 px-4 min-w-[120px]">Reference</th>
                        <th class="py-3.5 px-4 min-w-[110px]">Method</th>
                        <th class="py-3.5 px-4 text-right min-w-[110px]">Credit (+)</th>
                        <th class="py-3.5 px-4 text-right min-w-[110px]">Debit (-)</th>
                        <th class="py-3.5 px-4 text-right min-w-[120px]">Balance After</th>
                        <th class="py-3.5 px-4 min-w-[160px]">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($ledgers as $ledger)
                    @php
                        $rowNumber = ($ledgers->currentPage() - 1) * $ledgers->perPage() + $loop->iteration;
                    @endphp
                    <tr class="hover:bg-blue-50/40 transition">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-400 font-mono">{{ $rowNumber }}</td>

                        <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                            {{ $ledger->txn_number }}
                        </td>

                        <td class="py-3.5 px-4 text-[11px] text-gray-500">
                            {{ $ledger->created_at ? $ledger->created_at->format('d M Y, h:i A') : 'N/A' }}
                        </td>

                        <td class="py-3.5 px-4 font-semibold text-gray-800">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $ledger->type === 'credit' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ str_replace('_', ' ', $ledger->category) }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 font-mono text-gray-600">
                            {{ $ledger->reference_id ?? '-' }}
                        </td>

                        <td class="py-3.5 px-4 text-gray-600">
                            {{ $ledger->payment_method ?? 'System' }}
                        </td>

                        <td class="py-3.5 px-4 text-right font-mono font-bold text-blue-600">
                            @if($ledger->type === 'credit')
                                + ৳ {{ number_format($ledger->amount, 2) }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-right font-mono font-bold text-rose-600">
                            @if($ledger->type === 'debit')
                                - ৳ {{ number_format($ledger->amount, 2) }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="py-3.5 px-4 text-right font-mono font-bold text-gray-900">
                            ৳ {{ number_format($ledger->balance_after, 2) }}
                        </td>

                        <td class="py-3.5 px-4 text-gray-500 text-[11px] truncate max-w-[200px]" title="{{ $ledger->note }}">
                            {{ $ledger->note ?: '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-2 text-base">
                                <i class="fa fa-receipt"></i>
                            </div>
                            <p class="text-xs font-semibold">No ledger entries recorded for this seller yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ledgers->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">
                Showing {{ $ledgers->firstItem() ?? 0 }} to {{ $ledgers->lastItem() ?? 0 }} of {{ $ledgers->total() }} entries
            </span>
            <div>
                {{ $ledgers->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<script>
function copyLedgerTable() {
    const table = document.getElementById('sellerLedgerTable');
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
        alert('Seller ledger table copied to clipboard (TSV format).');
    });
}
</script>
@endsection
