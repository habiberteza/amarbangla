@extends('admin.layout')

@section('title', 'Edit Shop Category - Amar Bangla Super Admin')
@section('page-title', 'Shops category update (super_admin/shop_category_update/' . $shopCategory->shop_cat_id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.shop_categories.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Shop Categories</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Category #{{ $shopCategory->shop_cat_id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-store text-emerald-600"></i>
                <span>Shops Category Update: {{ $shopCategory->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Manage category registration info, parent category, home visibility, Bengali title, and image</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>shop_category_update/{{ $shopCategory->shop_cat_id }}</span>
            </span>
        </div>
    </div>

    <!-- Feedback messages -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2">
        <i class="fa fa-check-circle text-emerald-600"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
        <div class="font-bold flex items-center gap-2">
            <i class="fa fa-triangle-exclamation"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Shop Category Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ $shopCategory->image_url }}" alt="{{ $shopCategory->name }}"
                 class="w-16 h-16 rounded-2xl object-cover border border-gray-200 shadow-xs bg-gray-50"
                 onerror="this.onerror=null; this.src='{{ asset('uploads/schools/no_image.jpg') }}';">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ $shopCategory->name }}</h3>
                    @if($shopCategory->show_home == '1')
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Show on Home
                        </span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase bg-gray-100 text-gray-600">
                            Hidden from Home
                        </span>
                    @endif
                </div>
                <p class="text-xs text-emerald-700 font-semibold mt-0.5">
                    Bangla Title: {{ $shopCategory->title ?: 'Not specified' }}
                </p>
                <p class="text-[11px] text-gray-400">
                    Parent: {{ $shopCategory->parent ? $shopCategory->parent->name : 'None (Top Level)' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Registration Info (#custom-tabs-four-home)
         2. Others (#custom-tabs-four-profile) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="shopCategoryTabsNav">
        <button type="button" onclick="switchShopCategoryTab('custom-tabs-four-home')" id="btn-custom-tabs-four-home"
           class="shop-cat-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-emerald-600 text-white shadow-sm">
            <i class="fa fa-id-card"></i>
            <span>Registration Info</span>
        </button>

        <button type="button" onclick="switchShopCategoryTab('custom-tabs-four-profile')" id="btn-custom-tabs-four-profile"
           class="shop-cat-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-sliders"></i>
            <span>Others (Home & Image)</span>
        </button>
    </div>

    <!-- TAB 1: Registration Info (Category Name, Parent Category) -->
    <div id="custom-tabs-four-home" class="shop-cat-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
        <div class="border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <i class="fa fa-store text-emerald-600"></i>
                <span>Category Registration Details</span>
            </h3>
            <p class="text-xs text-gray-400 mt-0.5">Set category name and parent hierarchy</p>
        </div>

        <form action="{{ route('admin.shop_categories.update', $shopCategory->shop_cat_id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="shop_cat_id" value="{{ $shopCategory->shop_cat_id }}">
            <input type="hidden" name="show_home" value="{{ $shopCategory->show_home }}">
            <input type="hidden" name="title" value="{{ $shopCategory->title }}">

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                    Category Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $shopCategory->name) }}" required
                       placeholder="Shop category name"
                       class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                    Parent Category
                </label>
                <select name="parent_cat_id" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <option value="0">Please Select (Main Category / No Parent)</option>
                    @foreach($parentCategories as $pCat)
                        <option value="{{ $pCat->shop_cat_id }}" {{ (string)$shopCategory->parent_cat_id === (string)$pCat->shop_cat_id ? 'selected' : '' }}>
                            {{ $pCat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                    Update Registration Info
                </button>
                <a href="{{ route('admin.shop_categories.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- TAB 2: Others (Show Home, Bangla Title, Image Upload) -->
    <div id="custom-tabs-four-profile" class="shop-cat-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
        <div class="border-b border-gray-100 pb-3">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <i class="fa fa-sliders text-emerald-600"></i>
                <span>Others: Home Display & Visual Banner</span>
            </h3>
            <p class="text-xs text-gray-400 mt-0.5">Control homepage visibility, Bengali title for mobile app, and category icon image</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
            <div class="md:col-span-7">
                <form action="{{ route('admin.shop_categories.update', $shopCategory->shop_cat_id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="shop_cat_id" value="{{ $shopCategory->shop_cat_id }}">
                    <input type="hidden" name="name" value="{{ $shopCategory->name }}">
                    <input type="hidden" name="parent_cat_id" value="{{ $shopCategory->parent_cat_id }}">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Show Home <span class="text-rose-500">*</span>
                        </label>
                        <select name="show_home" required class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="1" {{ $shopCategory->show_home == '1' ? 'selected' : '' }}>Yes (Show on Home Page)</option>
                            <option value="0" {{ $shopCategory->show_home == '0' ? 'selected' : '' }}>No (Hide from Home Page)</option>
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">If enabled, this shop category will appear in the homepage mobile app section.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Title (Bangla / Display Title) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $shopCategory->title) }}" required
                               placeholder="e.g. গ্রোসারী শপ, কাঁচা বাজার, লন্ডি সার্ভিস, Fast Food"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Category Image (uploads/schools/)
                        </label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <p class="text-[11px] text-gray-400 mt-1">Leave empty to keep existing image. Recommended size: 150x167 px.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                            Update Others Info
                        </button>
                        <a href="{{ route('admin.shop_categories.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Current Image Preview -->
            <div class="md:col-span-5 bg-gray-50 p-5 rounded-2xl border border-gray-200 text-center space-y-3">
                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider block">Current Image Preview</span>
                <div class="inline-block p-2 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <img src="{{ $shopCategory->image_url }}" alt="{{ $shopCategory->name }}"
                         class="w-36 h-40 object-cover rounded-xl mx-auto"
                         onerror="this.onerror=null; this.src='{{ asset('uploads/schools/no_image.jpg') }}';">
                </div>
                <div class="text-[11px] text-gray-500 font-mono truncate">
                    File: {{ $shopCategory->image ?: 'no_image.jpg' }}
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function switchShopCategoryTab(tabId) {
    document.querySelectorAll('.shop-cat-tab-pane').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.shop-cat-tab-btn').forEach(btn => {
        btn.classList.remove('bg-emerald-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600');
    });

    const activePane = document.getElementById(tabId);
    const activeBtn = document.getElementById('btn-' + tabId);

    if (activePane) activePane.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.add('bg-emerald-600', 'text-white', 'shadow-sm');
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
    }
}

// Check url param for active tab
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('active') === 'personal' || window.location.hash === '#others') {
    switchShopCategoryTab('custom-tabs-four-profile');
}
</script>
@endsection
