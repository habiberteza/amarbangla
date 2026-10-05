@extends('admin.layout')

@section('title', 'License Management - Amar Bangla Super Admin')
@section('page-title', 'License Management (super_admin/license)')

@section('content')
<div class="space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-sm animate-fade-in">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Top KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Licenses</p>
                <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ number_format($totalCount ?? $licenses->total()) }}</h3>
                <span class="text-[10px] text-blue-600 font-medium">Registered Outlets</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-certificate"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Approved / Active</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-0.5">{{ number_format($approvedCount ?? 0) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Verified Valid</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Pending Approval</p>
                <h3 class="text-2xl font-black text-amber-600 mt-0.5">{{ number_format($pendingCount ?? 0) }}</h3>
                <span class="text-[10px] text-amber-600 font-medium">Awaiting Verification</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-clock"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Expired / Rejected</p>
                <h3 class="text-2xl font-black text-rose-600 mt-0.5">{{ number_format($expiredCount ?? 0) }}</h3>
                <span class="text-[10px] text-rose-600 font-medium">Action Required</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-3 border-b border-gray-100">
            <div>
                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa fa-id-card text-blue-600"></i>
                    <span>Shop License Management</span>
                </h4>
                <p class="text-[11px] text-gray-500">Filter, search, copy or export registered merchant licenses and permits</p>
            </div>

            <!-- Export Buttons: copy | csv | xl | pdf | print + Add License -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Action Export Group -->
                <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-gray-50 p-0.5 text-xs font-bold">
                    <!-- Copy Button -->
                    <button type="button"
                            id="btnCopyTable"
                            onclick="copyLicenseTable()"
                            title="Copy table data to clipboard"
                            class="px-3 py-1.5 hover:bg-white text-gray-700 hover:text-blue-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-copy text-gray-500"></i>
                        <span>copy</span>
                    </button>

                    <!-- CSV Button -->
                    <a href="{{ route('admin.licenses.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                       title="Export filtered records as CSV"
                       class="px-3 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-csv text-emerald-600"></i>
                        <span>csv</span>
                    </a>

                    <!-- Excel (xl) Button -->
                    <a href="{{ route('admin.licenses.export', array_merge(request()->query(), ['format' => 'xl'])) }}"
                       title="Export filtered records as Excel (.xls)"
                       class="px-3 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-700 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-excel text-emerald-600"></i>
                        <span>xl</span>
                    </a>

                    <!-- PDF Button -->
                    <a href="{{ route('admin.licenses.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                       target="_blank"
                       title="Open printable document format for PDF"
                       class="px-3 py-1.5 hover:bg-white text-gray-700 hover:text-rose-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-pdf text-rose-500"></i>
                        <span>pdf</span>
                    </a>

                    <!-- Print Button -->
                    <a href="{{ route('admin.licenses.export', array_merge(request()->query(), ['format' => 'print'])) }}"
                       target="_blank"
                       title="Direct Print Layout"
                       class="px-3 py-1.5 hover:bg-white text-gray-700 hover:text-indigo-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-print text-indigo-600"></i>
                        <span>print</span>
                    </a>
                </div>

                <!-- Create License Page Link -->
                <a href="{{ route('admin.licenses.create_legacy') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                    <i class="fa fa-plus"></i>
                    <span>Create License</span>
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <form id="licenseFilterForm" method="GET" action="{{ route('admin.licenses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div class="relative sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by license key, shop name, phone..."
                       class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()"
                        class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <option value="">-- All Statuses --</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved / Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected / Suspended</option>
                </select>
            </div>

            <div>
                <select name="shop_id" onchange="this.form.submit()"
                        class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <option value="">-- All Shops --</option>
                    @foreach($shops as $sh)
                        <option value="{{ $sh->id }}" {{ request('shop_id') == $sh->id ? 'selected' : '' }}>
                            {{ $sh->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5">
                    <i class="fa fa-filter text-[11px]"></i>
                    <span>Apply Filter</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'shop_id', 'start_date', 'end_date', 'license_type']))
                    <a href="{{ route('admin.licenses.index') }}" title="Clear Filters"
                       class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-semibold transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <!-- Table columns: No | Shop Name | License Key | Start Date | End Date | Action -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="licenseTable" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4 w-14 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[200px]">Shop Name</th>
                        <th class="py-3.5 px-4 min-w-[180px]">License Key</th>
                        <th class="py-3.5 px-4 min-w-[120px]">Start Date</th>
                        <th class="py-3.5 px-4 min-w-[120px]">End Date</th>
                        <th class="py-3.5 px-4 text-center min-w-[150px]">Action</th>
                    </tr>
                </thead>
                <tbody id="licenseTableBody" class="divide-y divide-gray-100 font-medium">
                    @forelse($licenses as $index => $lic)
                    @php
                        $rowNumber = ($licenses->currentPage() - 1) * $licenses->perPage() + $loop->iteration;
                        $shopName = $lic->shop_name ?: ($lic->shop ? $lic->shop->name : 'Unassigned Shop');
                        $key = $lic->license_key ?: ($lic->license_number ?: 'N/A');
                        $startDate = $lic->start_date ? $lic->start_date->format('M d, Y') : ($lic->issue_date ? $lic->issue_date->format('M d, Y') : '—');
                        $endDate = $lic->end_date ? $lic->end_date->format('M d, Y') : ($lic->expiry_date ? $lic->expiry_date->format('M d, Y') : 'Lifetime / Open');
                        $isExpired = ($lic->end_date && $lic->end_date->isPast()) || ($lic->expiry_date && $lic->expiry_date->isPast());
                    @endphp
                    <tr class="hover:bg-blue-50/40 transition group">
                        <!-- Column 1: No -->
                        <td class="py-3.5 px-4 text-center font-bold text-gray-500 font-mono">
                            {{ $rowNumber }}
                        </td>

                        <!-- Column 2: Shop Name -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0 border border-blue-100">
                                    <i class="fa fa-store"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition truncate">
                                        {{ $shopName }}
                                    </h4>
                                    <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                        @if($lic->shop && $lic->shop->phone)
                                            <span><i class="fa fa-phone text-[9px] mr-1"></i>{{ $lic->shop->phone }}</span>
                                        @endif
                                        @if($lic->license_type)
                                            <span class="px-1.5 py-0.2 rounded bg-gray-100 text-gray-600 font-semibold">{{ $lic->license_type }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Column 3: License Key -->
                        <td class="py-3.5 px-4 font-mono font-bold text-gray-800">
                            <div class="inline-flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200 text-xs transition">
                                <span class="text-blue-700 tracking-wide font-mono">{{ $key }}</span>
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $key }}'); showToast('License Key copied: {{ $key }}')"
                                        title="Copy License Key"
                                        class="text-gray-400 hover:text-blue-600 transition p-0.5">
                                    <i class="fa fa-copy text-[11px]"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Column 4: Start Date -->
                        <td class="py-3.5 px-4 text-gray-700">
                            <div class="flex items-center gap-1.5">
                                <i class="fa fa-calendar-check text-emerald-500 text-[11px]"></i>
                                <span class="font-semibold">{{ $startDate }}</span>
                            </div>
                        </td>

                        <!-- Column 5: End Date -->
                        <td class="py-3.5 px-4 text-gray-700">
                            <div class="flex items-center gap-1.5">
                                <i class="fa {{ $isExpired ? 'fa-calendar-xmark text-rose-500' : 'fa-calendar text-blue-500' }} text-[11px]"></i>
                                <span class="font-semibold {{ $isExpired ? 'text-rose-600 font-bold' : '' }}">
                                    {{ $endDate }}
                                </span>
                            </div>
                            @if($isExpired)
                                <span class="inline-block text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 mt-0.5">Expired</span>
                            @endif
                        </td>

                        <!-- Column 6: Action -->
                        <td class="py-3.5 px-4 text-center space-x-1 whitespace-nowrap">
                            <!-- View / Details Trigger -->
                            <button type="button"
                                    onclick="viewLicenseDetails({{ json_encode($lic) }})"
                                    title="View License Details"
                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition">
                                <i class="fa fa-eye text-[11px]"></i>
                            </button>

                            <!-- Edit Button linking to /super_admin/license_update/{id} -->
                            <a href="{{ route('admin.licenses.edit_legacy', $lic->id) }}"
                               title="Edit License (super_admin/license_update/{{ $lic->id }})"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-amber-50 text-gray-600 hover:text-amber-600 transition">
                                <i class="fa fa-pen text-[11px]"></i>
                            </a>

                            <!-- Toggle Status: Approve / Verify -->
                            @if($lic->status !== 'approved')
                            <form action="{{ route('admin.licenses.update-status', $lic->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition" title="Approve / Activate License">
                                    <i class="fa fa-check text-[11px]"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.licenses.update-status', $lic->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="expired">
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition" title="Mark as Expired">
                                    <i class="fa fa-clock text-[11px]"></i>
                                </button>
                            </form>
                            @endif

                            <!-- Delete Form -->
                            <form action="{{ route('admin.licenses.destroy', $lic->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete license record [{{ $key }}]?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition" title="Delete License">
                                    <i class="fa fa-trash-alt text-[11px]"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-2">
                                    <i class="fa fa-certificate text-xl"></i>
                                </div>
                                <p class="font-bold text-gray-600 text-sm">No shop licenses found</p>
                                <p class="text-xs text-gray-400 mt-0.5">Try adjusting your search criteria or register a new license.</p>
                                <a href="{{ route('admin.licenses.create_legacy') }}" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition">
                                    <i class="fa fa-plus mr-1"></i> Add First License
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($licenses->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <span class="text-xs text-gray-500 font-medium">
                Showing {{ $licenses->firstItem() }} to {{ $licenses->lastItem() }} of {{ $licenses->total() }} licenses
            </span>
            <div>
                {{ $licenses->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- View Details Modal -->
<div id="viewLicenseModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-extrabold text-gray-800 flex items-center gap-2">
                <i class="fa fa-certificate text-blue-600"></i>
                <span>Shop License Details</span>
            </h3>
            <button onclick="document.getElementById('viewLicenseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div class="p-3 bg-blue-50/60 rounded-xl border border-blue-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] uppercase font-bold text-blue-600">License Key</p>
                    <p id="view_key" class="text-base font-black font-mono text-gray-900"></p>
                </div>
                <span id="view_status_badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Shop Name</p>
                    <p id="view_shop_name" class="font-bold text-gray-800 mt-0.5"></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">License / Permit Type</p>
                    <p id="view_type" class="font-bold text-gray-800 mt-0.5"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Start Date</p>
                    <p id="view_start_date" class="font-bold text-emerald-700 mt-0.5"></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">End Date</p>
                    <p id="view_end_date" class="font-bold text-gray-800 mt-0.5"></p>
                </div>
            </div>

            <div id="view_document_section" class="p-3 bg-gray-50 rounded-xl border border-gray-100 hidden">
                <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Attached Document / Certificate</p>
                <a id="view_document_link" href="#" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-bold underline">
                    <i class="fa fa-file-pdf"></i>
                    <span>Download / Open Certificate Scan</span>
                </a>
            </div>

            <div id="view_notes_section" class="p-3 bg-gray-50 rounded-xl border border-gray-100 hidden">
                <p class="text-[10px] uppercase font-bold text-gray-400">Inspector / Admin Remarks</p>
                <p id="view_notes" class="text-gray-700 mt-0.5"></p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
            <a id="view_edit_link" href="#" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa fa-pen"></i>
                <span>Edit License</span>
            </a>
            <button type="button" onclick="document.getElementById('viewLicenseModal').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Floating Toast Container for Clipboard Copy -->
<div id="toastContainer" class="fixed bottom-5 right-5 z-50 space-y-2 pointer-events-none"></div>

<script>
// Copy Table to Clipboard as TSV
function copyLicenseTable() {
    const table = document.getElementById('licenseTable');
    if (!table) return;

    let rows = [];
    // Header row
    rows.push(['No', 'Shop Name', 'License Key', 'Start Date', 'End Date'].join('\t'));

    const tbody = document.getElementById('licenseTableBody');
    const trs = tbody.querySelectorAll('tr');

    let count = 0;
    trs.forEach(tr => {
        const cells = tr.querySelectorAll('td');
        if (cells.length >= 5) {
            const no = cells[0].innerText.trim();
            const shop = cells[1].querySelector('h4') ? cells[1].querySelector('h4').innerText.trim() : cells[1].innerText.trim();
            const keyEl = cells[2].querySelector('span.tracking-wide');
            const key = keyEl ? keyEl.innerText.trim() : cells[2].innerText.trim();
            const start = cells[3].innerText.trim();
            const end = cells[4].innerText.trim().replace('Expired', '').trim();

            rows.push([no, shop, key, start, end].join('\t'));
            count++;
        }
    });

    if (count === 0) {
        showToast('No license rows to copy.', 'error');
        return;
    }

    const tsvContent = rows.join('\n');
    navigator.clipboard.writeText(tsvContent).then(() => {
        showToast(`Copied ${count} license rows to clipboard!`, 'success');
    }).catch(err => {
        console.error('Copy failed: ', err);
        showToast('Failed to copy to clipboard', 'error');
    });
}

// Display toast message
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-gray-900 text-white' : 'bg-rose-600 text-white';
    const icon = type === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation';

    toast.className = `${bgColor} px-4 py-2.5 rounded-xl shadow-lg text-xs font-semibold flex items-center gap-2 transition-all duration-300 transform translate-y-2 opacity-0 pointer-events-auto`;
    toast.innerHTML = `<i class="fa ${icon}"></i><span>${message}</span>`;

    container.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
    }, 10);

    // Dismiss
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// View License Details
function viewLicenseDetails(lic) {
    const key = lic.license_key || lic.license_number || 'N/A';
    document.getElementById('view_key').innerText = key;
    document.getElementById('view_shop_name').innerText = lic.shop_name || (lic.shop ? lic.shop.name : 'Unassigned Shop');
    document.getElementById('view_type').innerText = lic.license_type || 'Trade License';

    const startDate = lic.start_date ? lic.start_date.substring(0, 10) : (lic.issue_date ? lic.issue_date.substring(0, 10) : 'N/A');
    const endDate = lic.end_date ? lic.end_date.substring(0, 10) : (lic.expiry_date ? lic.expiry_date.substring(0, 10) : 'Lifetime / Open');
    document.getElementById('view_start_date').innerText = startDate;
    document.getElementById('view_end_date').innerText = endDate;

    const statusBadge = document.getElementById('view_status_badge');
    statusBadge.innerText = (lic.status || 'approved').toUpperCase();
    if (lic.status === 'approved') {
        statusBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800';
    } else if (lic.status === 'pending') {
        statusBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800';
    } else {
        statusBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800';
    }

    // Document
    const docSec = document.getElementById('view_document_section');
    if (lic.document_file) {
        docSec.classList.remove('hidden');
        document.getElementById('view_document_link').href = lic.document_file.startsWith('http') ? lic.document_file : ('/' + lic.document_file.replace(/^\//, ''));
    } else {
        docSec.classList.add('hidden');
    }

    // Notes
    const notesSec = document.getElementById('view_notes_section');
    if (lic.notes) {
        notesSec.classList.remove('hidden');
        document.getElementById('view_notes').innerText = lic.notes;
    } else {
        notesSec.classList.add('hidden');
    }

    // Edit link
    document.getElementById('view_edit_link').href = '/super_admin/license_update/' + lic.id;

    document.getElementById('viewLicenseModal').classList.remove('hidden');
}
</script>
@endsection
