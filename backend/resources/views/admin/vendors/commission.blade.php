@extends('admin.layout')

@section('title', 'Seller Commission - ' . $vendor->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Seller Commission (super_admin/sellers_commission/' . $vendor->id . ')')

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
                <span class="text-blue-600 font-semibold">Commission</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-percent text-blue-600"></i>
                <span>Commission Settings & Earnings: {{ $vendor->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Configure platform commission percentages and inspect sales commission payouts</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>super_admin/sellers_commission/{{ $vendor->id }}</span>
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
           class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
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
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Current Commission Rate</p>
                <h3 class="text-2xl font-black text-blue-600 mt-0.5">{{ number_format($vendor->commission_rate ?? $globalCommission, 2) }}%</h3>
                <span class="text-[10px] text-gray-500 font-medium">Global Default: {{ $globalCommission }}%</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-calculator"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Sales Generated</p>
                <h3 class="text-2xl font-black text-gray-900 mt-0.5 font-mono">৳ {{ number_format($totalSales, 2) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Valid Delivered Orders</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-chart-line"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Platform Commission</p>
                <h3 class="text-2xl font-black text-purple-600 mt-0.5 font-mono">৳ {{ number_format($totalCommission, 2) }}</h3>
                <span class="text-[10px] text-purple-600 font-medium">Total Platform Retained</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-hand-holding-dollar"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Seller Net Earnings</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-0.5 font-mono">৳ {{ number_format($sellerEarnings, 2) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Net Payout Accrued</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <!-- Commission Configuration Card -->
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="border-b border-gray-100 pb-3 mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-sliders text-blue-600"></i>
                    <span>Commission Setup for {{ $vendor->name }}</span>
                </h3>
                <p class="text-xs text-gray-500">Set custom commission parameters applicable across this seller's products and outlets</p>
            </div>
            <span class="text-xs text-gray-400 font-mono">POST /super_admin/sellers_commission/{{ $vendor->id }}</span>
        </div>

        <form action="{{ route('admin.sellers.commission.update', $vendor->id) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Commission Rate (%) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="100" name="commission_rate" required
                               value="{{ old('commission_rate', $vendor->commission_rate ?? $globalCommission) }}"
                               class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-bold">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Commission Type
                    </label>
                    <select name="commission_type" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="percentage" {{ ($vendor->commission_type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage of Order Subtotal (%)</option>
                        <option value="fixed" {{ ($vendor->commission_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Fee Per Order (৳)</option>
                    </select>
                </div>

                <div class="flex items-center sm:pt-6">
                    <label class="relative flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700 select-none">
                        <input type="checkbox" name="apply_to_shops" value="1" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span>Apply rate to all seller shop outlets ({{ $vendor->shops ? $vendor->shops->count() : 0 }})</span>
                    </label>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Commission Rate</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Seller Outlets / Shops Breakdown -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-store text-blue-600"></i>
                    <span>Registered Outlets / Shops ({{ $vendor->shops ? $vendor->shops->count() : 0 }})</span>
                </h4>
                <p class="text-xs text-gray-500">Individual commission rates and wallet balances per outlet</p>
            </div>
            <a href="{{ route('admin.shops.create_legacy') }}" class="text-xs text-blue-600 hover:underline font-bold">
                + Add Outlet
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Shop Name</th>
                        <th class="py-3 px-4">Category / Type</th>
                        <th class="py-3 px-4 text-right">Outlet Balance</th>
                        <th class="py-3 px-4 text-center">Commission Rate</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($vendor->shops as $shop)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3 px-4 text-center font-bold text-gray-400 font-mono">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 font-bold text-gray-900">
                            <div>{{ $shop->name }}</div>
                            <div class="text-[10px] text-gray-400 font-normal">{{ $shop->address ?? 'No address' }}</div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $shop->category ?? 'General' }} &bull; {{ $shop->shop_type ?? 'Retail' }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-gray-900">৳ {{ number_format($shop->balance ?? 0, 2) }}</td>
                        <td class="py-3 px-4 text-center font-bold font-mono text-blue-600">{{ number_format($shop->commission_rate ?? $vendor->commission_rate ?? $globalCommission, 2) }}%</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $shop->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $shop->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('admin.shops.edit_legacy', $shop->id) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-700 hover:text-blue-600 font-bold transition">
                                <i class="fa fa-pen text-[10px]"></i>
                                <span>Edit</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">No shop outlets created under this seller yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Commission Log / Order Payouts History -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-receipt text-blue-600"></i>
                    <span>Commission Ledger & Orders Log</span>
                </h4>
                <p class="text-xs text-gray-500">Order-by-order commission deductions and earnings</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="copyCommissionTable()"
                        title="Copy to clipboard"
                        class="px-2.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-white text-gray-700 text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa fa-copy text-gray-500"></i>
                    <span>copy</span>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="commissionTable" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Order Number</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4 text-right">Order Total</th>
                        <th class="py-3 px-4 text-center">Comm. Rate</th>
                        <th class="py-3 px-4 text-right">Platform Fee</th>
                        <th class="py-3 px-4 text-right">Net Seller Earning</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($orders as $ord)
                    @php
                        $rateUsed = $vendor->commission_rate ?? $globalCommission;
                        $commAmt = round(($ord->total_amount * $rateUsed) / 100, 2);
                        $netEarn = $ord->total_amount - $commAmt;
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3 px-4 text-center font-bold text-gray-400 font-mono">{{ $loop->iteration }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-gray-900">{{ $ord->order_number }}</td>
                        <td class="py-3 px-4 text-[11px] text-gray-500">{{ $ord->created_at ? $ord->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-gray-900">৳ {{ number_format($ord->total_amount, 2) }}</td>
                        <td class="py-3 px-4 text-center font-mono font-bold text-blue-600">{{ number_format($rateUsed, 2) }}%</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-purple-600">৳ {{ number_format($commAmt, 2) }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600">৳ {{ number_format($netEarn, 2) }}</td>
                        <td class="py-3 px-4 text-center uppercase font-bold text-[10px]">
                            <span class="inline-block px-2 py-0.5 rounded {{ $ord->order_status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $ord->order_status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">No orders completed for commission calculation.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function copyCommissionTable() {
    const table = document.getElementById('commissionTable');
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
        alert('Commission table copied to clipboard (TSV format).');
    });
}
</script>
@endsection
