@extends('admin.layout')

@section('title', 'Edit Global Delivery Zone #' . $globalAddress->id . ' - Amar Bangla Super Admin')
@section('page-title', 'Edit Delivery Zone (super_admin/global_address/' . $globalAddress->id . '/edit)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
            <i class="fa fa-chevron-right text-[10px] text-gray-400"></i>
            <a href="{{ route('admin.global_address.index') }}" class="hover:text-blue-600 transition">Global Addresses</a>
            <i class="fa fa-chevron-right text-[10px] text-gray-400"></i>
            <span class="text-gray-800 font-bold">Edit #{{ $globalAddress->id }}</span>
        </div>

        <a href="{{ route('admin.global_address.index') }}"
           class="px-3.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
            <i class="fa fa-arrow-left text-[11px]"></i>
            <span>Back to List</span>
        </a>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Location Hierarchy (#custom-tabs-four-home / #registertab)
         2. Delivery Charges & Settings (#custom-tabs-four-settings / #delivery) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="globalAddressTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchGlobalAddressTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="global-address-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-map-location-dot"></i>
            <span>Location Hierarchy</span>
        </a>

        <a href="#custom-tabs-four-settings" onclick="switchGlobalAddressTab('custom-tabs-four-settings'); return false;" id="btn-custom-tabs-four-settings"
           class="global-address-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-truck-fast"></i>
            <span>Delivery Charges & Settings</span>
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
        <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-map-location-dot"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-gray-800">Edit Global Delivery Zone #{{ $globalAddress->id }}</h3>
                <p class="text-xs text-gray-500">Search by typing or choose from dropdown lists. Fields dynamically cascade.</p>
            </div>
        </div>

        <form action="{{ route('admin.global_address.update', $globalAddress->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- TAB 1: Location Hierarchy (#custom-tabs-four-home) -->
            <div id="custom-tabs-four-home" class="global-address-pane space-y-5">
                <div class="border-b border-gray-100 pb-2">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Geographic Region & Division Hierarchy</h4>
                </div>

                <!-- 1. Division * (Searchable Combobox) -->
                <div class="searchable-combobox-field space-y-1.5" id="page_edit_division_box">
                <label class="block text-xs font-bold text-gray-700">
                    Division <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-blue-500 text-xs pointer-events-none">
                        <i class="fa fa-map"></i>
                    </span>
                    <input type="text" name="division" id="page_edit_division" value="{{ old('division', $globalAddress->division) }}" required autocomplete="off"
                           placeholder="Type to search or select Division..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2.5 top-2.5 flex items-center gap-1">
                        <button type="button" class="combobox-clear {{ old('division', $globalAddress->division) ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
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
            <div class="searchable-combobox-field space-y-1.5" id="page_edit_district_box">
                <label class="block text-xs font-bold text-gray-700">
                    District / Zila <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-indigo-500 text-xs pointer-events-none">
                        <i class="fa fa-city"></i>
                    </span>
                    <input type="text" name="district" id="page_edit_district" value="{{ old('district', $globalAddress->district) }}" required autocomplete="off"
                           placeholder="Type to search or select District..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2.5 top-2.5 flex items-center gap-1">
                        <button type="button" class="combobox-clear {{ old('district', $globalAddress->district) ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
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
            <div class="searchable-combobox-field space-y-1.5" id="page_edit_upazila_box">
                <label class="block text-xs font-bold text-gray-700">
                    Upazila / Thana / Area <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-emerald-500 text-xs pointer-events-none">
                        <i class="fa fa-building"></i>
                    </span>
                    <input type="text" name="upazila" id="page_edit_upazila" value="{{ old('upazila', $globalAddress->upazila) }}" required autocomplete="off"
                           placeholder="Type to search or select Upazila..."
                           class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <div class="absolute right-2.5 top-2.5 flex items-center gap-1">
                        <button type="button" class="combobox-clear {{ old('upazila', $globalAddress->upazila) ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- 4. Pourashava / Union / City Corporation (Searchable Combobox) -->
                <div class="searchable-combobox-field space-y-1.5" id="page_edit_pourashava_box">
                    <label class="block text-xs font-bold text-gray-700">Pourashava / Union / City Corporation</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-amber-500 text-xs pointer-events-none">
                            <i class="fa fa-landmark"></i>
                        </span>
                        @php
                            $curPour = old('pourashava', $globalAddress->pourashava);
                            if ($curPour === '0') $curPour = 'City Corporation';
                            elseif ($curPour === '1') $curPour = 'Pourashava';
                            elseif ($curPour === '2') $curPour = 'Union';
                        @endphp
                        <input type="text" name="pourashava" id="page_edit_pourashava" value="{{ $curPour }}" autocomplete="off"
                               placeholder="Type or select (e.g. Pourashava, Union, City Corporation)..."
                               class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2.5 top-2.5 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ $curPour ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
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
                <div class="searchable-combobox-field space-y-1.5" id="page_edit_ward_box">
                    <label class="block text-xs font-bold text-gray-700">Ward</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-purple-500 text-xs pointer-events-none">
                            <i class="fa fa-hashtag"></i>
                        </span>
                        @php
                            $rawWVal = trim((string)old('ward', $globalAddress->ward));
                            if ($rawWVal !== '') {
                                if (preg_match('/^(?:ward\s*)?(\d+.*)$/i', $rawWVal, $wm)) {
                                    $curWard = 'Ward ' . $wm[1];
                                } else {
                                    $curWard = str_starts_with(strtolower($rawWVal), 'ward') ? $rawWVal : 'Ward ' . $rawWVal;
                                }
                            } else {
                                $curWard = '';
                            }
                        @endphp
                        <input type="text" name="ward" id="page_edit_ward" value="{{ $curWard }}" autocomplete="off"
                               placeholder="Type or select (e.g. Ward 1)..."
                               class="combobox-input w-full pl-9 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <div class="absolute right-2.5 top-2.5 flex items-center gap-1">
                            <button type="button" class="combobox-clear {{ $curWard ? '' : 'hidden' }} text-gray-400 hover:text-gray-600 text-[10px] w-5 h-5 flex items-center justify-center rounded">
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

                <div class="flex items-center justify-end pt-3">
                    <button type="button" onclick="switchGlobalAddressTab('custom-tabs-four-settings')"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                        <span>Continue to Delivery Charges</span>
                        <i class="fa fa-arrow-right text-[11px]"></i>
                    </button>
                </div>
            </div>

            <!-- TAB 2: Delivery Charges & Settings (#custom-tabs-four-settings) -->
            <div id="custom-tabs-four-settings" class="global-address-pane hidden space-y-5">
                <div class="border-b border-gray-100 pb-2">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Courier Shipping Fees & Status</h4>
                </div>

                <!-- Row: Postal Code & Delivery Charge -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700">Postal Code</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-400 text-xs pointer-events-none">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="text" name="post_code" id="page_edit_post_code" value="{{ old('post_code', $globalAddress->post_code) }}" placeholder="e.g. 1205"
                                   class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700">
                            Delivery Charge (৳) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-blue-600 text-xs font-bold pointer-events-none">৳</span>
                            <input type="number" step="0.01" min="0" name="delivery_charge" id="page_edit_delivery_charge"
                                   value="{{ old('delivery_charge', $globalAddress->delivery_charge) }}" required
                                   class="w-full pl-8 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        </div>
                        <!-- Quick Presets -->
                        <div class="flex items-center gap-1.5 pt-1 text-[10px]">
                            <span class="text-gray-400">Presets:</span>
                            <button type="button" onclick="document.getElementById('page_edit_delivery_charge').value = '60.00'"
                                    class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                                ৳60 Dhaka
                            </button>
                            <button type="button" onclick="document.getElementById('page_edit_delivery_charge').value = '120.00'"
                                    class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                                ৳120 Outside
                            </button>
                            <button type="button" onclick="document.getElementById('page_edit_delivery_charge').value = '150.00'"
                                    class="px-2 py-0.5 rounded bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 font-semibold transition">
                                ৳150 Express
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Active Checkbox -->
                <div class="pt-2">
                    <label class="flex items-center gap-3 cursor-pointer bg-gray-50 p-3 rounded-xl border border-gray-200">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $globalAddress->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="text-xs font-bold text-gray-800 block">Active for Shipping Calculation</span>
                            <span class="text-[10px] text-gray-500 block">Enables checkout courier delivery to this area</span>
                        </div>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-5 border-t border-gray-100">
                    <button type="button" onclick="switchGlobalAddressTab('custom-tabs-four-home')"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                        &larr; Back to Location
                    </button>
                    <button type="submit" class="px-7 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                        <i class="fa fa-save"></i>
                        <span>Update Delivery Zone</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function switchGlobalAddressTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'location': 'custom-tabs-four-home',
        'custom-tabs-four-settings': 'custom-tabs-four-settings',
        'delivery': 'custom-tabs-four-settings',
        'settings': 'custom-tabs-four-settings'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.global-address-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.global-address-tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });

    const activeBtn = document.getElementById('btn-' + activeId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchGlobalAddressTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchGlobalAddressTab(hash);
    }
});

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
                if (this.isOpen) this.close();
                else this.open();
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

        this.input.addEventListener('focus', () => this.open());
        this.input.addEventListener('click', () => { if (!this.isOpen) this.open(); });

        this.input.addEventListener('input', () => {
            this.updateClearBtn();
            this.filter(this.input.value);
            if (!this.isOpen) this.open();
            if (this.onChange) this.onChange(this.input.value.trim());
        });

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
            } else if (e.key === 'Escape' || e.key === 'Tab') {
                this.close();
            }
        });
    }

    open() {
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
            this.filteredOptions = this.options.filter(item => String(item).toLowerCase().includes(q));
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
        if (this.countEl) this.countEl.textContent = `${this.filteredOptions.length} ${this.badgeLabel}`;

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
        if (!keepValue) this.setValue('', false);
        if (this.isOpen) this.filter(this.input.value);
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

document.addEventListener('click', (e) => {
    if (!e.target.closest('.searchable-combobox-field')) {
        document.querySelectorAll('.combobox-dropdown').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('.combobox-toggle i').forEach(icon => icon.classList.remove('rotate-180'));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const H = window.GLOBAL_ADDRESS_HIERARCHY;
    const initialDiv = document.getElementById('page_edit_division').value.trim();
    const initialDist = document.getElementById('page_edit_district').value.trim();

    const division_cb = new SearchableCombobox({
        id: 'page_edit_division_box',
        options: H.divisions,
        badgeLabel: 'Divisions',
        onSelect: (div) => {
            if (div && H.districtsByDivision[div]) {
                district_cb.setOptions(H.districtsByDivision[div]);
                if (!H.districtsByDivision[div].includes(district_cb.getValue())) {
                    district_cb.setValue('');
                    upazila_cb.setValue('');
                    upazila_cb.setOptions([]);
                }
            } else {
                district_cb.setOptions(H.allDistricts);
            }
            if (div && div.toLowerCase() === 'dhaka') {
                document.getElementById('page_edit_delivery_charge').value = '60.00';
            } else if (div) {
                document.getElementById('page_edit_delivery_charge').value = '120.00';
            }
        }
    });

    const distOptions = (initialDiv && H.districtsByDivision[initialDiv]) ? H.districtsByDivision[initialDiv] : H.allDistricts;

    const district_cb = new SearchableCombobox({
        id: 'page_edit_district_box',
        options: distOptions,
        badgeLabel: 'Districts',
        onSelect: (dist) => {
            if (dist && H.districtToDivision[dist]) {
                const parentDiv = H.districtToDivision[dist];
                if (division_cb.getValue() !== parentDiv) {
                    division_cb.setValue(parentDiv, false);
                    district_cb.setOptions(H.districtsByDivision[parentDiv] || H.allDistricts);
                }
            }
            if (dist && H.upazilasByDistrict[dist]) {
                upazila_cb.setOptions(H.upazilasByDistrict[dist]);
                if (!H.upazilasByDistrict[dist].includes(upazila_cb.getValue())) {
                    upazila_cb.setValue('');
                }
            } else {
                upazila_cb.setOptions(H.allUpazilas);
            }
        }
    });

    const upzOptions = (initialDist && H.upazilasByDistrict[initialDist]) ? H.upazilasByDistrict[initialDist] : H.allUpazilas;

    const upazila_cb = new SearchableCombobox({
        id: 'page_edit_upazila_box',
        options: upzOptions,
        badgeLabel: 'Upazilas',
        onSelect: (upz) => {
            if (upz && H.upazilaToDistrict[upz]) {
                const parentDist = H.upazilaToDistrict[upz];
                if (district_cb.getValue() !== parentDist) {
                    district_cb.setValue(parentDist, false);
                }
                const parentDiv = H.districtToDivision[parentDist];
                if (parentDiv && division_cb.getValue() !== parentDiv) {
                    division_cb.setValue(parentDiv, false);
                }
            }
        }
    });

    const pourashava_cb = new SearchableCombobox({
        id: 'page_edit_pourashava_box',
        options: H.pourashavas,
        badgeLabel: 'Types'
    });

    const ward_cb = new SearchableCombobox({
        id: 'page_edit_ward_box',
        options: H.wards,
        badgeLabel: 'Wards'
    });
});
</script>
@endsection
