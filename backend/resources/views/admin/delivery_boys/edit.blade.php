@extends('admin.layout')

@section('title', 'Update Delivery Boy - ' . $deliveryBoy->name)
@section('page-title', 'Update Delivery Boy (super_admin/delivery_boy_update/' . $deliveryBoy->id . ')')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.delivery_boys.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Delivery Boy List</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Delivery Boy #{{ $deliveryBoy->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-motorcycle text-sky-600"></i>
                <span>Delivery Boy Update: {{ $deliveryBoy->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Manage credentials, personal info, delivery territory area, and photo</p>
        </div>

        <!-- Quick Action Jump Links -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.delivery_boys.orders', $deliveryBoy->id) }}"
               class="px-3.5 py-1.5 bg-sky-50 hover:bg-sky-600 text-sky-700 hover:text-white border border-sky-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-boxes-packing text-[11px]"></i>
                <span>Orders</span>
            </a>
            <a href="{{ route('admin.delivery_boys.commissions', $deliveryBoy->id) }}"
               class="px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-percent text-[11px]"></i>
                <span>Commission</span>
            </a>
            <a href="{{ route('admin.delivery_boys.ledger', $deliveryBoy->id) }}"
               class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-book text-[11px]"></i>
                <span>Ledger</span>
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-50 text-sky-800 border border-sky-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>delivery_boy_update/{{ $deliveryBoy->id }}</span>
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Profile Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @if($deliveryBoy->image)
                <img src="{{ asset($deliveryBoy->image) }}" alt="{{ $deliveryBoy->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-sky-100 shadow-sm">
            @else
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-600 to-blue-700 text-white font-extrabold text-2xl flex items-center justify-center shadow-sm">
                    {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-extrabold text-gray-900">{{ $deliveryBoy->name }}</h2>
                    @if($deliveryBoy->status === 'active' || $deliveryBoy->status == '1')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">Inactive</span>
                    @endif
                </div>
                <div class="text-xs text-gray-500 flex items-center gap-3 mt-1 font-mono">
                    <span><i class="fa fa-phone text-[10px] mr-1 text-sky-500"></i>{{ $deliveryBoy->phone }}</span>
                    <span>&bull;</span>
                    <span><i class="fa fa-map-pin text-[10px] mr-1 text-emerald-500"></i>{{ $deliveryBoy->upazila ?? $deliveryBoy->district ?? 'No Area Assigned' }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
            <div class="text-right">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Ledger Balance</span>
                <span class="text-base font-extrabold font-mono {{ $deliveryBoy->balance < 0 ? 'text-rose-600' : 'text-emerald-700' }}">
                    ৳ {{ number_format($deliveryBoy->balance, 2) }}
                </span>
            </div>
            <div class="h-8 w-[1px] bg-gray-200 hidden sm:block"></div>
            <div class="text-right">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Cash In Hand</span>
                <span class="text-base font-extrabold font-mono text-gray-800">
                    ৳ {{ number_format($deliveryBoy->cash_in_hand, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs:
         1. Registraion Info (#custom-tabs-four-home)
         2. Personal (#custom-tabs-four-profile)
         3. Address (#custom-tabs-four-settings)
         4. Photo (#custom-tabs-four-messages) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="deliveryBoyTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchDeliveryBoyTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="delivery-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-sky-600 text-white shadow-sm">
            <i class="fa fa-id-badge"></i>
            <span>Registraion Info</span>
        </a>

        <a href="#custom-tabs-four-profile" onclick="switchDeliveryBoyTab('custom-tabs-four-profile'); return false;" id="btn-custom-tabs-four-profile"
           class="delivery-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-user"></i>
            <span>Personal</span>
        </a>

        <a href="#custom-tabs-four-settings" onclick="switchDeliveryBoyTab('custom-tabs-four-settings'); return false;" id="btn-custom-tabs-four-settings"
           class="delivery-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-location-dot"></i>
            <span>Address</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchDeliveryBoyTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="delivery-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-image"></i>
            <span>Photo</span>
        </a>
    </div>

    <!-- TAB 1: Registraion Info (#custom-tabs-four-home) -->
    <div id="custom-tabs-four-home" class="delivery-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa fa-id-card"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Registraion Info / Account Credentials</h3>
                    <p class="text-xs text-gray-400">Login username, mobile phone contact, and security password</p>
                </div>
            </div>
            <span class="text-[11px] font-mono text-gray-400">super_admin/delivery_boy_update/{{ $deliveryBoy->id }}#custom-tabs-four-home</span>
        </div>

        <form action="{{ route('admin.delivery_boys.update_credentials', $deliveryBoy->id) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id" value="{{ $deliveryBoy->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1" for="name">Name <span class="text-rose-500">*</span></label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none"
                           name="name" id="name" value="{{ old('name', $deliveryBoy->name) }}" required />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1" for="mobile">Mobile <span class="text-rose-500">*</span></label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none"
                           name="mobile" id="mobile" value="{{ old('mobile', $deliveryBoy->phone) }}" required />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1" for="password">Password (Leave blank to keep current)</label>
                    <input type="password" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none"
                           name="password" id="password" placeholder="New Password" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1" for="con_password">Confirm Password</label>
                    <input type="password" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-sky-600 focus:outline-none"
                           name="con_password" id="con_password" placeholder="Confirm Password" />
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.delivery_boys.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: Personal (#custom-tabs-four-profile) -->
    <div id="custom-tabs-four-profile" class="delivery-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                    <i class="fa fa-user"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Personal Information</h3>
                    <p class="text-xs text-gray-400">Parents info, age, account status, and assigned agent</p>
                </div>
            </div>
            <span class="text-[11px] font-mono text-gray-400">super_admin/delivery_boy_update/{{ $deliveryBoy->id }}#custom-tabs-four-profile</span>
        </div>

        <form action="{{ route('admin.delivery_boys.update_personal', $deliveryBoy->id) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id" value="{{ $deliveryBoy->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Father's Name</label>
                    <input type="text" name="father_name" value="{{ old('father_name', $deliveryBoy->father_name) }}"
                           placeholder="Father's full name"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mother's Name</label>
                    <input type="text" name="mother_name" value="{{ old('mother_name', $deliveryBoy->mother_name) }}"
                           placeholder="Mother's full name"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Age (Years)</label>
                    <input type="number" min="14" max="100" name="age" value="{{ old('age', $deliveryBoy->age) }}"
                           placeholder="e.g. 26"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="1" {{ ($deliveryBoy->status === 'active' || $deliveryBoy->status == '1') ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($deliveryBoy->status === 'inactive' || $deliveryBoy->status == '0') ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Assigned Agent</label>
                    <select name="agent_id" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="">No Agent Assigned</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ (old('agent_id', $deliveryBoy->agent_id) == $agent->id) ? 'selected' : '' }}>
                                {{ $agent->name }} ({{ $agent->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.delivery_boys.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 3: Address (#custom-tabs-four-settings) -->
    <div id="custom-tabs-four-settings" class="delivery-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa fa-map-location-dot"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Delivery Area / Address</h3>
                    <p class="text-xs text-gray-400">Assigned administrative division, district, upazila, pourashava, and ward</p>
                </div>
            </div>
            <span class="text-[11px] font-mono text-gray-400">super_admin/delivery_boy_update/{{ $deliveryBoy->id }}#custom-tabs-four-settings</span>
        </div>

        <form action="{{ route('admin.delivery_boys.update_address', $deliveryBoy->id) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id" value="{{ $deliveryBoy->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- 1. Division -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Division</label>
                    <select name="division" id="del_division" onchange="viewDeliveryDistrict(this.value)"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        <option value="">Please Select</option>
                        {!! divisionView($deliveryBoy->division) !!}
                    </select>
                </div>

                <!-- 2. District -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">District</label>
                    <select name="district" id="del_district" onchange="viewDeliveryUpazila(this.value)"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <!-- 3. Upazila -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Upazila</label>
                    <select name="upazila" id="del_upazila"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <!-- 4. Pourashava/Union -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pourashava / Union</label>
                    <select name="pourashava" id="del_pourashava"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        <option value="">Please Select</option>
                        {!! pourashavaUnion($deliveryBoy->pourashava) !!}
                    </select>
                </div>

                <!-- 5. Ward -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Ward</label>
                    <select name="ward" id="del_ward"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                        <option value="">Please Select</option>
                        {!! wardView($deliveryBoy->ward) !!}
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.delivery_boys.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 4: Photo (#custom-tabs-four-messages) -->
    <div id="custom-tabs-four-messages" class="delivery-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                    <i class="fa fa-image"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Profile Photo</h3>
                    <p class="text-xs text-gray-400">Rider avatar image upload and live preview</p>
                </div>
            </div>
            <span class="text-[11px] font-mono text-gray-400">super_admin/delivery_boy_update/{{ $deliveryBoy->id }}#custom-tabs-four-messages</span>
        </div>

        <form action="{{ route('admin.delivery_boys.update_photo', $deliveryBoy->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="id" value="{{ $deliveryBoy->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 border border-gray-200 rounded-2xl text-center">
                    @if($deliveryBoy->image)
                        <img src="{{ asset($deliveryBoy->image) }}" id="delBoyPhotoPreview" class="w-32 h-32 rounded-2xl object-cover shadow-md border-2 border-white" alt="{{ $deliveryBoy->name }}">
                        <p class="text-[11px] text-gray-400 mt-2 font-mono">{{ basename($deliveryBoy->image) }}</p>
                    @else
                        <div id="delBoyPhotoPreviewFallback" class="w-32 h-32 rounded-2xl bg-gradient-to-tr from-sky-600 to-blue-700 text-white font-extrabold text-4xl flex items-center justify-center shadow-md">
                            {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-gray-700" for="photo">
                        Select New Profile Photo <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="photo" id="photo" accept="image/*" required onchange="previewDelBoyPhoto(this)"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-100 file:text-sky-800 hover:file:bg-sky-200 cursor-pointer focus:outline-none">
                    <p class="text-[11px] text-gray-400">Accepts PNG, JPG, JPEG up to 2MB (recommended 300x300px)</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.delivery_boys.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-upload"></i>
                    <span>Upload & Update</span>
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Client-side Tab & Geo Logic -->
<script>
    const allDistricts = @json(districtView());
    const allUpazilas = @json(upazilasView());

    const initialDivision = @json((string)($deliveryBoy->division ?? ''));
    const initialDistrict = @json((string)($deliveryBoy->district ?? ''));
    const initialUpazila = @json((string)($deliveryBoy->upazila ?? ''));

    function viewDeliveryDistrict(divisionVal) {
        const districtSelect = document.getElementById('del_district');
        if (!districtSelect) return;
        districtSelect.innerHTML = '<option value="">Please Select</option>';

        if (!divisionVal) return;

        allDistricts.forEach(d => {
            if (d.division_id == divisionVal || String(d.division_id) === String(divisionVal)) {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.textContent = d.name + (d.bn_name ? ' (' + d.bn_name + ')' : '');
                if (String(initialDistrict) === String(d.id) || initialDistrict.toLowerCase() === d.name.toLowerCase()) {
                    opt.selected = true;
                }
                districtSelect.appendChild(opt);
            }
        });

        viewDeliveryUpazila(districtSelect.value);
    }

    function viewDeliveryUpazila(districtVal) {
        const upazilaSelect = document.getElementById('del_upazila');
        if (!upazilaSelect) return;
        upazilaSelect.innerHTML = '<option value="">Please Select</option>';

        if (!districtVal) return;

        allUpazilas.forEach(u => {
            if (u.district_id == districtVal || String(u.district_id) === String(districtVal)) {
                const opt = document.createElement('option');
                opt.value = u.id;
                opt.textContent = u.name + (u.bn_name ? ' (' + u.bn_name + ')' : '');
                if (String(initialUpazila) === String(u.id) || initialUpazila.toLowerCase() === u.name.toLowerCase()) {
                    opt.selected = true;
                }
                upazilaSelect.appendChild(opt);
            }
        });
    }

    function previewDelBoyPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('delBoyPhotoPreview');
                const fallback = document.getElementById('delBoyPhotoPreviewFallback');
                if (preview) {
                    preview.src = e.target.result;
                } else if (fallback) {
                    fallback.outerHTML = '<img id="delBoyPhotoPreview" src="' + e.target.result + '" class="w-32 h-32 rounded-2xl object-cover shadow-md border-2 border-white">';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function switchDeliveryBoyTab(targetTabId) {
        const tabMap = {
            'custom-tabs-four-home': 'custom-tabs-four-home',
            'registertab': 'custom-tabs-four-home',
            'tab-registration': 'custom-tabs-four-home',
            'custom-tabs-four-profile': 'custom-tabs-four-profile',
            'personal': 'custom-tabs-four-profile',
            'tab-personal': 'custom-tabs-four-profile',
            'custom-tabs-four-settings': 'custom-tabs-four-settings',
            'address': 'custom-tabs-four-settings',
            'tab-address': 'custom-tabs-four-settings',
            'custom-tabs-four-messages': 'custom-tabs-four-messages',
            'photo': 'custom-tabs-four-messages',
            'tab-photo': 'custom-tabs-four-messages'
        };

        const activeId = tabMap[targetTabId] || 'custom-tabs-four-home';

        const panes = document.querySelectorAll('.delivery-tab-pane');
        panes.forEach(pane => {
            if (pane.id === activeId) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        });

        const tabButtons = [
            { id: 'btn-custom-tabs-four-home', tab: 'custom-tabs-four-home' },
            { id: 'btn-custom-tabs-four-profile', tab: 'custom-tabs-four-profile' },
            { id: 'btn-custom-tabs-four-settings', tab: 'custom-tabs-four-settings' },
            { id: 'btn-custom-tabs-four-messages', tab: 'custom-tabs-four-messages' }
        ];

        tabButtons.forEach(btnInfo => {
            const btn = document.getElementById(btnInfo.id);
            if (!btn) return;
            if (btnInfo.tab === activeId) {
                btn.className = 'delivery-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-sky-600 text-white shadow-sm';
            } else {
                btn.className = 'delivery-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200';
            }
        });

        if (history.replaceState) {
            history.replaceState(null, null, '#' + activeId);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const divEl = document.getElementById('del_division');
        if (divEl && divEl.value) {
            viewDeliveryDistrict(divEl.value);
        }

        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';
        if (hash) {
            switchDeliveryBoyTab(hash);
        } else {
            switchDeliveryBoyTab('custom-tabs-four-home');
        }
    });
</script>
@endsection
