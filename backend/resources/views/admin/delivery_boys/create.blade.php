@extends('admin.layout')

@section('title', 'Add Delivery Boy - Amar Bangla Super Admin')
@section('page-title', 'Create Delivery Boy Account (super_admin/delivery_boy_create)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.delivery_boys.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Delivery Boy List</span>
        </a>

        <span class="text-xs text-gray-400 font-mono">super_admin/delivery_boy_create</span>
    </div>

    @if (isset($errors) && $errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
        <div class="font-bold flex items-center gap-1.5 text-rose-700">
            <i class="fa fa-circle-exclamation"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside text-rose-600 pl-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.delivery_boys.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card 1: Account Credentials (Exact match to live form) -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-id-card"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Account Credentials & Login Info</h3>
                    <p class="text-[11px] text-gray-500">Name, mobile phone number, and account access password</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5" for="name">
                        Name <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">
                            <i class="fa fa-user"></i>
                        </span>
                        <input type="text" class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="name" id="name" placeholder="Name" value="{{ old('name') }}" required />
                    </div>
                    @error('name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5" for="mobile">
                        Mobile Number <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text" class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="mobile" id="mobile" placeholder="mobile (e.g. 01711223344)" value="{{ old('mobile') }}" required />
                    </div>
                    @error('phone') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    @error('mobile') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5" for="password">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">
                            <i class="fa fa-lock"></i>
                        </span>
                        <input type="password" class="w-full pl-9 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="password" id="password" placeholder="Password " required />
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5" for="con_password">
                        Confirm Password <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-gray-400 text-xs">
                            <i class="fa fa-shield-halved"></i>
                        </span>
                        <input type="password" class="w-full pl-9 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="con_password" id="con_password" placeholder="Password " required />
                        <button type="button" onclick="togglePasswordVisibility('con_password')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    @error('con_password') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Card 2: Assigned Delivery Zone (Cascading Searchable Comboboxes) -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Assigned Delivery Area / Coverage Zone</h3>
                    <p class="text-[11px] text-gray-500">Select or type Division, District, Upazila, Pourashava/Union, Ward</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- 1. Division -->
                <div class="searchable-combobox-field space-y-1" id="add_division_box">
                    <label class="block text-xs font-bold text-gray-700">Division</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-blue-500 text-xs pointer-events-none">
                            <i class="fa fa-map"></i>
                        </span>
                        <input type="text" name="division" id="add_division" autocomplete="off"
                               value="{{ old('division') }}" placeholder="Type or select Division..."
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
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching division. Press Enter to use custom.</div>
                        </div>
                    </div>
                </div>

                <!-- 2. District -->
                <div class="searchable-combobox-field space-y-1" id="add_district_box">
                    <label class="block text-xs font-bold text-gray-700">District / Zila</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-indigo-500 text-xs pointer-events-none">
                            <i class="fa fa-city"></i>
                        </span>
                        <input type="text" name="district" id="add_district" autocomplete="off"
                               value="{{ old('district') }}" placeholder="Type or select District..."
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
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching district. Press Enter to use custom.</div>
                        </div>
                    </div>
                </div>

                <!-- 3. Upazila -->
                <div class="searchable-combobox-field space-y-1" id="add_upazila_box">
                    <label class="block text-xs font-bold text-gray-700">Upazila / Thana / Area</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-emerald-500 text-xs pointer-events-none">
                            <i class="fa fa-building"></i>
                        </span>
                        <input type="text" name="upazila" id="add_upazila" autocomplete="off"
                               value="{{ old('upazila') }}" placeholder="Type or select Upazila..."
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
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching upazila. Press Enter to use custom.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- 4. Pourashava / Union -->
                <div class="searchable-combobox-field space-y-1" id="add_pourashava_box">
                    <label class="block text-xs font-bold text-gray-700">Pourashava / Union</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-amber-500 text-xs pointer-events-none">
                            <i class="fa fa-landmark"></i>
                        </span>
                        <input type="text" name="pourashava" id="add_pourashava" autocomplete="off"
                               value="{{ old('pourashava') }}" placeholder="Type or select (e.g. Pourashava, Union)..."
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
                                <span class="combobox-count">Pourashavas</span>
                            </div>
                            <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                            <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">Press Enter to use custom type.</div>
                        </div>
                    </div>
                </div>

                <!-- 5. Ward -->
                <div class="searchable-combobox-field space-y-1" id="add_ward_box">
                    <label class="block text-xs font-bold text-gray-700">Ward</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-purple-500 text-xs pointer-events-none">
                            <i class="fa fa-hashtag"></i>
                        </span>
                        <input type="text" name="ward" id="add_ward" autocomplete="off"
                               value="{{ old('ward') }}" placeholder="Type or select (e.g. ward 1)..."
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

            <!-- Street Address -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Detailed Street Address / Hub Location</label>
                <textarea name="address" rows="2" placeholder="House/Road/Sector, Landmark details..."
                          class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">{{ old('address') }}</textarea>
            </div>
        </div>

        <!-- Card 3: Personal & Operational Information -->
        <div class="bg-white p-6 sm:p-7 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa fa-user-gear"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800">Personal Details & Operation Status</h3>
                    <p class="text-[11px] text-gray-500">Parental info, assigned agent, vehicle, status & photo</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Father's Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Father's Name</label>
                    <input type="text" name="father_name" value="{{ old('father_name') }}" placeholder="Father's full name"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Mother's Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mother's Name</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name') }}" placeholder="Mother's full name"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Age (Years)</label>
                    <input type="number" min="14" max="100" name="age" value="{{ old('age') }}" placeholder="e.g. 25"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Account Status</label>
                    <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active (Enabled)</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive (Disabled)</option>
                    </select>
                </div>

                <!-- Assigned Agent -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Assigned Supervising Agent</label>
                    <select name="agent_id" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">No Agent (Direct Super Admin)</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }} ({{ $agent->phone }} - {{ $agent->upazila ?? $agent->district ?? 'Agent' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Vehicle Type</label>
                    <select name="vehicle_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="motorcycle" {{ old('vehicle_type') === 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        <option value="bicycle" {{ old('vehicle_type') === 'bicycle' ? 'selected' : '' }}>Bicycle</option>
                        <option value="van" {{ old('vehicle_type') === 'van' ? 'selected' : '' }}>Van / Pickup</option>
                        <option value="walking" {{ old('vehicle_type') === 'walking' ? 'selected' : '' }}>Walking / Foot</option>
                    </select>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email Address (Optional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="rider@amarbangla.com.bd"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- NID Number -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">National ID (NID)</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number') }}" placeholder="e.g. 1995267182901"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Profile Picture (pic / image) -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Profile Photo (pic)</label>
                    <input type="file" name="pic" accept="image/*"
                           class="w-full py-1.5 px-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                </div>
            </div>
        </div>

        <!-- Submit & Cancel Buttons -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.delivery_boys.index') }}"
               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                Cancel
            </a>

            <button type="submit"
                    class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                <i class="fa fa-save"></i>
                <span>Create Delivery Boy Account</span>
            </button>
        </div>
    </form>

</div>

<script>
window.GLOBAL_ADDRESS_HIERARCHY = @json($hierarchy);

function togglePasswordVisibility(fieldId) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    input.type = input.type === 'password' ? 'text' : 'password';
}

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

    setOptions(newOptions) {
        this.options = Array.isArray(newOptions) ? [...newOptions] : [];
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
        document.querySelectorAll('.combobox-dropdown').forEach(d => {
            if (d !== this.dropdown) d.classList.add('hidden');
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
    const H = window.GLOBAL_ADDRESS_HIERARCHY || {};

    const divisionCombo = new SearchableCombobox({
        id: 'add_division_box',
        options: H.divisions || [],
        badgeLabel: 'Divisions',
        onSelect: (div) => {
            updateDistricts(div);
        },
        onChange: (div) => {
            updateDistricts(div);
        }
    });

    const districtCombo = new SearchableCombobox({
        id: 'add_district_box',
        options: H.allDistricts || [],
        badgeLabel: 'Districts',
        onSelect: (dist) => {
            updateUpazilas(dist);
        },
        onChange: (dist) => {
            updateUpazilas(dist);
        }
    });

    const upazilaCombo = new SearchableCombobox({
        id: 'add_upazila_box',
        options: H.allUpazilas || [],
        badgeLabel: 'Upazilas',
        onSelect: (upz) => {
            if (upz && H.upazilaToDistrict && H.upazilaToDistrict[upz]) {
                const autoDist = H.upazilaToDistrict[upz];
                if (!districtCombo.input.value) {
                    districtCombo.setValue(autoDist, false);
                    if (H.districtToDivision && H.districtToDivision[autoDist] && !divisionCombo.input.value) {
                        divisionCombo.setValue(H.districtToDivision[autoDist], false);
                    }
                }
            }
        }
    });

    const pourashavaCombo = new SearchableCombobox({
        id: 'add_pourashava_box',
        options: H.pourashavas || [],
        badgeLabel: 'Types'
    });

    const wardCombo = new SearchableCombobox({
        id: 'add_ward_box',
        options: H.wards || [],
        badgeLabel: 'Wards'
    });

    function updateDistricts(div) {
        if (!div) {
            districtCombo.setOptions(H.allDistricts || []);
            upazilaCombo.setOptions(H.allUpazilas || []);
            return;
        }
        if (H.districtsByDivision && H.districtsByDivision[div]) {
            const list = H.districtsByDivision[div];
            districtCombo.setOptions(list);
            const curDist = districtCombo.input.value;
            if (curDist && !list.includes(curDist)) {
                districtCombo.setValue('', false);
                upazilaCombo.setOptions([]);
            } else if (curDist) {
                updateUpazilas(curDist);
            }
        }
    }

    function updateUpazilas(dist) {
        if (!dist) {
            const curDiv = divisionCombo.input.value;
            if (curDiv && H.districtsByDivision && H.districtsByDivision[curDiv]) {
                let upzs = [];
                H.districtsByDivision[curDiv].forEach(d => {
                    if (H.upazilasByDistrict && H.upazilasByDistrict[d]) {
                        upzs = upzs.concat(H.upazilasByDistrict[d]);
                    }
                });
                upazilaCombo.setOptions(upzs);
            } else {
                upazilaCombo.setOptions(H.allUpazilas || []);
            }
            return;
        }

        if (H.upazilasByDistrict && H.upazilasByDistrict[dist]) {
            upazilaCombo.setOptions(H.upazilasByDistrict[dist]);
        }

        if (H.districtToDivision && H.districtToDivision[dist]) {
            const autoDiv = H.districtToDivision[dist];
            if (!divisionCombo.input.value) {
                divisionCombo.setValue(autoDiv, false);
            }
        }
    }

    if (divisionCombo.input.value) updateDistricts(divisionCombo.input.value);
    if (districtCombo.input.value) updateUpazilas(districtCombo.input.value);
});
</script>
@endsection
