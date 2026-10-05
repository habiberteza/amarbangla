@extends('admin.layout')

@section('title', 'Delivery Boy - Amar Bangla Super Admin')
@section('page-title', 'Delivery Boys & Courier Dispatchers (super_admin/delivery_boy)')

@section('content')
<div class="space-y-6">

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

    <!-- Top Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Delivery Boys</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ number_format(\App\Models\DeliveryBoy::count()) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Registered Dispatchers</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-person-biking"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Active Status</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ number_format(\App\Models\DeliveryBoy::where('status', 'active')->count()) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Ready for Delivery</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Cash In Hand</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5">৳{{ number_format(\App\Models\DeliveryBoy::sum('cash_in_hand'), 2) }}</h3>
                <span class="text-[10px] text-indigo-600 font-medium">Collected from COD</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fa fa-money-bill-wave"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Balance</p>
                @php $totalBal = \App\Models\DeliveryBoy::sum('balance'); @endphp
                <h3 class="text-xl font-extrabold {{ $totalBal < 0 ? 'text-rose-600' : 'text-gray-800' }} mt-0.5">
                    ৳{{ number_format($totalBal, 2) }}
                </h3>
                <span class="text-[10px] text-gray-500 font-medium">Net Ledger Balance</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                <i class="fa fa-wallet"></i>
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
                    <h4 class="text-sm font-bold text-gray-800">Filter Delivery Boys by Area & Status</h4>
                    <p class="text-[11px] text-gray-500">Filter by Division, District, Upazila, Pourashava/Union, Ward (type to search & select from dropdown)</p>
                </div>
            </div>

            <a href="{{ route('admin.delivery_boys.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2 self-start sm:self-auto">
                <i class="fa fa-plus"></i>
                <span>Add Delivery Boy</span>
            </a>
        </div>

        <form id="filterForm" method="GET" action="{{ route('admin.delivery_boys.index') }}" class="space-y-4">
            <!-- Row 1: Keyword search + Vehicle Type + Status -->
            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="sm:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name, phone (017...), NID, or address..."
                           class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    @if(request('search'))
                        <button type="button" onclick="clearField('search')" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-rose-500 text-xs">
                            <i class="fa fa-times"></i>
                        </button>
                    @endif
                </div>

                <div>
                    <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <select name="vehicle_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Vehicles</option>
                        <option value="motorcycle" {{ request('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        <option value="bicycle" {{ request('vehicle_type') === 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                        <option value="van" {{ request('vehicle_type') === 'van' ? 'selected' : '' }}>Van / Pickup</option>
                        <option value="walking" {{ request('vehicle_type') === 'walking' ? 'selected' : '' }}>Walking</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: 5 Geographic Filters (Searchable & Dropdown) -->
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

            <!-- Action buttons -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <div class="text-[11px] text-gray-500 font-medium">
                    Found <span class="font-bold text-gray-800">{{ $riders->total() }}</span> delivery boys matching criteria
                </div>

                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'division', 'district', 'upazila', 'pourashava', 'ward', 'status', 'vehicle_type']))
                        <a href="{{ route('admin.delivery_boys.index') }}"
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                            <i class="fa fa-rotate-left text-[11px]"></i>
                            <span>Reset</span>
                        </a>
                    @endif

                    <button type="submit"
                            class="px-5 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                        <i class="fa fa-magnifying-glass"></i>
                        <span>Apply Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Delivery Boy Table with All Live Action Buttons -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-[11px] tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5 w-14">No</th>
                        <th class="px-5 py-3.5">Name</th>
                        <th class="px-5 py-3.5">Phone</th>
                        <th class="px-5 py-3.5">Balance</th>
                        <th class="px-5 py-3.5">Delivery Zone</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riders as $index => $rider)
                    <tr class="hover:bg-blue-50/30 transition">
                        <!-- 1. No -->
                        <td class="px-5 py-4 font-mono text-gray-400 font-semibold text-xs">
                            {{ $loop->iteration + ($riders->currentPage() - 1) * $riders->perPage() }}
                        </td>

                        <!-- 2. Name -->
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($rider->image)
                                    <img src="{{ asset($rider->image) }}" alt="{{ $rider->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow-sm">
                                        {{ strtoupper(substr($rider->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.delivery_boys.edit', $rider->id) }}" class="font-bold text-gray-900 text-sm hover:text-blue-600 transition block">
                                        {{ $rider->name }}
                                    </a>
                                    <div class="text-[11px] text-gray-500 flex items-center gap-2 mt-0.5">
                                        @if($rider->father_name)
                                            <span>F: {{ $rider->father_name }}</span>
                                        @endif
                                        @if($rider->age)
                                            <span class="px-1.5 py-0.2 bg-gray-100 rounded text-gray-600 text-[10px]">{{ $rider->age }} yrs</span>
                                        @endif
                                        @if($rider->agent)
                                            <span class="text-indigo-600 font-medium"><i class="fa fa-user-tie text-[9px] mr-0.5"></i>{{ $rider->agent->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- 3. Phone -->
                        <td class="px-5 py-4">
                            <a href="tel:{{ $rider->phone }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1.5 font-mono">
                                <i class="fa fa-phone text-[10px]"></i>
                                <span>{{ $rider->phone }}</span>
                            </a>
                            @if($rider->vehicle_type)
                                <div class="text-[10px] text-gray-400 capitalize mt-0.5 flex items-center gap-1">
                                    <i class="fa fa-bicycle text-[9px]"></i>
                                    <span>{{ $rider->vehicle_type }}</span>
                                </div>
                            @endif
                        </td>

                        <!-- 4. Balance (Matching live superadmin format e.g. ৳ -184.00) -->
                        <td class="px-5 py-4 font-mono font-bold text-xs whitespace-nowrap">
                            @php $bal = (float)$rider->balance; @endphp
                            <span class="px-2.5 py-1 rounded-lg {{ $bal < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                ৳ {{ number_format($bal, 2) }}
                            </span>
                            @if($rider->cash_in_hand > 0)
                                <div class="text-[10px] text-gray-400 mt-1 font-normal font-sans">
                                    Cash: ৳{{ number_format($rider->cash_in_hand, 2) }}
                                </div>
                            @endif
                        </td>

                        <!-- 5. Delivery Zone -->
                        <td class="px-5 py-4">
                            <div class="space-y-0.5">
                                <div class="font-semibold text-gray-800 text-xs">
                                    {{ $rider->upazila ?? $rider->district ?? 'All City' }}
                                </div>
                                <div class="text-[11px] text-gray-400 flex items-center gap-1 flex-wrap">
                                    @if($rider->district)
                                        <span>{{ $rider->district }}</span>
                                    @endif
                                    @if($rider->division)
                                        <span>&bull; {{ $rider->division }}</span>
                                    @endif
                                    @if($rider->pourashava)
                                        <span class="text-amber-600">&bull; {{ $rider->pourashava }}</span>
                                    @endif
                                    @if($rider->ward)
                                        <span class="text-purple-600">&bull; {{ $rider->ward }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- 6. Status -->
                        <td class="px-5 py-4">
                            @if($rider->status === 'active' || $rider->status == '1')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- 7. Action: Order, Commission, Ledger, Update, Delete -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <!-- 1. Order (Info/Cyan Button) -->
                                <a href="{{ route('admin.delivery_boys.orders', $rider->id) }}"
                                   class="px-2.5 py-1.5 bg-sky-50 hover:bg-sky-600 text-sky-700 hover:text-white border border-sky-200 hover:border-sky-600 rounded-lg text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
                                   title="Assigned Orders">
                                    <i class="fa fa-boxes-packing text-[10px]"></i>
                                    <span>Order</span>
                                </a>

                                <!-- 2. Commission (Primary/Indigo Button) -->
                                <a href="{{ route('admin.delivery_boys.commissions', $rider->id) }}"
                                   class="px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 hover:border-indigo-600 rounded-lg text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
                                   title="Commissions & Earnings">
                                    <i class="fa fa-percent text-[10px]"></i>
                                    <span>Commission</span>
                                </a>

                                <!-- 3. Ledger (Success/Emerald Button) -->
                                <a href="{{ route('admin.delivery_boys.ledger', $rider->id) }}"
                                   class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 hover:border-emerald-600 rounded-lg text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
                                   title="Financial Ledger & Balance">
                                    <i class="fa fa-book text-[10px]"></i>
                                    <span>Ledger</span>
                                </a>

                                <!-- 4. Update (Warning/Amber Button) -->
                                <a href="{{ route('admin.delivery_boys.edit', $rider->id) }}"
                                   class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-500 text-amber-800 hover:text-white border border-amber-200 hover:border-amber-500 rounded-lg text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
                                   title="Update Account & Areas">
                                    <i class="fa fa-pen-to-square text-[10px]"></i>
                                    <span>Update</span>
                                </a>

                                <!-- 5. Delete (Danger/Rose Button) -->
                                <form action="{{ route('admin.delivery_boys.destroy', $rider->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete delivery boy {{ addslashes($rider->name) }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-2 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 hover:border-rose-600 rounded-lg text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
                                            title="Delete Delivery Boy">
                                        <i class="fa fa-trash-can text-[10px]"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-lg">
                                    <i class="fa fa-person-biking"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-700">No delivery boys found</p>
                                <p class="text-xs text-gray-400">Try adjusting your filter criteria or register a new delivery boy.</p>
                                <a href="{{ route('admin.delivery_boys.create') }}" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold">
                                    <i class="fa fa-plus mr-1"></i> Add Delivery Boy
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riders->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Showing page {{ $riders->currentPage() }} of {{ $riders->lastPage() }}</span>
            {{ $riders->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Searchable Combobox Cascading JavaScript Engine -->
<script>
window.GLOBAL_ADDRESS_HIERARCHY = @json($hierarchy);

class SearchableCombobox {
    constructor(config) {
        this.container = document.getElementById(config.id);
        if (!this.container) return;

        this.input = this.container.querySelector('.combobox-input');
        this.toggleBtn = this.container.querySelector('.combobox-toggle');
        this.clearBtn = this.container.querySelector('.combobox-clear');
        this.dropdown = this.container.querySelector('.combobox-dropdown');
        this.list = this.container.querySelector('.combobox-list');
        this.emptyMsg = this.container.querySelector('.combobox-empty');
        this.countEl = this.container.querySelector('.combobox-count');
        this.chevron = this.toggleBtn ? this.toggleBtn.querySelector('i') : null;

        this.options = Array.isArray(config.options) ? [...config.options] : [];
        this.badgeLabel = config.badgeLabel || 'Options';
        this.onSelect = config.onSelect || null;
        this.onChange = config.onChange || null;

        this.highlightIndex = -1;
        this.isOpen = false;
        this.filteredOptions = [];

        this.bindEvents();
    }

    bindEvents() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }
            });
        }

        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.setValue('', true);
                this.input.focus();
                this.open();
            });
        }

        this.input.addEventListener('focus', () => {
            this.open();
        });

        this.input.addEventListener('input', () => {
            this.filter(this.input.value);
            if (this.clearBtn) {
                if (this.input.value.trim().length > 0) {
                    this.clearBtn.classList.remove('hidden');
                } else {
                    this.clearBtn.classList.add('hidden');
                }
            }
            if (this.onChange) this.onChange(this.input.value.trim());
        });

        this.input.addEventListener('keydown', (e) => {
            if (!this.isOpen && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                this.open();
                return;
            }

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.highlightNext();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.highlightPrev();
            } else if (e.key === 'Enter') {
                if (this.isOpen && this.highlightIndex >= 0 && this.highlightIndex < this.filteredOptions.length) {
                    e.preventDefault();
                    this.selectOption(this.filteredOptions[this.highlightIndex]);
                }
            } else if (e.key === 'Escape') {
                this.close();
            }
        });

        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.close();
            }
        });
    }

    setOptions(newOptions, resetValueIfMissing = false) {
        this.options = Array.isArray(newOptions) ? [...newOptions] : [];
        if (resetValueIfMissing && this.input.value) {
            const exists = this.options.some(opt => opt.toLowerCase() === this.input.value.trim().toLowerCase());
            if (!exists) {
                this.setValue('', true);
            }
        }
        if (this.isOpen) {
            this.filter(this.input.value);
        }
    }

    setValue(val, triggerCallback = false) {
        this.input.value = val;
        if (this.clearBtn) {
            if (val) {
                this.clearBtn.classList.remove('hidden');
            } else {
                this.clearBtn.classList.add('hidden');
            }
        }
        if (triggerCallback) {
            if (this.onSelect) this.onSelect(val);
            if (this.onChange) this.onChange(val);
        }
    }

    open() {
        // Close other dropdowns
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
        onSelect: (selectedUpz) => {
            if (selectedUpz && HIERARCHY.upazilaToDistrict && HIERARCHY.upazilaToDistrict[selectedUpz]) {
                const autoDist = HIERARCHY.upazilaToDistrict[selectedUpz];
                if (!filterDistrictCombo.input.value) {
                    filterDistrictCombo.setValue(autoDist, false);
                    if (HIERARCHY.districtToDivision && HIERARCHY.districtToDivision[autoDist] && !filterDivisionCombo.input.value) {
                        filterDivisionCombo.setValue(HIERARCHY.districtToDivision[autoDist], false);
                    }
                }
            }
        }
    });

    const filterPourashavaCombo = new SearchableCombobox({
        id: 'filter_pourashava_box',
        options: HIERARCHY.pourashavas || [],
        badgeLabel: 'Pourashavas/Unions'
    });

    const filterWardCombo = new SearchableCombobox({
        id: 'filter_ward_box',
        options: HIERARCHY.wards || [],
        badgeLabel: 'Wards'
    });

    function updateFilterDistricts(division) {
        if (!division) {
            filterDistrictCombo.setOptions(HIERARCHY.allDistricts || []);
            filterUpazilaCombo.setOptions(HIERARCHY.allUpazilas || []);
            return;
        }
        if (HIERARCHY.districtsByDivision && HIERARCHY.districtsByDivision[division]) {
            const districts = HIERARCHY.districtsByDivision[division];
            filterDistrictCombo.setOptions(districts);
            const currentDist = filterDistrictCombo.input.value;
            if (currentDist && !districts.includes(currentDist)) {
                filterDistrictCombo.setValue('', false);
                filterUpazilaCombo.setOptions([]);
            } else if (currentDist) {
                updateFilterUpazilas(currentDist);
            }
        }
    }

    function updateFilterUpazilas(district) {
        if (!district) {
            const currentDiv = filterDivisionCombo.input.value;
            if (currentDiv && HIERARCHY.districtsByDivision && HIERARCHY.districtsByDivision[currentDiv]) {
                let upzs = [];
                HIERARCHY.districtsByDivision[currentDiv].forEach(d => {
                    if (HIERARCHY.upazilasByDistrict && HIERARCHY.upazilasByDistrict[d]) {
                        upzs = upzs.concat(HIERARCHY.upazilasByDistrict[d]);
                    }
                });
                filterUpazilaCombo.setOptions(upzs);
            } else {
                filterUpazilaCombo.setOptions(HIERARCHY.allUpazilas || []);
            }
            return;
        }

        if (HIERARCHY.upazilasByDistrict && HIERARCHY.upazilasByDistrict[district]) {
            filterUpazilaCombo.setOptions(HIERARCHY.upazilasByDistrict[district]);
        }

        if (HIERARCHY.districtToDivision && HIERARCHY.districtToDivision[district]) {
            const autoDiv = HIERARCHY.districtToDivision[district];
            if (!filterDivisionCombo.input.value) {
                filterDivisionCombo.setValue(autoDiv, false);
            }
        }
    }

    // Initialize current selections
    if (filterDivisionCombo.input.value) {
        updateFilterDistricts(filterDivisionCombo.input.value);
    }
    if (filterDistrictCombo.input.value) {
        updateFilterUpazilas(filterDistrictCombo.input.value);
    }
});

function clearField(fieldName) {
    const input = document.querySelector(`[name="${fieldName}"]`);
    if (input) {
        input.value = '';
        input.form.submit();
    }
}
</script>
@endsection
