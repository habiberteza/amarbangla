@extends('admin.layout')

@section('title', 'Seller Management System - Amar Bangla Super Admin')
@section('page-title', 'Seller Management System (super_admin/sellers)')

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

    @if(session('error'))
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-triangle-exclamation text-rose-600 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Top KPI Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Sellers</p>
                <h3 class="text-2xl font-black text-gray-800 mt-0.5">{{ number_format($totalCount ?? $users->total()) }}</h3>
                <span class="text-[10px] text-blue-600 font-medium">Registered Merchants</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Active Sellers</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-0.5">{{ number_format($activeCount ?? 0) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">In Good Standing</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-user-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Suspended / Inactive</p>
                <h3 class="text-2xl font-black text-rose-600 mt-0.5">{{ number_format($suspendedCount ?? 0) }}</h3>
                <span class="text-[10px] text-rose-600 font-medium">Requires Attention</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-user-slash"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">New This Month</p>
                <h3 class="text-2xl font-black text-amber-600 mt-0.5">{{ number_format($newThisMonth ?? 0) }}</h3>
                <span class="text-[10px] text-amber-600 font-medium">Recent Onboarding</span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-base shadow-sm">
                <i class="fa fa-user-plus"></i>
            </div>
        </div>
    </div>

    <!-- Advanced Filter & Geographic Multi-Criteria Search Section -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-filter"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Filter Sellers by Geographic Region & Territory</h4>
                    <p class="text-[11px] text-gray-500">Filter by Division, District, Upazila, Pourashava/Union, Ward</p>
                </div>
            </div>

            <!-- Export Buttons: copy | CSV | xl | pdf | print + Add New Seller -->
            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-gray-50 p-0.5 text-xs font-bold">
                    <!-- Copy Button -->
                    <button type="button"
                            onclick="copySellerTable()"
                            title="Copy seller table rows to clipboard"
                            class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-blue-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-copy text-gray-500"></i>
                        <span>copy</span>
                    </button>

                    <!-- CSV Button -->
                    <a href="{{ route('admin.vendors.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                       title="Export filtered sellers as CSV"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-csv text-emerald-600"></i>
                        <span>CSV</span>
                    </a>

                    <!-- Excel (xl) Button -->
                    <a href="{{ route('admin.vendors.export', array_merge(request()->query(), ['format' => 'excel'])) }}"
                       title="Export filtered sellers as Excel (xl)"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-700 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-excel text-emerald-600"></i>
                        <span>xl</span>
                    </a>

                    <!-- PDF Button -->
                    <a href="{{ route('admin.vendors.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                       target="_blank"
                       title="Export printable PDF format"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-rose-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-pdf text-rose-500"></i>
                        <span>pdf</span>
                    </a>

                    <!-- Print Button -->
                    <a href="{{ route('admin.vendors.export', array_merge(request()->query(), ['format' => 'print'])) }}"
                       target="_blank"
                       title="Direct Print Layout"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-indigo-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-print text-indigo-600"></i>
                        <span>print</span>
                    </a>
                </div>

                <!-- Add New Seller Button linking to /super_admin/sellers_create -->
                <a href="{{ route('admin.sellers.create_legacy') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                    <i class="fa fa-user-plus"></i>
                    <span>Add New Seller</span>
                </a>
            </div>
        </div>

        <!-- Filter Form with Dynamic Cascading Dropdowns -->
        <form id="sellerFilterForm" method="GET" action="{{ route('admin.vendors.index') }}" class="space-y-4">
            <!-- Row 1: Search keyword + Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="sm:col-span-3 relative">
                    <input type="text" name="search" id="filter_search" value="{{ request('search') }}"
                           placeholder="Search sellers by name, phone, mobile, email, or father name..."
                           class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                </div>

                <div>
                    <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">-- All Statuses --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Geographic Filters (Division, District, Upazila, Pourashava/Union, Ward) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 pt-2 border-t border-gray-100">
                <!-- 1. Division -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Division</label>
                    <select name="division" id="sel_division"
                            class="w-full py-2 px-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Divisions</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" {{ request('division') == $div ? 'selected' : '' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 2. District -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">District</label>
                    <select name="district" id="sel_district"
                            class="w-full py-2 px-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Districts</option>
                        @foreach($districts as $dst)
                            <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>{{ $dst }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Upazila -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Upazila</label>
                    <select name="upazila" id="sel_upazila"
                            class="w-full py-2 px-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Upazilas</option>
                        @foreach($upazilas as $upz)
                            <option value="{{ $upz }}" {{ request('upazila') == $upz ? 'selected' : '' }}>{{ $upz }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Pourashava/Union -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Pourashava/Union</label>
                    <select name="pourashava" id="sel_pourashava"
                            class="w-full py-2 px-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Pourashavas</option>
                        @foreach($pourashavas as $p)
                            <option value="{{ $p }}" {{ request('pourashava') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 5. Ward -->
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Ward</label>
                    <select name="ward" id="sel_ward"
                            class="w-full py-2 px-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">All Wards</option>
                        @foreach($wards as $w)
                            <option value="{{ $w }}" {{ request('ward') == $w ? 'selected' : '' }}>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Submit & Reset Action Buttons -->
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                @if(request()->hasAny(['search', 'status', 'division', 'district', 'upazila', 'pourashava', 'ward']))
                    <a href="{{ route('admin.vendors.index') }}"
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

    <!-- Sellers Table Card -->
    <!-- Exact Table Columns: No | Name | Phone | Action -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="sellerTable" class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/90 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4 w-14 text-center">No</th>
                        <th class="py-3.5 px-4 min-w-[240px]">Name</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Phone</th>
                        <th class="py-3.5 px-4 text-center min-w-[150px]">Action</th>
                    </tr>
                </thead>
                <tbody id="sellerTableBody" class="divide-y divide-gray-100 font-medium">
                    @forelse($users as $index => $seller)
                    @php
                        $rowNumber = ($users->currentPage() - 1) * $users->perPage() + $loop->iteration;
                        $phone = $seller->phone ?? ($seller->mobile ?? 'N/A');
                        $photoUrl = $seller->image ? (str_starts_with($seller->image, 'http') ? $seller->image : asset($seller->image)) : null;
                    @endphp
                    <tr class="hover:bg-blue-50/40 transition group">
                        <!-- 1. No -->
                        <td class="py-3.5 px-4 text-center font-bold text-gray-500 font-mono">
                            {{ $rowNumber }}
                        </td>

                        <!-- 2. Name -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}" alt="{{ $seller->name }}"
                                         class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-xs shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs shrink-0">
                                        {{ substr($seller->name, 0, 2) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition truncate text-xs">
                                            {{ $seller->name }}
                                        </h4>
                                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded uppercase {{ ($seller->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $seller->status ?? 'active' }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-gray-400 truncate">
                                        {{ $seller->email }}
                                    </div>
                                    @if($seller->division || $seller->district)
                                    <div class="text-[10px] text-gray-400 mt-0.5">
                                        <i class="fa fa-location-dot text-[9px] mr-1 text-gray-300"></i>
                                        <span>{{ implode(', ', array_filter([$seller->upazila, $seller->district, $seller->division])) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- 3. Phone -->
                        <td class="py-3.5 px-4">
                            <div class="inline-flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200 text-xs font-mono font-bold text-gray-800 transition">
                                <i class="fa fa-phone text-emerald-600 text-[10px]"></i>
                                <span>{{ $phone }}</span>
                                @if($phone !== 'N/A')
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $phone }}'); showToast('Phone copied: {{ $phone }}')"
                                        title="Copy Phone Number"
                                        class="text-gray-400 hover:text-blue-600 transition p-0.5 ml-1">
                                    <i class="fa fa-copy text-[10px]"></i>
                                </button>
                                @endif
                            </div>
                        </td>

                        <!-- 4. Action -->
                        <td class="py-3.5 px-4 text-center space-x-1 whitespace-nowrap">
                            <!-- View Details Modal Trigger -->
                            <button type="button"
                                    onclick="viewSellerDetails({{ json_encode($seller) }})"
                                    title="View Seller Profile"
                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition">
                                <i class="fa fa-eye text-[11px]"></i>
                            </button>

                            <!-- 1. Orders: /super_admin/sellers_order/{id} -->
                            <a href="/super_admin/sellers_order/{{ $seller->id }}"
                               title="Orders (super_admin/sellers_order/{{ $seller->id }})"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition shadow-2xs">
                                <i class="fa fa-shopping-cart text-[11px]"></i>
                            </a>

                            <!-- 2. Commission: /super_admin/sellers_commission/{id} -->
                            <a href="/super_admin/sellers_commission/{{ $seller->id }}"
                               title="Commission (super_admin/sellers_commission/{{ $seller->id }})"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-purple-50 hover:bg-purple-600 text-purple-600 hover:text-white transition shadow-2xs">
                                <i class="fa fa-percent text-[11px]"></i>
                            </a>

                            <!-- 3. Ledger: /super_admin/sellers_ledger/{id} -->
                            <a href="/super_admin/sellers_ledger/{{ $seller->id }}"
                               title="Financial Ledger (super_admin/sellers_ledger/{{ $seller->id }})"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white transition shadow-2xs">
                                <i class="fa fa-wallet text-[11px]"></i>
                            </a>

                            <!-- Edit Button linking to /super_admin/sellers_update/{id} -->
                            <a href="/super_admin/sellers_update/{{ $seller->id }}"
                               title="Edit Seller (super_admin/sellers_update/{{ $seller->id }})"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-600 text-amber-600 hover:text-white transition shadow-2xs">
                                <i class="fa fa-pen text-[11px]"></i>
                            </a>

                            <!-- Toggle Status Form -->
                            <form action="{{ route('admin.sellers.toggle-status', $seller->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit"
                                        title="{{ $seller->status === 'active' ? 'Suspend Seller' : 'Activate Seller' }}"
                                        class="w-7 h-7 inline-flex items-center justify-center rounded-lg {{ $seller->status === 'active' ? 'bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white' }} transition shadow-2xs">
                                    <i class="fa {{ $seller->status === 'active' ? 'fa-ban' : 'fa-check' }} text-[11px]"></i>
                                </button>
                            </form>

                            <!-- Delete Form -->
                            <form action="{{ route('admin.vendors.destroy', $seller) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete seller \"{{ $seller->name }}\"?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        title="Delete Seller Account"
                                        class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-600 text-gray-600 hover:text-white transition shadow-2xs">
                                    <i class="fa fa-trash-alt text-[11px]"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-2">
                                    <i class="fa fa-users text-xl"></i>
                                </div>
                                <p class="font-bold text-gray-600 text-sm">No sellers found</p>
                                <p class="text-xs text-gray-400 mt-0.5">Try adjusting your filters or add a new seller account.</p>
                                <a href="{{ route('admin.sellers.create_legacy') }}" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition">
                                    <i class="fa fa-plus mr-1"></i> Add New Seller
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <span class="text-xs text-gray-500 font-medium">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} sellers
            </span>
            <div>
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- Seller View Details Modal -->
<div id="viewSellerModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-extrabold text-gray-800 flex items-center gap-2">
                <i class="fa fa-id-card text-blue-600"></i>
                <span>Seller Profile Details</span>
            </h3>
            <button onclick="document.getElementById('viewSellerModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <div class="space-y-3 text-xs">
            <div class="flex items-center gap-3 p-3 bg-blue-50/60 rounded-xl border border-blue-100">
                <div id="view_avatar" class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm overflow-hidden">
                </div>
                <div class="flex-1 min-w-0">
                    <h4 id="view_name" class="font-extrabold text-gray-900 text-sm"></h4>
                    <p id="view_email" class="text-gray-500"></p>
                </div>
                <span id="view_status_badge" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"></span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Mobile / Phone</p>
                    <p id="view_phone" class="font-bold font-mono text-gray-800 mt-0.5"></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Assigned Agent</p>
                    <p id="view_agent" class="font-bold text-gray-800 mt-0.5">Not Assigned</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Father's Name</p>
                    <p id="view_father" class="font-semibold text-gray-800 mt-0.5">—</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400">Mother's Name</p>
                    <p id="view_mother" class="font-semibold text-gray-800 mt-0.5">—</p>
                </div>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-[10px] uppercase font-bold text-gray-400">Territory / Address</p>
                <p id="view_address" class="font-medium text-gray-800 mt-0.5"></p>
            </div>
        </div>

        <div class="flex items-center justify-between gap-2 pt-3 border-t border-gray-100 flex-wrap">
            <div class="flex items-center gap-1.5 flex-wrap">
                <a id="view_orders_link" href="#" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                    <i class="fa fa-shopping-cart text-[10px]"></i>
                    <span>Orders</span>
                </a>
                <a id="view_commission_link" href="#" class="px-3 py-1.5 bg-purple-50 hover:bg-purple-600 text-purple-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                    <i class="fa fa-percent text-[10px]"></i>
                    <span>Commission</span>
                </a>
                <a id="view_ledger_link" href="#" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1">
                    <i class="fa fa-wallet text-[10px]"></i>
                    <span>Ledger</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <a id="view_edit_link" href="#" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa fa-pen"></i>
                    <span>Edit Profile</span>
                </a>
                <button type="button" onclick="document.getElementById('viewSellerModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Floating Toast Container for Clipboard Copy -->
<div id="toastContainer" class="fixed bottom-5 right-5 z-50 space-y-2 pointer-events-none"></div>

<script>
// Copy Table to Clipboard as TSV
function copySellerTable() {
    let rows = [];
    rows.push(['No', 'Name', 'Phone'].join('\t'));

    const tbody = document.getElementById('sellerTableBody');
    const trs = tbody.querySelectorAll('tr');

    let count = 0;
    trs.forEach(tr => {
        const cells = tr.querySelectorAll('td');
        if (cells.length >= 3) {
            const no = cells[0].innerText.trim();
            const nameEl = cells[1].querySelector('h4');
            const name = nameEl ? nameEl.innerText.trim() : cells[1].innerText.trim();
            const phoneEl = cells[2].querySelector('span');
            const phone = phoneEl ? phoneEl.innerText.trim() : cells[2].innerText.trim();

            rows.push([no, name, phone].join('\t'));
            count++;
        }
    });

    if (count === 0) {
        showToast('No seller rows to copy.', 'error');
        return;
    }

    const tsvContent = rows.join('\n');
    navigator.clipboard.writeText(tsvContent).then(() => {
        showToast(`Copied ${count} seller rows to clipboard!`, 'success');
    }).catch(err => {
        console.error('Copy failed: ', err);
        showToast('Failed to copy to clipboard', 'error');
    });
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-gray-900 text-white' : 'bg-rose-600 text-white';
    const icon = type === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation';

    toast.className = `${bgColor} px-4 py-2.5 rounded-xl shadow-lg text-xs font-semibold flex items-center gap-2 transition-all duration-300 transform translate-y-2 opacity-0 pointer-events-auto`;
    toast.innerHTML = `<i class="fa ${icon}"></i><span>${message}</span>`;

    container.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-y-2', 'opacity-0'), 10);
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function viewSellerDetails(seller) {
    document.getElementById('view_name').innerText = seller.name;
    document.getElementById('view_email').innerText = seller.email || '';
    document.getElementById('view_phone').innerText = seller.phone || seller.mobile || 'N/A';
    document.getElementById('view_father').innerText = seller.father_name || '—';
    document.getElementById('view_mother').innerText = seller.mother_name || '—';
    document.getElementById('view_agent').innerText = seller.agent ? seller.agent.name : 'Not Assigned';

    const addressParts = [seller.ward ? 'Ward: ' + seller.ward : '', seller.pourashava, seller.upazila, seller.district, seller.division].filter(Boolean);
    document.getElementById('view_address').innerText = addressParts.length ? addressParts.join(', ') : 'No geographic territory set';

    const statusBadge = document.getElementById('view_status_badge');
    const status = (seller.status || 'active').toUpperCase();
    statusBadge.innerText = status;
    statusBadge.className = 'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ' +
        (seller.status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800');

    const avatarContainer = document.getElementById('view_avatar');
    if (seller.image) {
        const src = seller.image.startsWith('http') ? seller.image : ('/' + seller.image.replace(/^\//, ''));
        avatarContainer.innerHTML = `<img src="${src}" class="w-full h-full object-cover">`;
    } else {
        avatarContainer.innerHTML = seller.name ? seller.name.substring(0, 2).toUpperCase() : 'SE';
    }

    document.getElementById('view_edit_link').href = '/super_admin/sellers_update/' + seller.id;
    document.getElementById('view_orders_link').href = '/super_admin/sellers_order/' + seller.id;
    document.getElementById('view_commission_link').href = '/super_admin/sellers_commission/' + seller.id;
    document.getElementById('view_ledger_link').href = '/super_admin/sellers_ledger/' + seller.id;
    document.getElementById('viewSellerModal').classList.remove('hidden');
}

// Dynamic Cascading for Dropdowns: Division -> District -> Upazila
document.addEventListener('DOMContentLoaded', function() {
    const selDiv = document.getElementById('sel_division');
    const selDist = document.getElementById('sel_district');
    const selUpz = document.getElementById('sel_upazila');

    if (selDiv && selDist) {
        selDiv.addEventListener('change', function() {
            const division = this.value;
            fetch(`/super_admin/global_address/cascade_options?division=${encodeURIComponent(division)}`)
                .then(r => r.json())
                .then(data => {
                    selDist.innerHTML = '<option value="">All Districts</option>';
                    if (data.districts) {
                        data.districts.forEach(d => {
                            selDist.innerHTML += `<option value="${d}">${d}</option>`;
                        });
                    }
                    if (selUpz) selUpz.innerHTML = '<option value="">All Upazilas</option>';
                }).catch(e => console.error(e));
        });
    }

    if (selDist && selUpz) {
        selDist.addEventListener('change', function() {
            const district = this.value;
            const division = selDiv ? selDiv.value : '';
            fetch(`/super_admin/global_address/cascade_options?division=${encodeURIComponent(division)}&district=${encodeURIComponent(district)}`)
                .then(r => r.json())
                .then(data => {
                    selUpz.innerHTML = '<option value="">All Upazilas</option>';
                    if (data.upazilas) {
                        data.upazilas.forEach(u => {
                            selUpz.innerHTML += `<option value="${u}">${u}</option>`;
                        });
                    }
                }).catch(e => console.error(e));
        });
    }
});
</script>
@endsection
