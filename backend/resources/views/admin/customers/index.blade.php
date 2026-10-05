@extends('admin.layout')

@section('title', 'Customer Directory - Amar Bangla Super Admin')
@section('page-title', 'Customer Directory & Profiles (super_admin/customer)')

@section('content')
<div class="space-y-6">

    <!-- Flash message alerts -->
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

    <!-- Top KPI Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Customers</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ number_format(\App\Models\Customer::count()) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Profiles Registered</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Active Status</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ number_format(\App\Models\Customer::where('status', 'active')->count()) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Active Accounts</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Balance</p>
                @php $totalCustomerBalance = \App\Models\Customer::sum('balance'); @endphp
                <h3 class="text-xl font-extrabold {{ $totalCustomerBalance < 0 ? 'text-rose-600' : 'text-blue-700' }} mt-0.5">
                    ৳ {{ number_format($totalCustomerBalance, 2) }}
                </h3>
                <span class="text-[10px] text-blue-600 font-medium">Customer Wallets</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">With Orders</p>
                <h3 class="text-xl font-extrabold text-amber-600 mt-0.5">{{ number_format(\App\Models\Customer::whereHas('orders')->count()) }}</h3>
                <span class="text-[10px] text-amber-600 font-medium">Placed Orders</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                <i class="fa fa-cart-shopping"></i>
            </div>
        </div>
    </div>

    <!-- Advanced Filter & Multi-Criteria Search Section -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-filter"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Filter Customers by Geographic Region & Profile</h4>
                    <p class="text-[11px] text-gray-500">Filter by Division, District, Upazila, Pourashava/Union, Ward</p>
                </div>
            </div>

            <!-- Export Buttons: CSV, xl, pdf, print + Add New Customer -->
            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-gray-50 p-0.5 text-xs font-bold">
                    <a href="{{ route('admin.customers.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                       title="Export filtered customers as CSV"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-blue-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-csv text-emerald-600"></i>
                        <span>CSV</span>
                    </a>
                    <a href="{{ route('admin.customers.export', array_merge(request()->query(), ['format' => 'excel'])) }}"
                       title="Export filtered customers as Excel (xl)"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-700 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-excel text-emerald-600"></i>
                        <span>xl</span>
                    </a>
                    <a href="{{ route('admin.customers.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                       target="_blank"
                       title="Export printable PDF format"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-rose-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-pdf text-rose-500"></i>
                        <span>pdf</span>
                    </a>
                    <a href="{{ route('admin.customers.export', array_merge(request()->query(), ['format' => 'print'])) }}"
                       target="_blank"
                       title="Direct Print Layout"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-indigo-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-print text-indigo-600"></i>
                        <span>print</span>
                    </a>
                </div>

                <a href="{{ route('admin.customers.create') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                    <i class="fa fa-user-plus"></i>
                    <span>Add New Customer</span>
                </a>
            </div>
        </div>

        <form id="customerFilterForm" method="GET" action="{{ route('admin.customers.index') }}" class="space-y-4">
            <!-- Row 1: Search keyword + Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="sm:col-span-2 lg:col-span-3 relative">
                    <input type="text" name="search" id="filter_search" value="{{ request('search') }}"
                           placeholder="Search customer by name, phone, email, or address..."
                           class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    @if(request('search'))
                        <button type="button" onclick="document.getElementById('filter_search').value=''; document.getElementById('customerFilterForm').submit();" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-rose-500 text-xs">
                            <i class="fa fa-times"></i>
                        </button>
                    @endif
                </div>

                <div>
                    <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: 5 Geographic Filters (Division, District, Upazila, Pourashava/Union, Ward) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- 1. Division -->
                <div class="searchable-combobox-filter relative" id="filter_division_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-map text-blue-500 mr-1"></i> Division
                    </label>
                    <div class="relative">
                        <input type="text" name="division" id="filter_division" value="{{ request('division') }}"
                               autocomplete="off" placeholder="All Divisions..."
                               class="combobox-input w-full py-2 pl-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ request('division') ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase">
                                <span class="combobox-count">Divisions</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching division</div>
                        </div>
                    </div>
                </div>

                <!-- 2. District -->
                <div class="searchable-combobox-filter relative" id="filter_district_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-city text-indigo-500 mr-1"></i> District
                    </label>
                    <div class="relative">
                        <input type="text" name="district" id="filter_district" value="{{ request('district') }}"
                               autocomplete="off" placeholder="All Districts..."
                               class="combobox-input w-full py-2 pl-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ request('district') ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase">
                                <span class="combobox-count">Districts</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching district</div>
                        </div>
                    </div>
                </div>

                <!-- 3. Upazila -->
                <div class="searchable-combobox-filter relative" id="filter_upazila_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-building text-emerald-500 mr-1"></i> Upazila
                    </label>
                    <div class="relative">
                        <input type="text" name="upazila" id="filter_upazila" value="{{ request('upazila') }}"
                               autocomplete="off" placeholder="All Upazilas..."
                               class="combobox-input w-full py-2 pl-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ request('upazila') ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase">
                                <span class="combobox-count">Upazilas</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching upazila</div>
                        </div>
                    </div>
                </div>

                <!-- 4. Pourashava/Union -->
                <div class="searchable-combobox-filter relative" id="filter_pourashava_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-landmark text-amber-500 mr-1"></i> Pourashava/Union
                    </label>
                    <div class="relative">
                        <input type="text" name="pourashava" id="filter_pourashava" value="{{ request('pourashava') }}"
                               autocomplete="off" placeholder="All Pourashava/Union..."
                               class="combobox-input w-full py-2 pl-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ request('pourashava') ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase">
                                <span class="combobox-count">Pourashava/Union</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching records</div>
                        </div>
                    </div>
                </div>

                <!-- 5. Ward -->
                <div class="searchable-combobox-filter relative" id="filter_ward_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-hashtag text-purple-500 mr-1"></i> Ward
                    </label>
                    <div class="relative">
                        <input type="text" name="ward" id="filter_ward" value="{{ request('ward') }}"
                               autocomplete="off" placeholder="All Wards (e.g. ward 1)..."
                               class="combobox-input w-full py-2 pl-3 pr-12 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ request('ward') ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-4 h-4">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase">
                                <span class="combobox-count">Wards</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching ward</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit & Reset Actions -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                    Found <strong class="text-gray-800">{{ $customers->total() }}</strong> registered customers matching criteria
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'customer_type_id', 'status', 'division', 'district', 'upazila', 'pourashava', 'ward']))
                        <a href="{{ route('admin.customers.index') }}"
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa fa-rotate-left"></i>
                            <span>Reset Filters</span>
                        </a>
                    @endif
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                        <i class="fa fa-check"></i>
                        <span>Apply Filters</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Customer Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3.5 w-14 text-center">No</th>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Contact Details</th>
                        <th class="px-5 py-3.5">Created Date</th>
                        <th class="px-5 py-3.5 text-center">Orders</th>
                        <th class="px-5 py-3.5 text-right">Balance</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50/80 transition">
                        <!-- No -->
                        <td class="px-4 py-3.5 text-center font-bold text-gray-500 font-mono">
                            {{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}
                        </td>

                        <!-- Customer Name & Avatar -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="font-bold text-gray-900 text-sm hover:text-blue-600 block leading-tight">
                                        {{ $customer->name }}
                                    </a>
                                    <span class="text-[11px] text-gray-400">ID: #{{ $customer->customer_id ?? $customer->id }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Contact Details -->
                        <td class="px-5 py-4">
                            <a href="tel:{{ $customer->phone }}" class="text-xs font-semibold text-gray-800 hover:text-blue-600 flex items-center gap-1.5">
                                <i class="fa fa-phone text-[10px] text-gray-400"></i>
                                <span>{{ $customer->phone }}</span>
                            </a>
                            @if($customer->email)
                                <div class="text-[11px] text-gray-400 mt-0.5 truncate max-w-[180px]">{{ $customer->email }}</div>
                            @endif
                        </td>

                        <!-- Created Date -->
                        <td class="px-5 py-4 text-gray-600 font-medium">
                            @if($customer->created_at)
                                {{ $customer->created_at->format('d M Y') }}
                            @elseif($customer->createdDtm && $customer->createdDtm !== '0000-00-00 00:00:00')
                                {{ date('d M Y', strtotime($customer->createdDtm)) }}
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>

                        <!-- Orders Link -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <a href="{{ route('admin.customers.orders', $customer->customer_id ?? $customer->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg text-xs font-bold transition shadow-xs group"
                               title="Click to view all order history of {{ $customer->name }}">
                                <i class="fa fa-receipt text-emerald-600 group-hover:text-white text-[11px]"></i>
                                <span>{{ $customer->total_orders_count }} Orders</span>
                            </a>
                        </td>

                        <!-- Balance -->
                        <td class="px-5 py-4 text-right font-mono font-bold {{ $customer->balance < 0 ? 'text-rose-600' : 'text-gray-900' }}">
                            ৳ {{ number_format($customer->balance, 2) }}
                        </td>

                        <!-- Status -->
                        <td class="px-5 py-4 text-center">
                            <span class="capitalize text-[10px] font-bold px-2.5 py-1 rounded-full
                                {{ $customer->status === 'active' ? 'bg-emerald-100 text-emerald-800' :
                                   ($customer->status === 'suspended' ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ $customer->status }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Order List Action Button -->
                                <a href="{{ route('admin.customers.orders', $customer->customer_id ?? $customer->id) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs"
                                   title="View Customer Order List">
                                    <i class="fa fa-list text-[10px]"></i>
                                    <span>Orders</span>
                                </a>

                                <a href="{{ route('admin.customers.show', $customer) }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition" title="View Profile">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.customers.edit', $customer) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i class="fa fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                      onsubmit="return confirm('Permanently delete customer {{ $customer->name }}?');" class="inline">
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
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-lg">
                                <i class="fa fa-users-slash"></i>
                            </div>
                            <p class="font-bold text-gray-600 text-sm">No customers found</p>
                            <p class="text-xs text-gray-400 mt-1">Try refining your search keyword or geographic filters.</p>
                            @if(request()->hasAny(['search', 'customer_type_id', 'status', 'division', 'district', 'upazila', 'pourashava', 'ward']))
                                <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs text-blue-600 font-bold hover:underline">
                                    <i class="fa fa-rotate-left"></i>
                                    Clear All Filters
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-xs text-gray-500">
                Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers
            </div>
            <div>
                {{ $customers->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- Geographic Cascading Data & Searchable Combobox Logic -->
<script>
window.GLOBAL_ADDRESS_HIERARCHY = @json($hierarchy);

class SearchableCombobox {
    constructor(config) {
        this.container = document.getElementById(config.id);
        if (!this.container) return;

        this.input = this.container.querySelector('.combobox-input');
        this.clearBtn = this.container.querySelector('.combobox-clear');
        this.toggleBtn = this.container.querySelector('.combobox-toggle');
        this.chevron = this.toggleBtn ? this.toggleBtn.querySelector('i') : null;
        this.dropdown = this.container.querySelector('.combobox-dropdown');
        this.list = this.container.querySelector('.combobox-list');
        this.emptyMsg = this.container.querySelector('.combobox-empty');
        this.countEl = this.container.querySelector('.combobox-count');

        this.options = config.options || [];
        this.filteredOptions = [...this.options];
        this.badgeLabel = config.badgeLabel || 'Items';
        this.onSelect = config.onSelect || null;
        this.onChange = config.onChange || null;

        this.isOpen = false;
        this.highlightIndex = -1;

        this.init();
    }

    init() {
        this.input.addEventListener('focus', () => this.open());
        this.input.addEventListener('input', () => {
            this.updateClearBtn();
            this.filter(this.input.value);
            if (!this.isOpen) this.open();
            if (this.onChange) this.onChange(this.input.value);
        });

        this.input.addEventListener('keydown', (e) => {
            if (!this.isOpen) {
                if (e.key === 'ArrowDown' || e.key === 'Enter') {
                    this.open();
                    e.preventDefault();
                }
                return;
            }
            if (e.key === 'ArrowDown') {
                this.highlightNext();
                e.preventDefault();
            } else if (e.key === 'ArrowUp') {
                this.highlightPrev();
                e.preventDefault();
            } else if (e.key === 'Enter') {
                if (this.highlightIndex >= 0 && this.highlightIndex < this.filteredOptions.length) {
                    this.selectOption(this.filteredOptions[this.highlightIndex]);
                    e.preventDefault();
                }
            } else if (e.key === 'Escape') {
                this.close();
            }
        });

        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (this.isOpen) {
                    this.close();
                } else {
                    this.input.focus();
                    this.open();
                }
            });
        }

        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.setValue('', true);
                this.input.focus();
            });
        }

        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.close();
            }
        });

        this.updateClearBtn();
    }

    updateClearBtn() {
        if (!this.clearBtn) return;
        if (this.input.value.trim().length > 0) {
            this.clearBtn.classList.remove('hidden');
        } else {
            this.clearBtn.classList.add('hidden');
        }
    }

    setOptions(newOptions) {
        this.options = newOptions || [];
        this.filteredOptions = [...this.options];
        if (this.isOpen) {
            this.filter(this.input.value);
        }
    }

    setValue(val, triggerCallback = false) {
        this.input.value = val;
        this.updateClearBtn();
        if (this.isOpen) {
            this.filter(val);
        }
        if (triggerCallback) {
            if (this.onSelect) this.onSelect(val);
            if (this.onChange) this.onChange(val);
        }
    }

    open() {
        document.querySelectorAll('.combobox-dropdown').forEach(d => {
            if (d !== this.dropdown) d.classList.add('hidden');
        });
        document.querySelectorAll('.combobox-toggle i').forEach(icon => {
            icon.classList.remove('rotate-180');
        });

        this.dropdown.classList.remove('hidden');
        this.isOpen = true;
        if (this.chevron) this.chevron.classList.add('rotate-180');
        this.filter(this.input.value);
    }

    close() {
        this.dropdown.classList.add('hidden');
        this.isOpen = false;
        this.highlightIndex = -1;
        if (this.chevron) this.chevron.classList.remove('rotate-180');
    }

    filter(query) {
        const q = (query || '').toLowerCase().trim();
        if (!q) {
            this.filteredOptions = [...this.options];
        } else {
            this.filteredOptions = this.options.filter(opt => opt.toLowerCase().includes(q));
        }
        this.renderList(q);
    }

    renderList(query) {
        this.list.innerHTML = '';
        if (this.countEl) {
            this.countEl.textContent = `${this.filteredOptions.length} ${this.badgeLabel}`;
        }

        if (this.filteredOptions.length === 0) {
            this.emptyMsg.classList.remove('hidden');
            this.list.classList.add('hidden');
            return;
        }

        this.emptyMsg.classList.add('hidden');
        this.list.classList.remove('hidden');

        const currentValue = this.input.value.trim().toLowerCase();

        this.filteredOptions.forEach((opt, idx) => {
            const li = document.createElement('li');
            li.className = 'combobox-item px-3 py-2 cursor-pointer hover:bg-blue-50 flex items-center justify-between text-xs text-gray-700 transition';
            li.dataset.index = idx;

            let displayText = opt;
            if (query && opt.toLowerCase().includes(query)) {
                const regex = new RegExp(`(${this.escapeRegex(query)})`, 'gi');
                displayText = opt.replace(regex, '<mark class="bg-amber-100 text-amber-900 font-bold px-0.5 rounded">$1</mark>');
            }

            const isSelected = opt.toLowerCase() === currentValue;
            li.innerHTML = `
                <span class="${isSelected ? 'font-bold text-blue-600' : ''}">${displayText}</span>
                ${isSelected ? '<i class="fa fa-check text-blue-600 text-[10px]"></i>' : ''}
            `;

            li.addEventListener('mousedown', (e) => {
                e.preventDefault();
                this.selectOption(opt);
            });

            this.list.appendChild(li);
        });

        this.highlightIndex = -1;
    }

    selectOption(val) {
        this.setValue(val, true);
        this.close();
    }

    highlightNext() {
        const items = this.list.querySelectorAll('.combobox-item');
        if (items.length === 0) return;
        this.highlightIndex = (this.highlightIndex + 1) % items.length;
        this.updateHighlight(items);
    }

    highlightPrev() {
        const items = this.list.querySelectorAll('.combobox-item');
        if (items.length === 0) return;
        this.highlightIndex = (this.highlightIndex - 1 + items.length) % items.length;
        this.updateHighlight(items);
    }

    updateHighlight(items) {
        items.forEach((item, idx) => {
            if (idx === this.highlightIndex) {
                item.classList.add('bg-blue-100', 'text-blue-900');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('bg-blue-100', 'text-blue-900');
            }
        });
    }

    escapeRegex(str) {
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const HIERARCHY = window.GLOBAL_ADDRESS_HIERARCHY || {};

    const filterDivisionCombo = new SearchableCombobox({
        id: 'filter_division_box',
        options: HIERARCHY.divisions || [],
        badgeLabel: 'Divisions',
        onSelect: (selectedDiv) => {
            updateFilterDistricts(selectedDiv);
        },
        onChange: (val) => {
            updateFilterDistricts(val);
        }
    });

    const filterDistrictCombo = new SearchableCombobox({
        id: 'filter_district_box',
        options: HIERARCHY.allDistricts || [],
        badgeLabel: 'Districts',
        onSelect: (selectedDist) => {
            updateFilterUpazilas(selectedDist);
        },
        onChange: (val) => {
            updateFilterUpazilas(val);
        }
    });

    const filterUpazilaCombo = new SearchableCombobox({
        id: 'filter_upazila_box',
        options: HIERARCHY.allUpazilas || [],
        badgeLabel: 'Upazilas',
        onSelect: () => {},
        onChange: () => {}
    });

    const filterPourashavaCombo = new SearchableCombobox({
        id: 'filter_pourashava_box',
        options: HIERARCHY.pourashavas || [],
        badgeLabel: 'Pourashava/Union',
        onSelect: () => {},
        onChange: () => {}
    });

    const filterWardCombo = new SearchableCombobox({
        id: 'filter_ward_box',
        options: HIERARCHY.wards || [],
        badgeLabel: 'Wards',
        onSelect: () => {},
        onChange: () => {}
    });

    function updateFilterDistricts(divisionName) {
        const div = (divisionName || '').trim();
        let availableDistricts = [];

        if (div && HIERARCHY.districtsByDivision && HIERARCHY.districtsByDivision[div]) {
            availableDistricts = HIERARCHY.districtsByDivision[div];
        } else {
            availableDistricts = HIERARCHY.allDistricts || [];
        }

        filterDistrictCombo.setOptions(availableDistricts);

        const currentDistrict = document.getElementById('filter_district').value.trim();
        if (currentDistrict && !availableDistricts.includes(currentDistrict)) {
            filterDistrictCombo.setValue('');
            updateFilterUpazilas('');
        }
    }

    function updateFilterUpazilas(districtName) {
        const dist = (districtName || '').trim();
        let availableUpazilas = [];

        if (dist && HIERARCHY.upazilasByDistrict && HIERARCHY.upazilasByDistrict[dist]) {
            availableUpazilas = HIERARCHY.upazilasByDistrict[dist];
        } else {
            availableUpazilas = HIERARCHY.allUpazilas || [];
        }

        filterUpazilaCombo.setOptions(availableUpazilas);

        const currentUpazila = document.getElementById('filter_upazila').value.trim();
        if (currentUpazila && !availableUpazilas.includes(currentUpazila)) {
            filterUpazilaCombo.setValue('');
        }
    }
});
</script>
@endsection

