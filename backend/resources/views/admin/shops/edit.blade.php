@extends('admin.layout')

@section('title', 'Update Shop - ' . $shop->name)
@section('page-title', 'Update Shop (super_admin/shops_update/' . $shop->id . ')')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('admin.shops.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Shop Management System</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-400">Shop ID: <strong class="text-gray-700 font-mono">#{{ $shop->id }}</strong></span>
        </div>
    </div>

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

    @if(isset($errors) && $errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1 shadow-sm">
        <div class="font-bold flex items-center gap-2">
            <i class="fa fa-circle-exclamation text-rose-600"></i>
            <span>Please correct the errors below:</span>
        </div>
        <ul class="list-disc list-inside text-rose-700 text-[11px] pl-4">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Shop Overview Hero Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white border-2 border-gray-100 shadow-sm p-1 flex items-center justify-center overflow-hidden flex-shrink-0">
                <img src="{{ $shop->logo_url }}" alt="{{ $shop->name }}" class="w-full h-full object-contain"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=1e3a8a&color=fff&size=100'">
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-lg font-extrabold text-gray-900">{{ $shop->name }}</h2>
                    @if($shop->home_feature || $shop->is_featured)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 flex items-center gap-1 shadow-xs">
                            <i class="fa fa-star text-[9px]"></i> Home Feature
                        </span>
                    @endif
                    @if($shop->status === 'active' || $shop->status === '1')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 capitalize">{{ $shop->status }}</span>
                    @endif
                </div>
                <div class="text-xs text-gray-500 flex flex-wrap items-center gap-3 mt-1 font-mono">
                    <span><i class="fa fa-phone text-blue-500 text-[10px] mr-1"></i>{{ $shop->phone ?? $shop->mobile ?? 'N/A' }}</span>
                    <span>&bull;</span>
                    <span><i class="fa fa-envelope text-gray-400 text-[10px] mr-1"></i>{{ $shop->email ?? 'N/A' }}</span>
                    <span>&bull;</span>
                    <span><i class="fa fa-map-pin text-emerald-500 text-[10px] mr-1"></i>{{ $shop->upazila ?? $shop->district ?? 'All Bangladesh' }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100">
            <div class="text-right">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Shop Balance</span>
                <span class="text-base font-extrabold font-mono {{ $shop->balance < 0 ? 'text-rose-600' : 'text-blue-700' }}">
                    ৳ {{ number_format($shop->balance, 2) }}
                </span>
            </div>
            <div class="h-8 w-[1px] bg-gray-200 hidden sm:block"></div>
            <div class="text-right">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Commission</span>
                <span class="text-base font-extrabold font-mono text-emerald-700">
                    {{ $shop->commission_rate }}%
                </span>
            </div>
        </div>
    </div>

    <!-- Section Navigation Tabs -->
    <div class="bg-white p-2 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-2 overflow-x-auto text-xs font-bold">
        <button type="button" onclick="switchTab('all')" id="tab-btn-all" class="tab-btn px-4 py-2 rounded-xl transition bg-blue-600 text-white shadow-xs">
            <i class="fa fa-table-cells-large mr-1.5"></i> All Sections
        </button>
        <button type="button" onclick="switchTab('personal')" id="tab-btn-personal" class="tab-btn px-4 py-2 rounded-xl transition text-gray-600 hover:bg-gray-100">
            <i class="fa fa-address-card mr-1.5 text-blue-600"></i> Registration & Personal
        </button>
        <button type="button" onclick="switchTab('photo')" id="tab-btn-photo" class="tab-btn px-4 py-2 rounded-xl transition text-gray-600 hover:bg-gray-100">
            <i class="fa fa-image mr-1.5 text-indigo-600"></i> Photo
        </button>
        <button type="button" onclick="switchTab('user')" id="tab-btn-user" class="tab-btn px-4 py-2 rounded-xl transition text-gray-600 hover:bg-gray-100">
            <i class="fa fa-user-lock mr-1.5 text-emerald-600"></i> User Update
        </button>
        <button type="button" onclick="switchTab('address')" id="tab-btn-address" class="tab-btn px-4 py-2 rounded-xl transition text-gray-600 hover:bg-gray-100">
            <i class="fa fa-location-dot mr-1.5 text-amber-600"></i> Address
        </button>
        <button type="button" onclick="switchTab('category')" id="tab-btn-category" class="tab-btn px-4 py-2 rounded-xl transition text-gray-600 hover:bg-gray-100">
            <i class="fa fa-tags mr-1.5 text-purple-600"></i> Shop Category
        </button>
    </div>

    <!-- 5 Dedicated Section Forms Matching Live CodeIgniter Update Forms -->
    <div class="space-y-6" id="sections-container">

        <!-- Section 1: Registration Info / Personal -->
        <div class="section-card bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" id="section-personal">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Registration Info / Personal</h3>
                        <p class="text-[11px] text-gray-400">Basic merchant information and business communication contact</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full uppercase">Section 1</span>
            </div>

            <form action="{{ route('admin.shops.update_personal', $shop->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="id" value="{{ $shop->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="name">Name <span class="text-rose-500">*</span></label>
                        <input type="text" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="name" id="name" value="{{ old('name', $shop->name) }}" required />
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="email">Email</label>
                        <input type="email" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="email" id="email" value="{{ old('email', $shop->email) }}" />
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="status">Status</label>
                        <select name="status" id="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="active" {{ old('status', $shop->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $shop->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ old('status', $shop->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="suspended" {{ old('status', $shop->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <!-- Mobile -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="mobile">Mobile</label>
                        <input type="text" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="mobile" id="mobile" value="{{ old('mobile', $shop->phone ?? $shop->mobile) }}" placeholder="e.g. 01700000000" />
                    </div>

                    <!-- Sale commission -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="sale_commission">Sale commission (%)</label>
                        <input type="number" step="0.01" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-emerald-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="sale_commission" id="sale_commission" value="{{ old('sale_commission', $shop->commission_rate) }}" />
                    </div>

                    <!-- Balance (Current view / edit) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="balance">Current Balance (৳)</label>
                        <input type="number" step="0.01" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="balance" id="balance" value="{{ old('balance', $shop->balance) }}" />
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1" for="address">Address</label>
                    <textarea name="address" id="address" rows="2"
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">{{ old('address', $shop->address) }}</textarea>
                </div>

                <!-- Comment -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1" for="comment">Comment</label>
                    <textarea name="comment" id="comment" rows="2"
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                              placeholder="Internal administrative comments or seller notes...">{{ old('comment', $shop->comment) }}</textarea>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-1.5">
                        <i class="fa fa-save"></i>
                        <span>Update Registration Info</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Section 2: Photo (Logo & ProfileImage) -->
        <div class="section-card bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" id="section-photo">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fa fa-image"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Photo</h3>
                        <p class="text-[11px] text-gray-400">Shop Logo banner and square profile branding image</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full uppercase">Section 2</span>
            </div>

            <form action="{{ route('admin.shops.update_photo', $shop->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="id" value="{{ $shop->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 1. Logo -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl space-y-3">
                        <label class="block text-xs font-bold text-gray-800">
                            Logo
                        </label>

                        <div class="flex items-center gap-4">
                            <div class="w-32 h-16 bg-white border border-gray-200 rounded-xl p-1 flex items-center justify-center overflow-hidden shadow-xs">
                                <img src="{{ $shop->logo_url }}" alt="Logo" class="max-w-full max-h-full object-contain" id="preview_logo"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=1e3a8a&color=fff&size=100'">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="logo_file" id="logo_file" accept="image/*"
                                       onchange="previewImage(this, 'preview_logo')"
                                       class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer" />
                                <p class="text-[11px] text-gray-500 mt-2 font-medium">
                                    Max. file size 1024KB and (width=350px) x (height=100px)
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. ProfileImage -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl space-y-3">
                        <label class="block text-xs font-bold text-gray-800">
                            ProfileImage
                        </label>

                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 bg-white border border-gray-200 rounded-xl p-1 flex items-center justify-center overflow-hidden shadow-xs">
                                <img src="{{ $shop->image_url }}" alt="Profile" class="w-full h-full object-cover rounded-lg" id="preview_profile"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($shop->name) }}&background=4f46e5&color=fff&size=100'">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="profile_image" id="profile_image" accept="image/*"
                                       onchange="previewImage(this, 'preview_profile')"
                                       class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                                <p class="text-[11px] text-gray-500 mt-2 font-medium">
                                    Max. file size 1024KB and (width=160px) x (height=160px)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-1.5">
                        <i class="fa fa-upload"></i>
                        <span>Upload & Update Photos</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Section 3: User Update (Email, Password, Confirm Password) -->
        <div class="section-card bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" id="section-user">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="fa fa-user-lock"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">User Update</h3>
                        <p class="text-[11px] text-gray-400">Portal login credentials and account access details</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full uppercase">Section 3</span>
            </div>

            <form action="{{ route('admin.shops.update_user', $shop->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="id" value="{{ $shop->id }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="user_email">Email <span class="text-rose-500">*</span></label>
                        <input type="email" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="email" id="user_email" value="{{ old('email', $shop->email ?? ($shop->user->email ?? '')) }}" required />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="password">Password (Leave blank to keep current)</label>
                        <input type="password" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="password" id="password" placeholder="New Password" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="con_password">Confirm Password</label>
                        <input type="password" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="con_password" id="con_password" placeholder="Confirm Password" />
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-1.5">
                        <i class="fa fa-key"></i>
                        <span>Update User Credentials</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Section 4: Address -->
        <div class="section-card bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" id="section-address">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                        <i class="fa fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Address</h3>
                        <p class="text-[11px] text-gray-400">Geographic territory: Division, District, Upazila, Pourashava/Union, Ward, House Address</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full uppercase">Section 4</span>
            </div>

            <form action="{{ route('admin.shops.update_address', $shop->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="id" value="{{ $shop->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Division -->
                    <div class="searchable-combobox-filter relative" id="edit_division_box">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Division</label>
                        <div class="relative">
                            <input type="text" name="division" id="edit_division" value="{{ old('division', $shop->division) }}"
                                   autocomplete="off" placeholder="Select Division..."
                                   class="combobox-input w-full py-2.5 pl-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <button type="button" class="combobox-toggle absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                                <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                                <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching division</div>
                            </div>
                        </div>
                    </div>

                    <!-- District -->
                    <div class="searchable-combobox-filter relative" id="edit_district_box">
                        <label class="block text-xs font-bold text-gray-700 mb-1">District</label>
                        <div class="relative">
                            <input type="text" name="district" id="edit_district" value="{{ old('district', $shop->district) }}"
                                   autocomplete="off" placeholder="Select District..."
                                   class="combobox-input w-full py-2.5 pl-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <button type="button" class="combobox-toggle absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                                <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                                <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching district</div>
                            </div>
                        </div>
                    </div>

                    <!-- Upazila -->
                    <div class="searchable-combobox-filter relative" id="edit_upazila_box">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Upazila</label>
                        <div class="relative">
                            <input type="text" name="upazila" id="edit_upazila" value="{{ old('upazila', $shop->upazila) }}"
                                   autocomplete="off" placeholder="Select Upazila..."
                                   class="combobox-input w-full py-2.5 pl-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <button type="button" class="combobox-toggle absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                                <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                                <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching upazila</div>
                            </div>
                        </div>
                    </div>

                    <!-- Pourashava/Union -->
                    <div class="searchable-combobox-filter relative" id="edit_pourashava_box">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pourashava/Union</label>
                        <div class="relative">
                            <input type="text" name="pourashava" id="edit_pourashava" value="{{ old('pourashava', $shop->pourashava) }}"
                                   autocomplete="off" placeholder="Pourashava or Union name..."
                                   class="combobox-input w-full py-2.5 pl-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <button type="button" class="combobox-toggle absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                                <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                                <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching record</div>
                            </div>
                        </div>
                    </div>

                    <!-- Ward -->
                    <div class="searchable-combobox-filter relative" id="edit_ward_box">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Ward</label>
                        <div class="relative">
                            <input type="text" name="ward" id="edit_ward" value="{{ old('ward', $shop->ward) }}"
                                   autocomplete="off" placeholder="Ward number or name..."
                                   class="combobox-input w-full py-2.5 pl-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <button type="button" class="combobox-toggle absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 text-xs">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="combobox-dropdown hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 overflow-hidden text-xs">
                                <ul class="combobox-list max-h-48 overflow-y-auto divide-y divide-gray-50"></ul>
                                <div class="combobox-empty hidden p-3 text-center text-gray-400 text-xs">No matching ward</div>
                            </div>
                        </div>
                    </div>

                    <!-- House Address -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="house_address">House Address</label>
                        <input type="text" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition"
                               name="house_address" id="house_address" value="{{ old('house_address', $shop->house_address) }}" placeholder="House #, Road #, Holding #" />
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-1.5">
                        <i class="fa fa-map-pin"></i>
                        <span>Update Address Details</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Section 5: Shop category -->
        <div class="section-card bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4" id="section-category">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                        <i class="fa fa-tags"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Shop category</h3>
                        <p class="text-[11px] text-gray-400">Category classification, priority rankings and homepage discovery flags</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full uppercase">Section 5</span>
            </div>

            <form action="{{ route('admin.shops.update_category', $shop->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="id" value="{{ $shop->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="category_id">Category</label>
                        <select name="category_id" id="category_id" onchange="loadSubcategories(this.value)"
                                class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $shop->category_id) == $category->id || old('category', $shop->category) == $category->name) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SubCategory -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="sub_category_id">SubCategory</label>
                        <select name="sub_category_id" id="sub_category_id"
                                class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="">Select SubCategory</option>
                            @foreach($subcategories as $subCat)
                                <option value="{{ $subCat->id }}" data-parent="{{ $subCat->parent_id }}"
                                        {{ (old('sub_category_id', $shop->sub_category_id) == $subCat->id || old('sub_category', $shop->sub_category) == $subCat->name) ? 'selected' : '' }}>
                                    {{ $subCat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Shop type -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="shop_type">Shop type</label>
                        <select name="shop_type" id="shop_type"
                                class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="regular" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'regular' ? 'selected' : '' }}>Regular Shop</option>
                            <option value="express" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'express' ? 'selected' : '' }}>Amar Bangla Express Store</option>
                            <option value="local" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'local' ? 'selected' : '' }}>Local Shop</option>
                            <option value="food" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'food' ? 'selected' : '' }}>Amar Food</option>
                            <option value="grocery" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'grocery' ? 'selected' : '' }}>Grocery</option>
                            <option value="pharmacy" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option value="fashion" {{ old('shop_type', $shop->shop_type ?? $shop->type) === 'fashion' ? 'selected' : '' }}>Fashion & Apparel</option>
                        </select>
                    </div>

                    <!-- Priority type -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="priority_type">Priority type</label>
                        <select name="priority_type" id="priority_type"
                                class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="general" {{ old('priority_type', $shop->priority_type) === 'general' ? 'selected' : '' }}>General</option>
                            <option value="standard" {{ old('priority_type', $shop->priority_type) === 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="high" {{ old('priority_type', $shop->priority_type) === 'high' ? 'selected' : '' }}>High Priority</option>
                            <option value="premium" {{ old('priority_type', $shop->priority_type) === 'premium' ? 'selected' : '' }}>Premium Merchant</option>
                            <option value="top" {{ old('priority_type', $shop->priority_type) === 'top' ? 'selected' : '' }}>Top Tier (VIP)</option>
                        </select>
                    </div>

                    <!-- Home feature -->
                    <div class="sm:col-span-2 flex items-center pt-5">
                        <label class="relative flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="home_feature" id="home_feature" value="1"
                                   {{ (old('home_feature', $shop->home_feature) || $shop->is_featured) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="text-xs font-bold text-gray-800 select-none">
                                Home feature <span class="text-gray-400 font-normal">(Display prominently on mobile app and web homepage)</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-1.5">
                        <i class="fa fa-shapes"></i>
                        <span>Update Category & Priority</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<!-- Interactive Tab Switcher & Cascading Address Logic -->
<script>
window.GLOBAL_ADDRESS_HIERARCHY = @json($hierarchy);

function switchTab(tabId) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-xs');
        btn.classList.add('text-gray-600', 'hover:bg-gray-100');
    });

    const activeBtn = document.getElementById('tab-btn-' + tabId);
    if (activeBtn) {
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-xs');
        activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
    }

    const cards = document.querySelectorAll('.section-card');
    if (tabId === 'all') {
        cards.forEach(card => card.classList.remove('hidden'));
    } else {
        cards.forEach(card => card.classList.add('hidden'));
        const target = document.getElementById('section-' + tabId);
        if (target) {
            target.classList.remove('hidden');
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function switchTab(targetTab) {
    const tabMap = {
        'all': 'all',
        'personal': 'personal',
        'registertab': 'personal',
        'custom-tabs-four-home': 'personal',
        'photo': 'photo',
        'phototab': 'photo',
        'custom-tabs-four-messages': 'photo',
        'user': 'user',
        'userUpdate': 'user',
        'custom-tabs-four-profile': 'user',
        'address': 'address',
        'addressSettings': 'address',
        'custom-tabs-four-settings': 'address',
        'category': 'category',
        'shopCategory': 'category'
    };

    const tab = tabMap[targetTab] || targetTab;
    const allSections = document.querySelectorAll('.section-card');
    const allBtns = document.querySelectorAll('.tab-btn');

    allBtns.forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-xs');
        btn.classList.add('text-gray-600', 'hover:bg-gray-100');
    });

    const activeBtn = document.getElementById('tab-btn-' + tab);
    if (activeBtn) {
        activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-xs');
    }

    allSections.forEach(sec => {
        if (tab === 'all') {
            sec.style.display = 'block';
        } else {
            sec.style.display = (sec.id === 'section-' + tab) ? 'block' : 'none';
        }
    });

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchTab(hash);
    }
});
window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchTab(hash);
    }
});

