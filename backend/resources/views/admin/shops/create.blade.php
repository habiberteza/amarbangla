@extends('admin.layout')

@section('title', 'Create Shop - Amar Bangla Super Admin')
@section('page-title', 'Create Shop (super_admin/shops_create)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.shops.index') }}"
           class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Shop Management System</span>
        </a>
    </div>

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

    <!-- Main Shop Create Form Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                    <i class="fa fa-store"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-gray-900 text-base">Register New Shop Outlet</h2>
                    <p class="text-xs text-gray-400">Configure core shop credentials and login access</p>
                </div>
            </div>
            <span class="text-xs font-mono text-gray-400">super_admin/shops_create</span>
        </div>

        <form action="{{ route('admin.shops.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Primary 5 Fields as per super_admin/shops_create Specification -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa fa-id-card text-blue-500"></i>
                    <span>Primary Shop & Credentials Information</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- 1. Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="name">
                            Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="e.g. Amar Bangla Fashion Outlet"
                               class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <!-- 2. Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="email">
                            Email <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               placeholder="e.g. shopowner@amarbangla.com.bd"
                               class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <!-- 5. Status -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="status">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <!-- 3. Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="password">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               placeholder="Set initial login password (min 4 chars)"
                               class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <!-- 4. Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1" for="con_password">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="con_password" id="con_password" required
                               placeholder="Re-type password to confirm"
                               class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <!-- Additional Details Accordion / Optional Section -->
            <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white">
                <button type="button" onclick="toggleExtraFields()" class="w-full p-4 bg-gray-50 hover:bg-gray-100 flex items-center justify-between text-xs font-bold text-gray-700 transition">
                    <span class="flex items-center gap-2">
                        <i class="fa fa-sliders text-blue-600"></i>
                        <span>Additional Details & Location (Mobile, Address, Category, Photos - Optional)</span>
                    </span>
                    <i class="fa fa-chevron-down text-gray-400 transition-transform duration-200" id="extra_icon"></i>
                </button>

                <div id="extra_fields" class="p-6 space-y-5 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1" for="mobile">Mobile Number</label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}" placeholder="017..."
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1" for="sale_commission">Sale Commission (%)</label>
                            <input type="number" step="0.01" name="sale_commission" id="sale_commission" value="{{ old('sale_commission', '5.00') }}"
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-emerald-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1" for="shop_type">Shop Type</label>
                            <select name="shop_type" id="shop_type"
                                    class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="regular">Regular Shop</option>
                                <option value="express">Amar Bangla Express Store</option>
                                <option value="local">Local Shop</option>
                                <option value="food">Amar Food</option>
                                <option value="grocery">Grocery</option>
                                <option value="fashion">Fashion & Apparel</option>
                            </select>
                        </div>
                    </div>

                    <!-- Geographic Area -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Division</label>
                            <input type="text" name="division" id="division" value="{{ old('division', 'Dhaka') }}"
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">District</label>
                            <input type="text" name="district" id="district" value="{{ old('district', 'Dhaka') }}"
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Upazila</label>
                            <input type="text" name="upazila" id="upazila" value="{{ old('upazila') }}" placeholder="Upazila..."
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Pourashava/Union</label>
                            <input type="text" name="pourashava" id="pourashava" value="{{ old('pourashava') }}" placeholder="Pourashava or Union..."
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Ward</label>
                            <input type="text" name="ward" id="ward" value="{{ old('ward') }}" placeholder="Ward number..."
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">House Address</label>
                            <input type="text" name="house_address" id="house_address" value="{{ old('house_address') }}" placeholder="House #, Road #..."
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>
                    </div>

                    <!-- Category & Subcategory -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Category</label>
                            <select name="category_id" id="category_id" onchange="loadSubcategories(this.value)"
                                    class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">SubCategory</label>
                            <select name="sub_category_id" id="sub_category_id"
                                    class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="">Select SubCategory</option>
                                @foreach($subcategories as $subCat)
                                    <option value="{{ $subCat->id }}" data-parent="{{ $subCat->parent_id }}">{{ $subCat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Photos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Logo</label>
                            <input type="file" name="logo_file" accept="image/*"
                                   class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <span class="text-[10px] text-gray-400 mt-1 block">Max. file size 1024KB and (width=350px) x (height=100px)</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">ProfileImage</label>
                            <input type="file" name="profile_image" accept="image/*"
                                   class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            <span class="text-[10px] text-gray-400 mt-1 block">Max. file size 1024KB and (width=160px) x (height=160px)</span>
                        </div>
                    </div>

                    <!-- Home feature -->
                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="home_feature" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-gray-700">Display as Featured Shop on Homepage</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.shops.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-plus"></i>
                    <span>Create Shop</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function toggleExtraFields() {
    const el = document.getElementById('extra_fields');
    const icon = document.getElementById('extra_icon');
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        el.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

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

    const selected = subCatSelect.selectedOptions[0];
    if (selected && selected.value && selected.style.display === 'none') {
        subCatSelect.value = '';
    }
}
</script>
@endsection
