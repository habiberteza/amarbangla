@extends('admin.layout')

@section('title', 'Global Address & Delivery Zones - Amar Bangla Super Admin')
@section('page-title', 'Global Address & Delivery Zones (super_admin/global_address)')

@section('content')
<div class="space-y-6">

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Zones</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ number_format(\App\Models\GlobalAddress::count()) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">All Bangladesh Areas</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-map-location-dot"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Active Coverage</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ number_format(\App\Models\GlobalAddress::where('is_active', true)->count()) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Ready for Shipping</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-check-circle"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Divisions</p>
                <h3 class="text-xl font-extrabold text-indigo-600 mt-0.5">{{ \App\Models\GlobalAddress::distinct('division')->count('division') }}</h3>
                <span class="text-[10px] text-indigo-600 font-medium">Across 64 Districts</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fa fa-earth-asia"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Upazilas Covered</p>
                <h3 class="text-xl font-extrabold text-amber-600 mt-0.5">{{ number_format(\App\Models\GlobalAddress::distinct('upazila')->count('upazila')) }}</h3>
                <span class="text-[10px] text-amber-600 font-medium">Thanas & Cities</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                <i class="fa fa-building"></i>
            </div>
        </div>
    </div>

    <!-- Advanced Filter & Search Box -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-sliders"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Advanced Filter & Multi-Criteria Search</h4>
                    <p class="text-[11px] text-gray-500">Filter individually by any field or combine multiple filters simultaneously (type to search & select from dropdowns)</p>
                </div>
            </div>

            <button type="button" onclick="openAddModal()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2 self-start sm:self-auto">
                <i class="fa fa-plus"></i>
                <span>Add Delivery Zone</span>
            </button>
        </div>

        <form id="filterForm" method="GET" action="{{ route('admin.global_address.index') }}" class="space-y-4">
            <!-- Row 1: Search Keyword -->
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Free keyword search (e.g. Dhaka, Dhanmondi, ward 5, 1205, Pourashava)..."
                       class="w-full pl-10 pr-24 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                <i class="fa fa-search absolute left-3.5 top-3.5 text-gray-400 text-xs"></i>
                @if(request('search'))
                    <button type="button" onclick="clearFilterField('search')" class="absolute right-14 top-2.5 text-gray-400 hover:text-rose-500 text-xs py-1 px-2">
                        <i class="fa fa-times"></i>
                    </button>
                @endif
                <button type="submit" class="absolute right-1.5 top-1.5 px-3 py-1.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-[11px] font-semibold transition">
                    Search
                </button>
            </div>

            <!-- Row 2: 5 Geographic / Hierarchy Filters (Searchable & Dropdown) -->
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

                <!-- 2. District / Zila -->
                <div class="searchable-combobox-filter relative" id="filter_district_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-city text-indigo-500 mr-1"></i> District / Zila
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

                <!-- 3. Upazila / Thana -->
                <div class="searchable-combobox-filter relative" id="filter_upazila_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-building text-emerald-500 mr-1"></i> Upazila / Thana
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

                <!-- 4. Pourashava / Union -->
                <div class="searchable-combobox-filter relative" id="filter_pourashava_box">
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">
                        <i class="fa fa-landmark text-amber-500 mr-1"></i> Pourashava / Union / City Corp
                    </label>
                    <div class="relative">
                        <input type="text" name="pourashava" id="filter_pourashava" value="{{ request('pourashava') }}"
                               autocomplete="off" placeholder="All Types (Pourashava, Union, City Corp)..."
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
                                <span class="combobox-count">Pourashava / Union / City Corp</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching type</div>
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
                               autocomplete="off" placeholder="All Wards (65)..."
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

            <!-- Row 3: Action Buttons & Active Filter Chips -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                        <i class="fa fa-filter text-[11px]"></i>
                        <span>Apply Filters</span>
                    </button>

                    @php
                        $hasFilters = request('search') || request('division') || request('district') || request('upazila') || request('pourashava') || request('ward');
                    @endphp

                    @if($hasFilters)
                        <a href="{{ route('admin.global_address.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-rose-50 text-gray-600 hover:text-rose-600 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                            <i class="fa fa-rotate-left text-[11px]"></i>
                            <span>Reset All</span>
                        </a>
                    @endif
                </div>

                <!-- Active Filter Badges -->
                @if($hasFilters)
                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                    <span class="text-gray-400 text-[11px] font-medium mr-1">Active filters:</span>

                    @if(request('division'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-[11px] font-semibold">
                            Division: <strong>{{ request('division') }}</strong>
                            <button type="button" onclick="clearFilterField('division')" class="hover:text-blue-900 ml-0.5"><i class="fa fa-times text-[10px]"></i></button>
                        </span>
                    @endif

                    @if(request('district'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200 text-[11px] font-semibold">
                            District: <strong>{{ request('district') }}</strong>
                            <button type="button" onclick="clearFilterField('district')" class="hover:text-indigo-900 ml-0.5"><i class="fa fa-times text-[10px]"></i></button>
                        </span>
                    @endif

                    @if(request('upazila'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold">
                            Upazila: <strong>{{ request('upazila') }}</strong>
                            <button type="button" onclick="clearFilterField('upazila')" class="hover:text-emerald-900 ml-0.5"><i class="fa fa-times text-[10px]"></i></button>
                        </span>
                    @endif

                    @if(request('pourashava'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-semibold">
                            Type: <strong>{{ request('pourashava') }}</strong>
                            <button type="button" onclick="clearFilterField('pourashava')" class="hover:text-amber-900 ml-0.5"><i class="fa fa-times text-[10px]"></i></button>
                        </span>
                    @endif

                    @if(request('ward'))
                        @php
                            $fWard = trim((string)request('ward'));
                            if (preg_match('/^(?:ward\s*)?(\d+.*)$/i', $fWard, $wm)) {
                                $fWardDisplay = 'Ward ' . $wm[1];
                            } else {
                                $fWardDisplay = str_starts_with(strtolower($fWard), 'ward') ? $fWard : 'Ward ' . $fWard;
                            }
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 border border-purple-200 text-[11px] font-semibold">
                            Ward: <strong>{{ $fWardDisplay }}</strong>
                            <button type="button" onclick="clearFilterField('ward')" class="hover:text-purple-900 ml-0.5"><i class="fa fa-times text-[10px]"></i></button>
                        </span>
                    @endif

                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 border border-gray-200 text-[11px] font-semibold">
                            Keyword: "<strong>{{ request('search') }}</strong>"
                            <button type="button" onclick="clearFilterField('search')" class="hover:text-gray-900 ml-0.5"><i class="fa fa-times text-[10px]"></i></button>
                        </span>
                    @endif
                </div>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between text-xs text-gray-500 font-medium">
            <div>
                Showing <strong class="text-gray-800">{{ $addresses->firstItem() ?? 0 }}</strong> to <strong class="text-gray-800">{{ $addresses->lastItem() ?? 0 }}</strong> of <strong class="text-gray-800">{{ number_format($addresses->total()) }}</strong> zones
                @if($hasFilters)
                    <span class="ml-1 text-blue-600 font-semibold">(Filtered from {{ number_format(\App\Models\GlobalAddress::count()) }} total)</span>
                @endif
            </div>
            <div>
                Page {{ $addresses->currentPage() }} of {{ $addresses->lastPage() }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4"># ID</th>
                        <th class="py-3 px-4">Division</th>
                        <th class="py-3 px-4">District / Zila</th>
                        <th class="py-3 px-4">Upazila / Thana</th>
                        <th class="py-3 px-4">Pourashava / Union / City Corp</th>
                        <th class="py-3 px-4">Ward</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($addresses as $addr)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4 font-mono text-[11px] text-gray-400">
                            #{{ $addr->id }}
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-800">
                            {{ $addr->division }}
                        </td>
                        <td class="py-3 px-4 text-gray-700">
                            {{ $addr->district }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-gray-900">
                            {{ $addr->upazila }}
                        </td>
                        <td class="py-3 px-4 text-gray-600">
                            @php
                                $pText = match((string)$addr->pourashava) {
                                    '0' => 'City Corporation',
                                    '1' => 'Pourashava',
                                    '2' => 'Union',
                                    default => $addr->pourashava,
                                };
                            @endphp
                            @if($pText === 'City Corporation')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm">
                                    <i class="fa fa-city mr-1 text-[10px] text-indigo-500"></i> City Corporation
                                </span>
                            @elseif($pText === 'Pourashava')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                    <i class="fa fa-landmark mr-1 text-[10px] text-amber-500"></i> Pourashava
                                </span>
                            @elseif($pText === 'Union')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                    <i class="fa fa-building mr-1 text-[10px] text-emerald-500"></i> Union
                                </span>
                            @elseif($pText)
                                <span class="text-gray-700 font-medium">{{ $pText }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-700 font-medium">
                            @php
                                $rawW = trim((string)($addr->ward ?? ''));
                                if ($rawW === '' || $rawW === '—') {
                                    $wText = '—';
                                } elseif (preg_match('/^(?:ward\s*)?(\d+.*)$/i', $rawW, $wm)) {
                                    $wText = 'Ward ' . $wm[1];
                                } else {
                                    $wText = str_starts_with(strtolower($rawW), 'ward') ? $rawW : 'Ward ' . $rawW;
                                }
                            @endphp
                            @if($wText !== '—')
                                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-purple-50 text-purple-700 border border-purple-100 font-bold text-[11px]">
                                    {{ $wText }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <button type="button"
                                    onclick="openEditModal({{ json_encode($addr) }})"
                                    class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition"
                                    title="Quick Edit (Modal)">
                                <i class="fa fa-pen text-[11px]"></i>
                            </button>
                            <a href="{{ route('admin.global_address.edit', $addr->id) }}"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-indigo-50 text-gray-600 hover:text-indigo-600 transition"
                               title="Full Edit Page">
                                <i class="fa fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                            <form action="{{ route('admin.global_address.destroy', $addr->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this delivery zone?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition" title="Delete Zone">
                                    <i class="fa fa-trash-alt text-[11px]"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <div class="max-w-xs mx-auto space-y-2">
                                <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-lg">
                                    <i class="fa fa-filter-circle-xmark"></i>
                                </div>
                                <p class="text-sm font-semibold text-gray-600">No delivery zones found</p>
                                <p class="text-xs text-gray-400">No global addresses match the selected filter criteria. Try clearing some filters.</p>
                                <a href="{{ route('admin.global_address.index') }}" class="inline-block mt-2 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold">
                                    Reset Filters
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($addresses->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $addresses->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Add Modal: Add New Global Delivery Zone -->
<div id="addAddressModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Add New Global Delivery Zone</h3>
                    <p class="text-[11px] text-gray-500">Search by typing or choose from dropdown lists</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.global_address.store') }}" method="POST" id="addAddressForm" class="space-y-4 mt-4">
            @csrf

            <!-- 1. Division * (Searchable Combobox) -->
            <div class="searchable-combobox-field space-y-1" id="add_division_box">
                <label class="block text-xs font-bold text-gray-700">
                    Division <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-blue-500 text-xs pointer-events-none">
                        <i class="fa fa-map"></i>
                    </span>
                    <input type="text" name="division" id="add_division" required autocomplete="off"
                           placeholder="Type to search or select Division..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2 top-2 flex items-center gap-1">
                        <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <!-- Dropdown List -->
                    <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                        <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                            <span class="combobox-count">Divisions</span>
                            <span class="text-[9px] text-gray-400">Click or type</span>
                        </div>
                        <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                        <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching division. Press Enter to use custom text.</div>
                    </div>
                </div>
            </div>

            <!-- 2. District / Zila * (Searchable Combobox) -->
            <div class="searchable-combobox-field space-y-1" id="add_district_box">
                <label class="block text-xs font-bold text-gray-700">
                    District / Zila <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-indigo-500 text-xs pointer-events-none">
                        <i class="fa fa-city"></i>
                    </span>
                    <input type="text" name="district" id="add_district" required autocomplete="off"
                           placeholder="Type to search or select District..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2 top-2 flex items-center gap-1">
                        <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <!-- Dropdown List -->
                    <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                        <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                            <span class="combobox-count">Districts</span>
                            <span class="text-[9px] text-gray-400">Click or type</span>
                        </div>
                        <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                        <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching district. Press Enter to use custom text.</div>
                    </div>
                </div>
            </div>

            <!-- 3. Upazila / Thana / Area * (Searchable Combobox) -->
            <div class="searchable-combobox-field space-y-1" id="add_upazila_box">
                <label class="block text-xs font-bold text-gray-700">
                    Upazila / Thana / Area <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-emerald-500 text-xs pointer-events-none">
                        <i class="fa fa-building"></i>
                    </span>
                    <input type="text" name="upazila" id="add_upazila" required autocomplete="off"
                           placeholder="Type to search or select Upazila..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2 top-2 flex items-center gap-1">
                        <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <!-- Dropdown List -->
                    <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                        <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                            <span class="combobox-count">Upazilas</span>
                            <span class="text-[9px] text-gray-400">Click or type</span>
                        </div>
                        <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                        <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching upazila. Press Enter to use custom text.</div>
                    </div>
                </div>
            </div>

            <!-- Row: Pourashava / Union & Ward -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- 4. Pourashava / Union (Searchable Combobox) -->
                <div class="searchable-combobox-field space-y-1" id="add_pourashava_box">
                    <label class="block text-xs font-bold text-gray-700">
                        Pourashava / Union / City Corporation
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-amber-500 text-xs pointer-events-none">
                            <i class="fa fa-landmark"></i>
                        </span>
                        <input type="text" name="pourashava" id="add_pourashava" autocomplete="off"
                               placeholder="Type or select (e.g. Pourashava, Union, City Corporation)..."
                               class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>

                        <!-- Dropdown List -->
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                <span class="combobox-count">Types</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">Press Enter to use custom type.</div>
                        </div>
                    </div>
                </div>

                <!-- 5. Ward (Searchable Combobox) -->
                <div class="searchable-combobox-field space-y-1" id="add_ward_box">
                    <label class="block text-xs font-bold text-gray-700">
                        Ward
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-purple-500 text-xs pointer-events-none">
                            <i class="fa fa-hashtag"></i>
                        </span>
                        <input type="text" name="ward" id="add_ward" autocomplete="off"
                               placeholder="Type or select (e.g. ward 1)..."
                               class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>

                        <!-- Dropdown List -->
                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                <span class="combobox-count">Wards</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">Press Enter to use custom ward.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row: Postal Code & Delivery Charge -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">Postal Code</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-xs pointer-events-none">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="text" name="post_code" id="add_post_code" placeholder="e.g. 1205"
                               class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">
                        Delivery Charge (৳) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-blue-600 text-xs font-bold pointer-events-none">
                            ৳
                        </span>
                        <input type="number" step="0.01" min="0" name="delivery_charge" id="add_delivery_charge" value="60.00" required
                               class="w-full pl-8 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                    <!-- Quick Presets -->
                    <div class="flex items-center gap-1.5 pt-1 text-[10px]">
                        <span class="text-gray-400">Presets:</span>
                        <button type="button" onclick="document.getElementById('add_delivery_charge').value = '60.00'"
                                class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                            ৳60 Dhaka
                        </button>
                        <button type="button" onclick="document.getElementById('add_delivery_charge').value = '120.00'"
                                class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                            ৳120 Outside
                        </button>
                        <button type="button" onclick="document.getElementById('add_delivery_charge').value = '150.00'"
                                class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                            ৳150 Express
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-2.5 rounded-xl border border-gray-200">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="text-xs font-bold text-gray-800 block">Active for Shipping Calculation</span>
                        <span class="text-[10px] text-gray-500 block">Enables checkout courier delivery to this area</span>
                    </div>
                </label>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Delivery Zone</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal: Edit Global Delivery Zone -->
<div id="editAddressModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-200 relative my-8">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa fa-pen"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800" id="editModalTitle">Edit Global Delivery Zone</h3>
                    <p class="text-[11px] text-gray-500">Modify location hierarchy or delivery fees</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-7 h-7 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form id="editAddressForm" method="POST" class="space-y-4 mt-4">
            @csrf
            @method('PUT')

            <!-- 1. Division * (Searchable Combobox) -->
            <div class="searchable-combobox-field space-y-1" id="edit_division_box">
                <label class="block text-xs font-bold text-gray-700">
                    Division <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-blue-500 text-xs pointer-events-none">
                        <i class="fa fa-map"></i>
                    </span>
                    <input type="text" name="division" id="edit_division" required autocomplete="off"
                           placeholder="Type to search or select Division..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2 top-2 flex items-center gap-1">
                        <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                        <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                            <span class="combobox-count">Divisions</span>
                        </div>
                        <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                        <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching division. Press Enter to use custom text.</div>
                    </div>
                </div>
            </div>

            <!-- 2. District / Zila * (Searchable Combobox) -->
            <div class="searchable-combobox-field space-y-1" id="edit_district_box">
                <label class="block text-xs font-bold text-gray-700">
                    District / Zila <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-indigo-500 text-xs pointer-events-none">
                        <i class="fa fa-city"></i>
                    </span>
                    <input type="text" name="district" id="edit_district" required autocomplete="off"
                           placeholder="Type to search or select District..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2 top-2 flex items-center gap-1">
                        <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                        <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                            <span class="combobox-count">Districts</span>
                        </div>
                        <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                        <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching district. Press Enter to use custom text.</div>
                    </div>
                </div>
            </div>

            <!-- 3. Upazila / Thana / Area * (Searchable Combobox) -->
            <div class="searchable-combobox-field space-y-1" id="edit_upazila_box">
                <label class="block text-xs font-bold text-gray-700">
                    Upazila / Thana / Area <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-emerald-500 text-xs pointer-events-none">
                        <i class="fa fa-building"></i>
                    </span>
                    <input type="text" name="upazila" id="edit_upazila" required autocomplete="off"
                           placeholder="Type to search or select Upazila..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2 top-2 flex items-center gap-1">
                        <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-times"></i>
                        </button>
                        <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                            <i class="fa fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                        <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                            <span class="combobox-count">Upazilas</span>
                        </div>
                        <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                        <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching upazila. Press Enter to use custom text.</div>
                    </div>
                </div>
            </div>

            <!-- Row: Pourashava / Union & Ward -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- 4. Pourashava / Union (Searchable Combobox) -->
                <div class="searchable-combobox-field space-y-1" id="edit_pourashava_box">
                    <label class="block text-xs font-bold text-gray-700">Pourashava / Union / City Corporation</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-amber-500 text-xs pointer-events-none">
                            <i class="fa fa-landmark"></i>
                        </span>
                        <input type="text" name="pourashava" id="edit_pourashava" autocomplete="off"
                               placeholder="Type or select (e.g. Pourashava, Union, City Corporation)..."
                               class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>

                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                <span class="combobox-count">Types</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">Press Enter to use custom type.</div>
                        </div>
                    </div>
                </div>

                <!-- 5. Ward (Searchable Combobox) -->
                <div class="searchable-combobox-field space-y-1" id="edit_ward_box">
                    <label class="block text-xs font-bold text-gray-700">Ward</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-purple-500 text-xs pointer-events-none">
                            <i class="fa fa-hashtag"></i>
                        </span>
                        <input type="text" name="ward" id="edit_ward" autocomplete="off"
                               placeholder="Type or select (e.g. ward 1)..."
                               class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2 top-2 flex items-center gap-1">
                            <button type="button" class="combobox-clear hidden text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="combobox-toggle text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
                                <i class="fa fa-chevron-down transition-transform duration-200"></i>
                            </button>
                        </div>

                        <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                            <div class="combobox-header px-3 py-1.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                <span class="combobox-count">Wards</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">Press Enter to use custom ward.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row: Postal Code & Delivery Charge -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">Postal Code</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-xs pointer-events-none">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="text" name="post_code" id="edit_post_code"
                               class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700">
                        Delivery Charge (৳) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-blue-600 text-xs font-bold pointer-events-none">
                            ৳
                        </span>
                        <input type="number" step="0.01" min="0" name="delivery_charge" id="edit_delivery_charge" required
                               class="w-full pl-8 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                    <!-- Quick Presets -->
                    <div class="flex items-center gap-1.5 pt-1 text-[10px]">
                        <span class="text-gray-400">Presets:</span>
                        <button type="button" onclick="document.getElementById('edit_delivery_charge').value = '60.00'"
                                class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                            ৳60 Dhaka
                        </button>
                        <button type="button" onclick="document.getElementById('edit_delivery_charge').value = '120.00'"
                                class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                            ৳120 Outside
                        </button>
                        <button type="button" onclick="document.getElementById('edit_delivery_charge').value = '150.00'"
                                class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                            ৳150 Express
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer bg-gray-50 p-2.5 rounded-xl border border-gray-200">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="text-xs font-bold text-gray-800 block">Active for Shipping Calculation</span>
                        <span class="text-[10px] text-gray-500 block">Enables checkout courier delivery to this area</span>
                    </div>
                </label>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Delivery Zone</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// 1. Data Store from Backend (Complete Bangladesh Geography)
window.GLOBAL_ADDRESS_HIERARCHY = @json($hierarchy);

/**
 * Modern Searchable Combobox Component
 * Handles:
 * - Real-time typing search with highlight
 * - Scrollable dropdown list with option counts
 * - Arrow navigation & Enter key selection
 * - Custom input preservation
 * - Dynamic cascading & reactive callbacks
 */
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
        // Toggle button click
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

        // Clear button click
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.setValue('', true);
                this.input.focus();
                this.open();
            });
        }

        // Input focus & click
        this.input.addEventListener('focus', () => {
            this.open();
        });
        this.input.addEventListener('click', () => {
            if (!this.isOpen) this.open();
        });

        // Real-time typing filter
        this.input.addEventListener('input', () => {
            this.updateClearBtn();
            this.filter(this.input.value);
            if (!this.isOpen) this.open();
            if (this.onChange) this.onChange(this.input.value.trim());
        });

        // Keyboard navigation
        this.input.addEventListener('keydown', (e) => {
            if (!this.isOpen && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
                this.open();
                e.preventDefault();
                return;
            }

            if (!this.isOpen) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.moveHighlight(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.moveHighlight(-1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (this.highlightIndex >= 0 && this.highlightIndex < this.filteredOptions.length) {
                    this.selectItem(this.filteredOptions[this.highlightIndex]);
                } else {
                    this.close();
                }
            } else if (e.key === 'Escape') {
                this.close();
            } else if (e.key === 'Tab') {
                this.close();
            }
        });
    }

    open() {
        // Close all other open comboboxes
        document.querySelectorAll('.combobox-dropdown').forEach(d => {
            if (d !== this.dropdown) d.classList.add('hidden');
        });
        document.querySelectorAll('.combobox-toggle i').forEach(icon => {
            if (icon !== this.chevron) icon.classList.remove('rotate-180');
        });

        this.filter(this.input.value);
        this.dropdown.classList.remove('hidden');
        if (this.chevron) this.chevron.classList.add('rotate-180');
        this.isOpen = true;
    }

    close() {
        this.dropdown.classList.add('hidden');
        if (this.chevron) this.chevron.classList.remove('rotate-180');
        this.isOpen = false;
        this.highlightIndex = -1;
    }

    filter(query) {
        const q = (query || '').trim().toLowerCase();
        if (!q) {
            this.filteredOptions = [...this.options];
        } else {
            this.filteredOptions = this.options.filter(item => {
                return String(item).toLowerCase().includes(q);
            });
            // Prioritize items that start with query
            this.filteredOptions.sort((a, b) => {
                const aStarts = String(a).toLowerCase().startsWith(q);
                const bStarts = String(b).toLowerCase().startsWith(q);
                if (aStarts && !bStarts) return -1;
                if (!aStarts && bStarts) return 1;
                return 0;
            });
        }
        this.highlightIndex = this.filteredOptions.length > 0 ? 0 : -1;
        this.renderList(q);
    }

    renderList(query) {
        this.list.innerHTML = '';

        if (this.countEl) {
            this.countEl.textContent = `${this.filteredOptions.length} ${this.badgeLabel}`;
        }

        if (this.filteredOptions.length === 0) {
            this.list.classList.add('hidden');
            this.emptyMsg.classList.remove('hidden');
            return;
        }

        this.list.classList.remove('hidden');
        this.emptyMsg.classList.add('hidden');

        const currentVal = this.input.value.trim().toLowerCase();

        this.filteredOptions.slice(0, 100).forEach((item, index) => {
            const li = document.createElement('li');
            li.className = `px-3 py-2 cursor-pointer flex items-center justify-between text-xs transition ${
                index === this.highlightIndex ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700 hover:bg-gray-50'
            }`;

            // Highlight matched substring
            const rawText = String(item);
            let displayHtml = rawText;
            if (query) {
                const matchIndex = rawText.toLowerCase().indexOf(query.toLowerCase());
                if (matchIndex !== -1) {
                    const before = rawText.substring(0, matchIndex);
                    const matched = rawText.substring(matchIndex, matchIndex + query.length);
                    const after = rawText.substring(matchIndex + query.length);
                    displayHtml = `${before}<strong class="text-blue-600 font-extrabold bg-blue-100/60 rounded px-0.5">${matched}</strong>${after}`;
                }
            }

            const isSelected = rawText.toLowerCase() === currentVal;
            li.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full ${isSelected ? 'bg-blue-600' : 'bg-transparent'}"></span>
                    <span>${displayHtml}</span>
                </div>
                ${isSelected ? '<i class="fa fa-check text-blue-600 text-[11px]"></i>' : ''}
            `;

            li.addEventListener('click', (e) => {
                e.stopPropagation();
                this.selectItem(item);
            });

            li.addEventListener('mouseenter', () => {
                this.highlightIndex = index;
                this.updateHighlightStyle();
            });

            this.list.appendChild(li);
        });
    }

    moveHighlight(delta) {
        if (this.filteredOptions.length === 0) return;
        this.highlightIndex += delta;
        if (this.highlightIndex < 0) this.highlightIndex = this.filteredOptions.length - 1;
        if (this.highlightIndex >= this.filteredOptions.length) this.highlightIndex = 0;
        this.updateHighlightStyle();
    }

    updateHighlightStyle() {
        const items = this.list.querySelectorAll('li');
        items.forEach((item, idx) => {
            if (idx === this.highlightIndex) {
                item.classList.add('bg-blue-50', 'text-blue-700', 'font-semibold');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('bg-blue-50', 'text-blue-700', 'font-semibold');
            }
        });
    }

    selectItem(item) {
        this.input.value = item;
        this.updateClearBtn();
        this.close();
        if (this.onSelect) this.onSelect(item);
        if (this.onChange) this.onChange(item);
    }

    setOptions(newOptions, keepValue = true) {
        this.options = Array.isArray(newOptions) ? [...newOptions] : [];
        if (!keepValue) {
            this.setValue('', false);
        }
        if (this.isOpen) {
            this.filter(this.input.value);
        }
    }

    setValue(val, triggerCallback = true) {
        this.input.value = val || '';
        this.updateClearBtn();
        if (triggerCallback) {
            if (this.onSelect) this.onSelect(val);
            if (this.onChange) this.onChange(val);
        }
    }

    getValue() {
        return this.input.value.trim();
    }

    updateClearBtn() {
        if (this.clearBtn) {
            if (this.input.value.trim()) {
                this.clearBtn.classList.remove('hidden');
            } else {
                this.clearBtn.classList.add('hidden');
            }
        }
    }
}

// Global click outside to dismiss open dropdowns
document.addEventListener('click', (e) => {
    if (!e.target.closest('.searchable-combobox-field') && !e.target.closest('.searchable-combobox-filter')) {
        document.querySelectorAll('.combobox-dropdown').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('.combobox-toggle i').forEach(icon => icon.classList.remove('rotate-180'));
    }
});

// Initialize Add Modal Searchable Comboboxes with Cascading Logic
let add_division_cb, add_district_cb, add_upazila_cb, add_pourashava_cb, add_ward_cb;

function initAddComboboxes() {
    const H = window.GLOBAL_ADDRESS_HIERARCHY;

    add_division_cb = new SearchableCombobox({
        id: 'add_division_box',
        options: H.divisions,
        badgeLabel: 'Divisions',
        onSelect: (div) => {
            if (div && H.districtsByDivision[div]) {
                add_district_cb.setOptions(H.districtsByDivision[div]);
                if (!H.districtsByDivision[div].includes(add_district_cb.getValue())) {
                    add_district_cb.setValue('');
                    add_upazila_cb.setValue('');
                    add_upazila_cb.setOptions([]);
                }
            } else {
                add_district_cb.setOptions(H.allDistricts);
            }
            // Auto delivery charge
            if (div && div.toLowerCase() === 'dhaka') {
                document.getElementById('add_delivery_charge').value = '60.00';
            } else if (div) {
                document.getElementById('add_delivery_charge').value = '120.00';
            }
        }
    });

    add_district_cb = new SearchableCombobox({
        id: 'add_district_box',
        options: H.allDistricts,
        badgeLabel: 'Districts',
        onSelect: (dist) => {
            if (dist && H.districtToDivision[dist]) {
                const parentDiv = H.districtToDivision[dist];
                if (add_division_cb.getValue() !== parentDiv) {
                    add_division_cb.setValue(parentDiv, false);
                    add_district_cb.setOptions(H.districtsByDivision[parentDiv] || H.allDistricts);
                }
            }
            if (dist && H.upazilasByDistrict[dist]) {
                add_upazila_cb.setOptions(H.upazilasByDistrict[dist]);
                if (!H.upazilasByDistrict[dist].includes(add_upazila_cb.getValue())) {
                    add_upazila_cb.setValue('');
                }
            } else {
                add_upazila_cb.setOptions(H.allUpazilas);
            }
            // Auto delivery charge
            const curDiv = add_division_cb.getValue();
            if (curDiv && curDiv.toLowerCase() === 'dhaka') {
                document.getElementById('add_delivery_charge').value = '60.00';
            } else if (curDiv) {
                document.getElementById('add_delivery_charge').value = '120.00';
            }
        }
    });

    add_upazila_cb = new SearchableCombobox({
        id: 'add_upazila_box',
        options: H.allUpazilas,
        badgeLabel: 'Upazilas',
        onSelect: (upz) => {
            if (upz && H.upazilaToDistrict[upz]) {
                const parentDist = H.upazilaToDistrict[upz];
                if (add_district_cb.getValue() !== parentDist) {
                    add_district_cb.setValue(parentDist, false);
                }
                const parentDiv = H.districtToDivision[parentDist];
                if (parentDiv && add_division_cb.getValue() !== parentDiv) {
                    add_division_cb.setValue(parentDiv, false);
                }
            }
        }
    });

    add_pourashava_cb = new SearchableCombobox({
        id: 'add_pourashava_box',
        options: H.pourashavas,
        badgeLabel: 'Types'
    });

    add_ward_cb = new SearchableCombobox({
        id: 'add_ward_box',
        options: H.wards,
        badgeLabel: 'Wards'
    });
}

// Initialize Edit Modal Searchable Comboboxes
let edit_division_cb, edit_district_cb, edit_upazila_cb, edit_pourashava_cb, edit_ward_cb;

function initEditComboboxes() {
    const H = window.GLOBAL_ADDRESS_HIERARCHY;

    edit_division_cb = new SearchableCombobox({
        id: 'edit_division_box',
        options: H.divisions,
        badgeLabel: 'Divisions',
        onSelect: (div) => {
            if (div && H.districtsByDivision[div]) {
                edit_district_cb.setOptions(H.districtsByDivision[div]);
            } else {
                edit_district_cb.setOptions(H.allDistricts);
            }
        }
    });

    edit_district_cb = new SearchableCombobox({
        id: 'edit_district_box',
        options: H.allDistricts,
        badgeLabel: 'Districts',
        onSelect: (dist) => {
            if (dist && H.districtToDivision[dist]) {
                const parentDiv = H.districtToDivision[dist];
                if (edit_division_cb.getValue() !== parentDiv) {
                    edit_division_cb.setValue(parentDiv, false);
                }
            }
            if (dist && H.upazilasByDistrict[dist]) {
                edit_upazila_cb.setOptions(H.upazilasByDistrict[dist]);
            }
        }
    });

    edit_upazila_cb = new SearchableCombobox({
        id: 'edit_upazila_box',
        options: H.allUpazilas,
        badgeLabel: 'Upazilas',
        onSelect: (upz) => {
            if (upz && H.upazilaToDistrict[upz]) {
                const parentDist = H.upazilaToDistrict[upz];
                if (edit_district_cb.getValue() !== parentDist) {
                    edit_district_cb.setValue(parentDist, false);
                }
                const parentDiv = H.districtToDivision[parentDist];
                if (parentDiv && edit_division_cb.getValue() !== parentDiv) {
                    edit_division_cb.setValue(parentDiv, false);
                }
            }
        }
    });

    edit_pourashava_cb = new SearchableCombobox({
        id: 'edit_pourashava_box',
        options: H.pourashavas,
        badgeLabel: 'Types'
    });

    edit_ward_cb = new SearchableCombobox({
        id: 'edit_ward_box',
        options: H.wards,
        badgeLabel: 'Wards'
    });
}

// Initialize Filter Bar Searchable Comboboxes
let filter_division_cb, filter_district_cb, filter_upazila_cb, filter_pourashava_cb, filter_ward_cb;

function initFilterComboboxes() {
    const H = window.GLOBAL_ADDRESS_HIERARCHY;

    filter_division_cb = new SearchableCombobox({
        id: 'filter_division_box',
        options: H.divisions,
        badgeLabel: 'Divisions',
        onSelect: (div) => {
            if (div && H.districtsByDivision[div]) {
                filter_district_cb.setOptions(H.districtsByDivision[div]);
            } else {
                filter_district_cb.setOptions(H.allDistricts);
            }
        }
    });

    const initialDiv = filter_division_cb.getValue();
    const initialDistOptions = initialDiv && H.districtsByDivision[initialDiv] ? H.districtsByDivision[initialDiv] : H.allDistricts;

    filter_district_cb = new SearchableCombobox({
        id: 'filter_district_box',
        options: initialDistOptions,
        badgeLabel: 'Districts',
        onSelect: (dist) => {
            if (dist && H.districtToDivision[dist]) {
                const parentDiv = H.districtToDivision[dist];
                if (!filter_division_cb.getValue()) {
                    filter_division_cb.setValue(parentDiv, false);
                }
            }
            if (dist && H.upazilasByDistrict[dist]) {
                filter_upazila_cb.setOptions(H.upazilasByDistrict[dist]);
            } else {
                filter_upazila_cb.setOptions(H.allUpazilas);
            }
        }
    });

    const initialDist = filter_district_cb.getValue();
    const initialUpzOptions = initialDist && H.upazilasByDistrict[initialDist] ? H.upazilasByDistrict[initialDist] : H.allUpazilas;

    filter_upazila_cb = new SearchableCombobox({
        id: 'filter_upazila_box',
        options: initialUpzOptions,
        badgeLabel: 'Upazilas'
    });

    filter_pourashava_cb = new SearchableCombobox({
        id: 'filter_pourashava_box',
        options: H.pourashavas,
        badgeLabel: 'Types'
    });

    filter_ward_cb = new SearchableCombobox({
        id: 'filter_ward_box',
        options: H.wards,
        badgeLabel: 'Wards'
    });
}

// Modal open/close helpers
function openAddModal() {
    const H = window.GLOBAL_ADDRESS_HIERARCHY;
    add_division_cb.setValue('');
    add_district_cb.setOptions(H.allDistricts);
    add_district_cb.setValue('');
    add_upazila_cb.setOptions(H.allUpazilas);
    add_upazila_cb.setValue('');
    add_pourashava_cb.setValue('');
    add_ward_cb.setValue('');
    document.getElementById('add_post_code').value = '';
    document.getElementById('add_delivery_charge').value = '60.00';

    document.getElementById('addAddressModal').classList.remove('hidden');
    setTimeout(() => add_division_cb.input.focus(), 100);
}

function closeAddModal() {
    document.getElementById('addAddressModal').classList.add('hidden');
}

function openEditModal(addr) {
    const H = window.GLOBAL_ADDRESS_HIERARCHY;
    document.getElementById('editModalTitle').textContent = `Edit Global Delivery Zone (#${addr.id})`;
    document.getElementById('editAddressForm').action = "{{ route('admin.global_address.index') }}/" + addr.id;

    edit_division_cb.setValue(addr.division || '', false);

    const distOptions = (addr.division && H.districtsByDivision[addr.division]) ? H.districtsByDivision[addr.division] : H.allDistricts;
    edit_district_cb.setOptions(distOptions);
    edit_district_cb.setValue(addr.district || '', false);

    const upzOptions = (addr.district && H.upazilasByDistrict[addr.district]) ? H.upazilasByDistrict[addr.district] : H.allUpazilas;
    edit_upazila_cb.setOptions(upzOptions);
    edit_upazila_cb.setValue(addr.upazila || '', false);

    let pVal = addr.pourashava || '';
    if (pVal === '0') pVal = 'City Corporation';
    else if (pVal === '1') pVal = 'Pourashava';
    else if (pVal === '2') pVal = 'Union';
    edit_pourashava_cb.setValue(pVal, false);
    let wVal = (addr.ward || '').trim();
    if (wVal) {
        const wm = wVal.match(/^(?:ward\s*)?(\d+.*)$/i);
        if (wm) {
            wVal = 'Ward ' + wm[1];
        } else if (!wVal.toLowerCase().startsWith('ward')) {
            wVal = 'Ward ' + wVal;
        }
    }
    edit_ward_cb.setValue(wVal, false);
    document.getElementById('edit_post_code').value = addr.post_code || '';
    document.getElementById('edit_delivery_charge').value = addr.delivery_charge;
    document.getElementById('edit_is_active').checked = !!addr.is_active;

    document.getElementById('editAddressModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editAddressModal').classList.add('hidden');
}

function clearFilterField(fieldName) {
    const form = document.getElementById('filterForm');
    const input = form.querySelector(`[name="${fieldName}"]`);
    if (input) {
        input.value = '';
    }
    form.submit();
}

// On DOM ready, initialize all comboboxes
document.addEventListener('DOMContentLoaded', () => {
    initAddComboboxes();
    initEditComboboxes();
    initFilterComboboxes();
});
</script>
@endsection
