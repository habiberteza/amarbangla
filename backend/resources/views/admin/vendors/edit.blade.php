@extends('admin.layout')

@section('title', 'Update Seller - Amar Bangla Super Admin')
@section('page-title', 'Update Seller (super_admin/sellers_update/' . $vendor->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.vendors.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Seller List</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Update Seller #{{ $vendor->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-user-pen text-blue-600"></i>
                <span>Update Seller: {{ $vendor->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Manage registration credentials, personal info, territory address, and photo</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>super_admin/sellers_update/{{ $vendor->id }}</span>
        </span>
    </div>

    <!-- Flash Messages -->
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

    <!-- Profile Header Summary Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @php
                $photo = $vendor->image ? (str_starts_with($vendor->image, 'http') ? $vendor->image : asset($vendor->image)) : null;
            @endphp
            @if($photo)
                <img src="{{ $photo }}" alt="{{ $vendor->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-blue-100 shadow-sm">
            @else
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-black text-xl uppercase shadow-sm">
                    {{ substr($vendor->name, 0, 2) }}
                </div>
            @endif
            <div>
                <h3 class="text-base font-black text-gray-900">{{ $vendor->name }}</h3>
                <p class="text-xs text-gray-500 font-mono">{{ $vendor->phone ?? ($vendor->mobile ?? 'No phone') }} &bull; {{ $vendor->email }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ ($vendor->status ?? 'active') === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $vendor->status ?? 'active' }}
                    </span>
                    @if($vendor->shops && $vendor->shops->count() > 0)
                        <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">
                            <i class="fa fa-store mr-1"></i>{{ $vendor->shops->count() }} Outlet(s)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.sellers.orders', $vendor->id) }}"
               class="px-3.5 py-2 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-shopping-cart text-[11px]"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('admin.sellers.commission', $vendor->id) }}"
               class="px-3.5 py-2 bg-purple-50 hover:bg-purple-600 text-purple-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-percent text-[11px]"></i>
                <span>Commission</span>
            </a>

            <a href="{{ route('admin.sellers.ledger', $vendor->id) }}"
               class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-wallet text-[11px]"></i>
                <span>Ledger</span>
            </a>

            <form action="{{ route('admin.sellers.toggle-status', $vendor->id) }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs font-bold border transition flex items-center gap-1.5 {{ $vendor->status === 'active' ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                    <i class="fa {{ $vendor->status === 'active' ? 'fa-ban' : 'fa-check' }}"></i>
                    <span>{{ $vendor->status === 'active' ? 'Suspend Account' : 'Activate Account' }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Navigation Tabs: Registration Info | Personal | Address | Photo -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1">
        <button type="button" onclick="switchSellerTab('tab-registration')" id="btn-tab-registration"
                class="seller-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-id-badge"></i>
            <span>Registration Info</span>
        </button>

        <button type="button" onclick="switchSellerTab('tab-personal')" id="btn-tab-personal"
                class="seller-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-user"></i>
            <span>Personal</span>
        </button>

        <button type="button" onclick="switchSellerTab('tab-address')" id="btn-tab-address"
                class="seller-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-location-dot"></i>
            <span>Address</span>
        </button>

        <button type="button" onclick="switchSellerTab('tab-photo')" id="btn-tab-photo"
                class="seller-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-image"></i>
            <span>Photo</span>
        </button>
    </div>

    <!-- TAB 1: Registration Info -->
    <!-- Fields: Name, Mobile, Password, Confirm Password -->
    <div id="tab-registration" class="seller-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.sellers.update_credentials', $vendor->id) }}" method="POST" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-id-badge text-blue-600"></i>
                        <span>Registration Info</span>
                    </h3>
                    <p class="text-xs text-gray-400">Account login credentials and mobile contact</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/sellers_update_action/{{ $vendor->id }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" required value="{{ old('name', $vendor->name) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Mobile <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="mobile" required value="{{ old('mobile', $vendor->mobile ?: $vendor->phone) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Password <span class="text-gray-400 font-normal">(Leave blank to keep unchanged)</span>
                    </label>
                    <input type="password" name="password" minlength="6" placeholder="Enter new password..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" minlength="6" placeholder="Re-type new password..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Registration Info</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: Personal -->
    <!-- Fields: Father Name, Mother Name, Age, Agent -->
    <div id="tab-personal" class="seller-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.sellers.update_personal', $vendor->id) }}" method="POST" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-user text-blue-600"></i>
                        <span>Personal Information</span>
                    </h3>
                    <p class="text-xs text-gray-400">Parental details, age, and assigned territory field agent</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/sellers_personal_update/{{ $vendor->id }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Father Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Father Name
                    </label>
                    <input type="text" name="father_name" value="{{ old('father_name', $vendor->father_name) }}"
                           placeholder="Enter father's name..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Mother Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Mother Name
                    </label>
                    <input type="text" name="mother_name" value="{{ old('mother_name', $vendor->mother_name) }}"
                           placeholder="Enter mother's name..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Age
                    </label>
                    <input type="number" name="age" min="15" max="100" value="{{ old('age', $vendor->age) }}"
                           placeholder="e.g. 32"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Agent -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Agent
                    </label>
                    <select name="agent_id"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">-- Select Field Agent --</option>
                        @foreach($agents as $ag)
                            <option value="{{ $ag->id }}" {{ old('agent_id', $vendor->agent_id) == $ag->id ? 'selected' : '' }}>
                                {{ $ag->name }} ({{ $ag->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Personal Info</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 3: Address -->
    <!-- Fields: Division, District, Upazila, Pourashava/Union *, Ward * -->
    <div id="tab-address" class="seller-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.sellers.update_address', $vendor->id) }}" method="POST" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-location-dot text-blue-600"></i>
                        <span>Address & Territorial Region</span>
                    </h3>
                    <p class="text-xs text-gray-400">Merchant outlet geographic location</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/sellers_address_update/{{ $vendor->id }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Division -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Division
                    </label>
                    <input type="text" name="division" value="{{ old('division', $vendor->division) }}"
                           placeholder="e.g. Dhaka, Chittagong..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- District -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        District
                    </label>
                    <input type="text" name="district" value="{{ old('district', $vendor->district) }}"
                           placeholder="e.g. Dhaka, Gazipur, Narayanganj..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Upazila -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Upazila
                    </label>
                    <input type="text" name="upazila" value="{{ old('upazila', $vendor->upazila) }}"
                           placeholder="e.g. Mirpur, Dhanmondi, Savar..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Pourashava/Union * -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Pourashava/Union <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="pourashava" required value="{{ old('pourashava', $vendor->pourashava) }}"
                           placeholder="Enter Pourashava or Union parishad..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Ward * -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Ward <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="ward" required value="{{ old('ward', $vendor->ward) }}"
                           placeholder="e.g. Ward 04, Ward 12..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Address</span>
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 4: Photo -->
    <!-- Fields: Image (Max. file size 1024KB and width=300px x height=300px) -->
    <div id="tab-photo" class="seller-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.sellers.update_photo', $vendor->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-image text-blue-600"></i>
                        <span>Profile Photo</span>
                    </h3>
                    <p class="text-xs text-gray-400">Seller portrait image</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">super_admin/sellers_photo_update/{{ $vendor->id }}</span>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-6 p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="text-center shrink-0">
                    @if($photo)
                        <img src="{{ $photo }}" alt="{{ $vendor->name }}" id="preview_img"
                             class="w-28 h-28 rounded-2xl object-cover border-2 border-white shadow-md mx-auto">
                    @else
                        <div id="preview_placeholder" class="w-28 h-28 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-black text-2xl uppercase shadow-md mx-auto">
                            {{ substr($vendor->name, 0, 2) }}
                        </div>
                    @endif
                    <span class="inline-block text-[10px] text-gray-400 font-semibold mt-2">Current Photo</span>
                </div>

                <div class="flex-1 space-y-3 w-full">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Image <span class="text-gray-400 font-normal">No file chosen</span>
                        </label>
                        <input type="file" name="image" id="input_image" required accept="image/*"
                               onchange="previewSellerImage(this)"
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer border border-gray-200 rounded-xl p-1 bg-white">
                    </div>
                    <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 p-2.5 rounded-xl font-medium flex items-center gap-1.5">
                        <i class="fa fa-circle-info text-amber-600"></i>
                        <span>Max. file size 1024KB and (width=300px) x (height=300px)</span>
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-upload"></i>
                    <span>Upload & Update Photo</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
// Switch between the 4 multi-sections
function switchSellerTab(tabId) {
    document.querySelectorAll('.seller-tab-pane').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.seller-tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600');
    });

    const activePane = document.getElementById(tabId);
    if (activePane) {
        activePane.classList.remove('hidden');
    }

    const activeBtn = document.getElementById('btn-' + tabId);
    if (activeBtn) {
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
    }
}

// Live Image Preview
function previewSellerImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let img = document.getElementById('preview_img');
            const placeholder = document.getElementById('preview_placeholder');
            if (!img && placeholder) {
                img = document.createElement('img');
                img.id = 'preview_img';
                img.className = 'w-28 h-28 rounded-2xl object-cover border-2 border-white shadow-md mx-auto';
                placeholder.replaceWith(img);
            }
            if (img) {
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