function loadSubcategories(parentId) {
    const subCatSelect = document.getElementById('sub_category_id');
    if (!subCatSelect) return;

    const options = subCatSelect.querySelectorAll('option');
    options.forEach(opt => {
        if (!opt.value) {
            opt.style.display = '';
            return;
        }
        const optParent = opt.getAttribute('data-parent');
        if (!parentId || optParent === String(parentId)) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });

    // If currently selected option is now hidden, reset to blank
    const selected = subCatSelect.selectedOptions[0];
    if (selected && selected.value && selected.style.display === 'none') {
        subCatSelect.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    const currentCat = document.getElementById('category_id');
    if (currentCat && currentCat.value) {
        loadSubcategories(currentCat.value);
    }

    const HIERARCHY = window.GLOBAL_ADDRESS_HIERARCHY || {};

    const editDivisionCombo = new SearchableCombobox({
        id: 'edit_division_box',
        options: HIERARCHY.divisions || [],
        badgeLabel: 'Divisions',
        onSelect: (selectedDiv) => {
            updateEditDistricts(selectedDiv);
        },
        onChange: (val) => {
            updateEditDistricts(val);
        }
    });

    const editDistrictCombo = new SearchableCombobox({
        id: 'edit_district_box',
        options: HIERARCHY.allDistricts || [],
        badgeLabel: 'Districts',
        onSelect: (selectedDist) => {
            updateEditUpazilas(selectedDist);
        },
        onChange: (val) => {
            updateEditUpazilas(val);
        }
    });

    const editUpazilaCombo = new SearchableCombobox({
        id: 'edit_upazila_box',
        options: HIERARCHY.allUpazilas || [],
        badgeLabel: 'Upazilas',
        onSelect: () => {},
        onChange: () => {}
    });

    const editPourashavaCombo = new SearchableCombobox({
        id: 'edit_pourashava_box',
        options: HIERARCHY.pourashavas || [],
        badgeLabel: 'Pourashavas/Unions',
        onSelect: () => {},
        onChange: () => {}
    });

    const editWardCombo = new SearchableCombobox({
        id: 'edit_ward_box',
        options: HIERARCHY.wards || [],
        badgeLabel: 'Wards',
        onSelect: () => {},
        onChange: () => {}
    });

    function updateEditDistricts(divisionName) {
        const div = (divisionName || '').trim();
        let availableDistricts = [];

        if (div && HIERARCHY.districtsByDivision && HIERARCHY.districtsByDivision[div]) {
            availableDistricts = HIERARCHY.districtsByDivision[div];
        } else {
            availableDistricts = HIERARCHY.allDistricts || [];
        }

        editDistrictCombo.setOptions(availableDistricts);

        const currentDistrict = document.getElementById('edit_district').value.trim();
        if (currentDistrict && !availableDistricts.includes(currentDistrict)) {
            editDistrictCombo.setValue('');
            updateEditUpazilas('');
        }
    }

    function updateEditUpazilas(districtName) {
        const dist = (districtName || '').trim();
        let availableUpazilas = [];

        if (dist && HIERARCHY.upazilasByDistrict && HIERARCHY.upazilasByDistrict[dist]) {
            availableUpazilas = HIERARCHY.upazilasByDistrict[dist];
        } else {
            availableUpazilas = HIERARCHY.allUpazilas || [];
        }

        editUpazilaCombo.setOptions(availableUpazilas);

        const currentUpazila = document.getElementById('edit_upazila').value.trim();
        if (currentUpazila && !availableUpazilas.includes(currentUpazila)) {
            editUpazilaCombo.setValue('');
        }
    }
});

