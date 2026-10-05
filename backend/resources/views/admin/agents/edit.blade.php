@extends('admin.layout')

@section('title', 'Update Agent - ' . $agent->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Agent Update (super_admin/agent_update/' . $agent->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.agents.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Agent List</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Agent #{{ $agent->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-user-tie text-emerald-600"></i>
                <span>Agent Update: {{ $agent->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Manage agent registration credentials, administrative location address, and territory area</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>agent_update/{{ $agent->id }}</span>
        </span>
    </div>

    <!-- Flash Notifications -->
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

    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1 shadow-sm">
        <div class="font-bold flex items-center gap-2">
            <i class="fa fa-triangle-exclamation text-rose-600"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Agent Hero Summary Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-700 text-white font-extrabold text-2xl flex items-center justify-center shadow-sm">
                {{ strtoupper(substr($agent->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-base font-black text-gray-900">{{ $agent->name }}</h3>
                <p class="text-xs text-gray-500 font-mono">{{ $agent->phone }} &bull; {{ $agent->email ?: 'No email' }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ ($agent->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $agent->status ?? 'active' }}
                    </span>
                    <span class="text-[10px] font-mono font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded">
                        Code: {{ $agent->agent_code }}
                    </span>
                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                        {{ $agent->commission_rate }}% Commission
                    </span>
                </div>
            </div>
        </div>

        <div class="text-right">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Wallet Balance</span>
            <span class="text-base font-extrabold font-mono text-emerald-700">
                ৳{{ number_format($agent->balance ?? 0, 2) }}
            </span>
        </div>
    </div>

    <!-- Navigation Tabs:
         1. Registraion Info (#custom-tabs-four-home)
         2. Address (#custom-tabs-four-settings)
         3. Area (#custom-tabs-four-area) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="agentTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchAgentTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="agent-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-emerald-600 text-white shadow-sm">
            <i class="fa fa-id-badge"></i>
            <span>Registraion Info</span>
        </a>

        <a href="#custom-tabs-four-settings" onclick="switchAgentTab('custom-tabs-four-settings'); return false;" id="btn-custom-tabs-four-settings"
           class="agent-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-location-dot"></i>
            <span>Address</span>
        </a>

        <a href="#custom-tabs-four-area" onclick="switchAgentTab('custom-tabs-four-area'); return false;" id="btn-custom-tabs-four-area"
           class="agent-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-map-location-dot"></i>
            <span>Area</span>
        </a>
    </div>

    <!-- Unified Form with Tab Panes -->
    <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: Registraion Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="agent-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-id-badge text-emerald-600"></i>
                        <span>Registraion Info</span>
                    </h3>
                    <p class="text-xs text-gray-400">Basic identification, phone, commission percentage, and account status</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/agent_update/{{ $agent->id }}#custom-tabs-four-home</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Agent Code</label>
                    <input type="text" value="{{ $agent->agent_code }}" disabled
                           class="w-full py-2.5 px-3.5 bg-gray-100 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $agent->name) }}" required
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" required
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $agent->email) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">NID Card Number</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number', $agent->nid_number) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Commission Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $agent->commission_rate) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Status</label>
                    <select name="status" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                        <option value="active" {{ $agent->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $agent->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ $agent->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Wallet Balance (৳)</label>
                    <input type="text" value="৳{{ number_format($agent->balance, 2) }}" disabled
                           class="w-full py-2.5 px-3.5 bg-gray-100 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-600 cursor-not-allowed">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.agents.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Address (#custom-tabs-four-settings) -->
        <div id="custom-tabs-four-settings" class="agent-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-location-dot text-emerald-600"></i>
                        <span>Address</span>
                    </h3>
                    <p class="text-xs text-gray-400">Agent home office location and administrative division/district</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/agent_update/{{ $agent->id }}#custom-tabs-four-settings</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Division</label>
                    <select name="division" id="agent_division" onchange="viewAgentDistrict(this.value)"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                        <option value="">Please Select</option>
                        {!! divisionView($agent->division) !!}
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">District</label>
                    <select name="district" id="agent_district" onchange="viewAgentUpazila(this.value)"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Upazila / Area</label>
                    <select name="upazila" id="agent_upazila"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Union / Ward</label>
                    <input type="text" name="union_ward" value="{{ old('union_ward', $agent->union_ward) }}"
                           placeholder="Union or Ward Number"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.agents.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </div>

        <!-- TAB 3: Area (#custom-tabs-four-area) -->
        <div id="custom-tabs-four-area" class="agent-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-map-location-dot text-emerald-600"></i>
                        <span>Area Coverage & Permitted Operations</span>
                    </h3>
                    <p class="text-xs text-gray-400">Territory operational scope for merchant onboardings and rider dispatch</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/agent_update/{{ $agent->id }}#custom-tabs-four-area</span>
            </div>

            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white text-emerald-600 flex items-center justify-center font-bold text-base shadow-2xs">
                    <i class="fa fa-globe"></i>
                </div>
                <div>
                    <h4 class="text-xs font-extrabold text-emerald-900">Operates Across: {{ $agent->upazila ?: ($agent->district ?: 'Assigned Regional Zone') }}</h4>
                    <p class="text-[11px] text-emerald-700">All registered vendors and riders under this territory will route commission and billing to this agent.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <span class="text-xs text-gray-500 font-semibold block">Managed Division</span>
                    <strong class="text-sm text-gray-800">{{ $agent->division ?: 'National' }}</strong>
                </div>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <span class="text-xs text-gray-500 font-semibold block">District Zone</span>
                    <strong class="text-sm text-gray-800">{{ $agent->district ?: 'All' }}</strong>
                </div>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <span class="text-xs text-gray-500 font-semibold block">Sub-district / Thana</span>
                    <strong class="text-sm text-gray-800">{{ $agent->upazila ?: 'All' }}</strong>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.agents.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    const allDistricts = @json(districtView());
    const allUpazilas = @json(upazilasView());

    const initialDivision = @json((string)($agent->division ?? ''));
    const initialDistrict = @json((string)($agent->district ?? ''));
    const initialUpazila = @json((string)($agent->upazila ?? ''));

    function viewAgentDistrict(divisionVal) {
        const districtSelect = document.getElementById('agent_district');
        if (!districtSelect) return;
        districtSelect.innerHTML = '<option value="">Please Select</option>';

        if (!divisionVal) return;

        allDistricts.forEach(d => {
            if (d.division_id == divisionVal || String(d.division_id) === String(divisionVal)) {
                const opt = document.createElement('option');
                opt.value = d.name;
                opt.textContent = d.name + (d.bn_name ? ' (' + d.bn_name + ')' : '');
                if (String(initialDistrict) === String(d.id) || initialDistrict.toLowerCase() === d.name.toLowerCase()) {
                    opt.selected = true;
                }
                districtSelect.appendChild(opt);
            }
        });

        viewAgentUpazila(districtSelect.value);
    }

    function viewAgentUpazila(districtVal) {
        const upazilaSelect = document.getElementById('agent_upazila');
        if (!upazilaSelect) return;
        upazilaSelect.innerHTML = '<option value="">Please Select</option>';

        if (!districtVal) return;

        let targetDistId = districtVal;
        const matched = allDistricts.find(d => d.name.toLowerCase() === districtVal.toLowerCase() || String(d.id) === String(districtVal));
        if (matched) targetDistId = matched.id;

        allUpazilas.forEach(u => {
            if (u.district_id == targetDistId || String(u.district_id) === String(targetDistId)) {
                const opt = document.createElement('option');
                opt.value = u.name;
                opt.textContent = u.name + (u.bn_name ? ' (' + u.bn_name + ')' : '');
                if (String(initialUpazila) === String(u.id) || initialUpazila.toLowerCase() === u.name.toLowerCase()) {
                    opt.selected = true;
                }
                upazilaSelect.appendChild(opt);
            }
        });
    }

    function switchAgentTab(targetTabId) {
        const tabMap = {
            'custom-tabs-four-home': 'custom-tabs-four-home',
            'registertab': 'custom-tabs-four-home',
            'tab-registration': 'custom-tabs-four-home',
            'custom-tabs-four-settings': 'custom-tabs-four-settings',
            'Address': 'custom-tabs-four-settings',
            'tab-address': 'custom-tabs-four-settings',
            'custom-tabs-four-area': 'custom-tabs-four-area',
            'area': 'custom-tabs-four-area',
            'tab-area': 'custom-tabs-four-area'
        };

        const activeId = tabMap[targetTabId] || 'custom-tabs-four-home';

        const panes = document.querySelectorAll('.agent-tab-pane');
        panes.forEach(pane => {
            if (pane.id === activeId) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        });

        const tabButtons = [
            { id: 'btn-custom-tabs-four-home', tab: 'custom-tabs-four-home' },
            { id: 'btn-custom-tabs-four-settings', tab: 'custom-tabs-four-settings' },
            { id: 'btn-custom-tabs-four-area', tab: 'custom-tabs-four-area' }
        ];

        tabButtons.forEach(btnInfo => {
            const btn = document.getElementById(btnInfo.id);
            if (!btn) return;
            if (btnInfo.tab === activeId) {
                btn.className = 'agent-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-emerald-600 text-white shadow-sm';
            } else {
                btn.className = 'agent-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200';
            }
        });

        if (history.replaceState) {
            history.replaceState(null, null, '#' + activeId);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const divEl = document.getElementById('agent_division');
        if (divEl && divEl.value) {
            viewAgentDistrict(divEl.value);
        }

        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';
        if (hash) {
            switchAgentTab(hash);
        } else {
            switchAgentTab('custom-tabs-four-home');
        }
    });
</script>
@endsection
