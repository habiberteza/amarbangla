@extends('admin.layout')

@section('title', 'Financial Ledger - ' . $deliveryBoy->name)
@section('page-title', 'Financial Ledger: ' . $deliveryBoy->name . ' (super_admin/delivery_boy_ledger/' . $deliveryBoy->id . ')')

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
            <a href="{{ route('admin.delivery_boys.commissions', $deliveryBoy->id) }}"
               class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 rounded-lg text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa fa-percent text-[11px]"></i>
                <span>Commission</span>
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

    <!-- Balance Banner & Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Current Ledger Balance</p>
                <h3 class="text-2xl font-extrabold {{ $deliveryBoy->balance < 0 ? 'text-rose-600' : 'text-emerald-700' }} mt-0.5 font-mono">
                    ৳ {{ number_format($deliveryBoy->balance, 2) }}
                </h3>
                <span class="text-[10px] text-gray-500 font-medium">Net Settlement Amount</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa fa-scale-balanced"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Cash In Hand (COD)</p>
                <h3 class="text-2xl font-extrabold text-blue-600 mt-0.5 font-mono">
                    ৳ {{ number_format($deliveryBoy->cash_in_hand, 2) }}
                </h3>
                <span class="text-[10px] text-gray-500 font-medium">Collected Cash to Deposit</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa fa-money-bill-wave"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Transactions</p>
                <h3 class="text-2xl font-extrabold text-indigo-600 mt-0.5 font-mono">
                    {{ $transactions->total() }}
                </h3>
                <span class="text-[10px] text-gray-500 font-medium">Total Ledger Entries</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa fa-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Record Transaction / Adjustment Form -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                <i class="fa fa-plus-minus"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Record Transaction / Balance Adjustment</h3>
                <p class="text-[11px] text-gray-400">Post a credit (pay out / commission) or debit (cash collected deposit / penalty)</p>
            </div>
        </div>

        <form action="{{ route('admin.delivery_boys.add_transaction', $deliveryBoy->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
            @csrf

            <!-- Type -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Entry Type <span class="text-rose-500">*</span></label>
                <select name="type" required class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="credit">Credit (+) Add to Balance</option>
                    <option value="debit">Debit (-) Deduct from Balance</option>
                </select>
            </div>

            <!-- Amount -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Amount (৳) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Category <span class="text-rose-500">*</span></label>
                <select name="category" required class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="delivery_fee">Delivery Commission Payout</option>
                    <option value="cod_collection">COD Cash Deposit</option>
                    <option value="fuel_allowance">Fuel / Travel Allowance</option>
                    <option value="bonus">Bonus / Incentive</option>
                    <option value="penalty">Penalty / Deduction</option>
                    <option value="adjustment">Manual Adjustment</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Method</label>
                <select name="payment_method" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="Cash">Cash</option>
                    <option value="bKash">bKash</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Bank">Bank Transfer</option>
                </select>
            </div>

            <!-- Note & Submit -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Note (Optional)</label>
                <div class="flex items-center gap-2">
                    <input type="text" name="note" placeholder="Description/Note"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition whitespace-nowrap">
                        Post
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions History Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Transaction History</h3>
            <span class="text-xs text-gray-400 font-mono">{{ $transactions->total() }} records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[10px] tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Date / Time</th>
                        <th class="px-5 py-3.5">Txn Number</th>
                        <th class="px-5 py-3.5">Type</th>
                        <th class="px-5 py-3.5">Category</th>
                        <th class="px-5 py-3.5">Method</th>
                        <th class="px-5 py-3.5">Amount</th>
                        <th class="px-5 py-3.5">Balance After</th>
                        <th class="px-5 py-3.5">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $txn)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-mono text-gray-500 text-xs">
                            {{ $txn->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-5 py-4 font-mono font-bold text-blue-600">
                            {{ $txn->txn_number }}
                        </td>
                        <td class="px-5 py-4">
                            @if($txn->type === 'credit')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Credit (+)</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Debit (-)</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 capitalize font-medium text-gray-700">
                            {{ str_replace('_', ' ', $txn->category) }}
                        </td>
                        <td class="px-5 py-4 text-gray-600">
                            {{ $txn->payment_method ?? 'Cash' }}
                        </td>
                        <td class="px-5 py-4 font-mono font-bold text-xs {{ $txn->type === 'credit' ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $txn->type === 'credit' ? '+' : '-' }} ৳{{ number_format($txn->amount, 2) }}
                        </td>
                        <td class="px-5 py-4 font-mono font-bold text-gray-800">
                            ৳{{ number_format($txn->balance_after, 2) }}
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-[11px] max-w-xs truncate">
                            {{ $txn->note ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400 text-xs">
                            No ledger transactions recorded for {{ $deliveryBoy->name }} yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