// Combobox helper class
class SearchableCombobox {
    constructor(config) {
        this.container = document.getElementById(config.id);
        if (!this.container) return;

        this.input = this.container.querySelector('.combobox-input');
        this.toggleBtn = this.container.querySelector('.combobox-toggle');
        this.dropdown = this.container.querySelector('.combobox-dropdown');
        this.list = this.container.querySelector('.combobox-list');
        this.emptyMsg = this.container.querySelector('.combobox-empty');

        this.options = config.options || [];
        this.filteredOptions = [...this.options];
        this.onSelect = config.onSelect || null;
        this.onChange = config.onChange || null;

        this.isOpen = false;
        this.highlightIndex = -1;

        this.init();
    }

    init() {
        this.input.addEventListener('focus', () => this.open());
        this.input.addEventListener('input', () => {
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

        document.addEventListener('click', (e) => {
            if (!this.container.contains(e.target)) {
                this.close();
            }
        });
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

        this.dropdown.classList.remove('hidden');
        this.isOpen = true;
        this.filter(this.input.value);
    }

    close() {
        this.dropdown.classList.add('hidden');
        this.isOpen = false;
        this.highlightIndex = -1;
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
            li.className = 'px-3 py-2 cursor-pointer hover:bg-blue-50 flex items-center justify-between text-xs text-gray-700 transition';
            li.dataset.index = idx;

            const isSelected = opt.toLowerCase() === currentValue;
            li.innerHTML = `
                <span class="${isSelected ? 'font-bold text-blue-600' : ''}">${opt}</span>
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
        const items = this.list.querySelectorAll('li');
        if (items.length === 0) return;
        this.highlightIndex = (this.highlightIndex + 1) % items.length;
        this.updateHighlight(items);
    }

    highlightPrev() {
        const items = this.list.querySelectorAll('li');
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
}
</script>
@endsection
