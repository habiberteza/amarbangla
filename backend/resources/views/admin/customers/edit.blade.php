@extends('admin.layout')

@section('title', 'Update Customer - ' . ($customer->customer_name ?: $customer->name) . ' - Amar Bangla Super Admin')
@section('page-title', 'Customer Update (super_admin/customer_update/' . ($customer->customer_id ?? $customer->id) . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.customers.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Customer List</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Customer #{{ $customer->customer_id ?? $customer->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-user-gear text-amber-500"></i>
                <span>Customer Update: {{ $customer->customer_name ?: $customer->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Manage registration info, personal profile, location address, and customer photo</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <a href="{{ route('admin.customers.show', $customer->customer_id ?? $customer->id) }}"
               class="px-3.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-eye"></i>
                <span>View Orders</span>
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>customer_update/{{ $customer->customer_id ?? $customer->id }}</span>
            </span>
        </div>
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

    <!-- Customer Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @php
                $picFile = $customer->pic;
                $hasPic = $picFile && file_exists(public_path('uploads/customer_image/' . $picFile));
                $photoUrl = $hasPic ? asset('uploads/customer_image/' . $picFile) : asset('uploads/customer_image/no_image.jpg');
            @endphp
            <img src="{{ $photoUrl }}" alt="{{ $customer->customer_name ?: $customer->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-200 shadow-sm">
            <div>
                <h3 class="text-base font-black text-gray-900">{{ $customer->customer_name ?: $customer->name }}</h3>
                <p class="text-xs text-gray-500 font-mono">{{ $customer->mobile ?: ($customer->phone ?: 'No phone') }} &bull; {{ $customer->email ?: 'No email' }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ ($customer->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $customer->status ?? 'active' }}
                    </span>
                    @if($customer->customerType)
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                            <i class="fa fa-tag mr-1"></i>{{ $customer->customerType->type_name ?: $customer->customerType->name }}
                        </span>
                    @endif
                    <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                        ৳{{ number_format($customer->balance ?? 0, 2) }} Balance
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Registraion Info (#custom-tabs-four-home)
         2. Personal (#custom-tabs-four-profile)
         3. Address (#custom-tabs-four-settings)
         4. Photo (#custom-tabs-four-messages) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="customerTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchCustomerTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="customer-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-amber-500 text-white shadow-sm">
            <i class="fa fa-id-badge"></i>
            <span>Registraion Info</span>
        </a>

        <a href="#custom-tabs-four-profile" onclick="switchCustomerTab('custom-tabs-four-profile'); return false;" id="btn-custom-tabs-four-profile"
           class="customer-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-user"></i>
            <span>Personal</span>
        </a>

        <a href="#custom-tabs-four-settings" onclick="switchCustomerTab('custom-tabs-four-settings'); return false;" id="btn-custom-tabs-four-settings"
           class="customer-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-location-dot"></i>
            <span>Address</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchCustomerTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="customer-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-image"></i>
            <span>Photo</span>
        </a>
    </div>

    <!-- TAB 1: Registraion Info (#custom-tabs-four-home) -->
    <div id="custom-tabs-four-home" class="customer-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.customers.update_credentials', $customer->customer_id ?? $customer->id) }}" method="POST" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-id-badge text-amber-500"></i>
                        <span>Registraion Info</span>
                    </h3>
                    <p class="text-xs text-gray-400">Customer account credentials and user type classification</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/customer_update/{{ $customer->customer_id ?? $customer->id }}#custom-tabs-four-home</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- CustomerName -->
                <div>
                    <label for="CustomerName" class="block text-xs font-bold text-gray-700 mb-1.5">
                        CustomerName <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="customer_name" id="CustomerName" placeholder="CustomerName" value="{{ old('customer_name', $customer->customer_name ?: $customer->name) }}" required />
                </div>

                <!-- Mobile -->
                <div>
                    <label for="Mobile" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Mobile <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="mobile" id="Mobile" placeholder="Mobile" value="{{ old('mobile', $customer->mobile ?: $customer->phone) }}" required />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Password <span class="text-gray-400 font-normal">(Leave blank to keep unchanged)</span>
                    </label>
                    <input type="password" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="password" id="password" placeholder="Password" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="con_password" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Confirm Password
                    </label>
                    <input type="password" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="con_password" id="con_password" placeholder="Confirm Password" />
                </div>

                <!-- User Type -->
                <div class="md:col-span-2">
                    <label for="cus_type_id" class="block text-xs font-bold text-gray-700 mb-1.5">
                        User Type
                    </label>
                    <select class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            name="cus_type_id" id="cus_type_id">
                        <option value="">Please Select</option>
                        @foreach($types as $type)
                            @php
                                $typeKey = $type->cus_type_id ?? $type->id;
                                $typeName = $type->type_name ?: $type->name;
                            @endphp
                            <option value="{{ $typeKey }}" {{ old('cus_type_id', $customer->cus_type_id ?: $customer->customer_type_id) == $typeKey ? 'selected' : '' }}>
                                {{ $typeName }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" name="customer_id" value="{{ $customer->customer_id ?? $customer->id }}" />

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: Personal (#custom-tabs-four-profile) -->
    <div id="custom-tabs-four-profile" class="customer-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.customers.update_personal', $customer->customer_id ?? $customer->id) }}" method="POST" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-user text-amber-500"></i>
                        <span>Personal</span>
                    </h3>
                    <p class="text-xs text-gray-400">Parents info, age, and National ID Card identification</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/customer_update/{{ $customer->customer_id ?? $customer->id }}#custom-tabs-four-profile</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- FatherName -->
                <div>
                    <label for="FatherName" class="block text-xs font-bold text-gray-700 mb-1.5">
                        FatherName
                    </label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="father_name" id="FatherName" placeholder="FatherName" value="{{ old('father_name', $customer->father_name) }}" />
                </div>

                <!-- MotherName -->
                <div>
                    <label for="MotherName" class="block text-xs font-bold text-gray-700 mb-1.5">
                        MotherName
                    </label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="mother_name" id="MotherName" placeholder="MotherName" value="{{ old('mother_name', $customer->mother_name) }}" />
                </div>

                <!-- Age -->
                <div>
                    <label for="Age" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Age
                    </label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="age" id="Age" placeholder="Age" value="{{ old('age', $customer->age) }}" />
                </div>

                <!-- NID -->
                <div>
                    <label for="NID" class="block text-xs font-bold text-gray-700 mb-1.5">
                        NID
                    </label>
                    <input type="text" class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                           name="nid" id="NID" placeholder="NID" value="{{ old('nid', $customer->nid) }}" />
                </div>
            </div>

            <input type="hidden" name="customer_id" value="{{ $customer->customer_id ?? $customer->id }}" />

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 3: Address (#custom-tabs-four-settings) -->
    <div id="custom-tabs-four-settings" class="customer-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.customers.update_address', $customer->customer_id ?? $customer->id) }}" method="POST" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-location-dot text-amber-500"></i>
                        <span>Address</span>
                    </h3>
                    <p class="text-xs text-gray-400">Territory location, administrative area, and detailed street address</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/customer_update/{{ $customer->customer_id ?? $customer->id }}#custom-tabs-four-settings</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Division -->
                <div>
                    <label for="division" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Division
                    </label>
                    <select class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            name="division" id="division" onchange="viewdistrict(this.value)">
                        <option value="">Please Select</option>
                        {!! divisionView($customer->division) !!}
                    </select>
                </div>

                <!-- District -->
                <div>
                    <label for="district" class="block text-xs font-bold text-gray-700 mb-1.5">
                        District
                    </label>
                    <select class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            name="district" id="district" onchange="viewupazila(this.value)">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <!-- Upazila -->
                <div>
                    <label for="upazila" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Upazila
                    </label>
                    <select class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            name="upazila" id="upazila">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <!-- Pourashava/Union -->
                <div>
                    <label for="pourashava" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Pourashava/Union
                    </label>
                    <select class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            name="pourashava" id="pourashava">
                        <option value="">Please Select</option>
                        {!! pourashavaUnion($customer->pourashava) !!}
                    </select>
                </div>

                <!-- Ward -->
                <div>
                    <label for="ward" class="block text-xs font-bold text-gray-700 mb-1.5">
                        Ward
                    </label>
                    <select class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                            name="ward" id="ward">
                        <option value="">Please Select</option>
                        {!! wardView($customer->ward) !!}
                    </select>
                </div>

                <!-- House Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-xs font-bold text-gray-700 mb-1.5">
                        House Address
                    </label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full p-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:outline-none transition"
                              placeholder="House Address">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <input type="hidden" name="customer_id" value="{{ $customer->customer_id ?? $customer->id }}" />

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 4: Photo (#custom-tabs-four-messages) -->
    <div id="custom-tabs-four-messages" class="customer-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.customers.update_photo', $customer->customer_id ?? $customer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-image text-amber-500"></i>
                        <span>Photo</span>
                    </h3>
                    <p class="text-xs text-gray-400">Customer profile photo preview and image uploader</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/customer_update/{{ $customer->customer_id ?? $customer->id }}#custom-tabs-four-messages</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <!-- Photo Preview -->
                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 border border-gray-200 rounded-2xl text-center">
                    @php
                        $photoPath = $customer->pic && file_exists(public_path('uploads/customer_image/' . $customer->pic))
                            ? asset('uploads/customer_image/' . $customer->pic)
                            : asset('uploads/customer_image/no_image.jpg');
                    @endphp
                    <img src="{{ $photoPath }}" width="200" id="customerPhotoPreview" class="proimg rounded-2xl shadow-md border-2 border-white object-cover max-h-[200px]" alt="Customer Photo">
                    <span class="text-[11px] text-gray-500 mt-2 font-mono">{{ $customer->pic ?: 'no_image.jpg' }}</span>
                </div>

                <!-- Upload Control -->
                <div class="space-y-3">
                    <label for="pic" class="block text-xs font-bold text-gray-700">
                        Photo <span class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="pic" id="pic" accept="image/*" onchange="previewSelectedPhoto(this)"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer focus:outline-none" required />
                    <span class="help-block text-xs text-gray-500 block leading-relaxed">
                        <b>Max. file size 1024KB and (width=300px) x (height=300px)</b>
                    </span>
                </div>
            </div>

            <input type="hidden" name="customer_id" value="{{ $customer->customer_id ?? $customer->id }}" />

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update</span>
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Client-side Geo Data and Tab Switching Logic -->
<script>
    const allDistricts = @json(districtView());
    const allUpazilas = @json(upazilasView());

    const initialDivision = @json((string)($customer->division ?? ''));
    const initialDistrict = @json((string)($customer->district ?? ''));
    const initialUpazila = @json((string)($customer->upazila ?? ''));

    function viewdistrict(divisionVal) {
        const districtSelect = document.getElementById('district');
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

        // Trigger upazila change
        viewupazila(districtSelect.value);
    }

    function viewupazila(districtVal) {
        const upazilaSelect = document.getElementById('upazila');
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

    // Photo Live Preview
    function previewSelectedPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('customerPhotoPreview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Tab Switching Function
    function switchCustomerTab(targetTabId) {
        // Tab mapping normalization
        const tabMap = {
            'custom-tabs-four-home': 'custom-tabs-four-home',
            'registertab': 'custom-tabs-four-home',
            'tab-registration': 'custom-tabs-four-home',
            'custom-tabs-four-profile': 'custom-tabs-four-profile',
            'Personal': 'custom-tabs-four-profile',
            'tab-personal': 'custom-tabs-four-profile',
            'custom-tabs-four-settings': 'custom-tabs-four-settings',
            'Address': 'custom-tabs-four-settings',
            'tab-address': 'custom-tabs-four-settings',
            'custom-tabs-four-messages': 'custom-tabs-four-messages',
            'Photo': 'custom-tabs-four-messages',
            'tab-photo': 'custom-tabs-four-messages'
        };

        const activeId = tabMap[targetTabId] || 'custom-tabs-four-home';

        // Panes
        const panes = document.querySelectorAll('.customer-tab-pane');
        panes.forEach(pane => {
            if (pane.id === activeId) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        });

        // Tab buttons
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
                btn.className = 'customer-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-amber-500 text-white shadow-sm';
            } else {
                btn.className = 'customer-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200';
            }
        });

        // Update URL hash without reload
        if (history.replaceState) {
            history.replaceState(null, null, '#' + activeId);
        }
    }

    // Auto-initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize districts dropdown
        const divEl = document.getElementById('division');
        if (divEl && divEl.value) {
            viewdistrict(divEl.value);
        }

        // Check if session has active_tab
        const sessionTab = @json(session('active_tab', ''));
        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';

        if (sessionTab) {
            switchCustomerTab(sessionTab);
        } else if (hash) {
            switchCustomerTab(hash);
        } else {
            switchCustomerTab('custom-tabs-four-home');
        }
    });
</script>
@endsection
