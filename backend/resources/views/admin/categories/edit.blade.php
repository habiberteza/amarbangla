@extends('admin.layout')

@section('title', 'Edit Product Category - Amar Bangla Super Admin')
@section('page-title', 'Edit Category: ' . $category->name . ' (super_admin/demo_category_update/' . $category->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Categories</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Category #{{ $category->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-layer-group text-blue-600"></i>
                <span>Edit Product Category: {{ $category->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Configure catalog hierarchy, parent associations, category banners and storefront status</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>demo_category_update/{{ $category->id }}</span>
            </span>
        </div>
    </div>

    <!-- Error Alerts -->
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

    <!-- Category Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @php
                $img = $category->image;
                $imgSrc = $img ? (str_starts_with($img, 'http') ? $img : asset($img)) : asset('uploads/no_image.jpg');
            @endphp
            <img src="{{ $imgSrc }}" alt="{{ $category->name }}"
                 class="w-14 h-14 rounded-2xl object-cover border border-gray-200 shadow-xs"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&background=2563eb&color=fff'">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ $category->name }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($category->is_featured)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800 flex items-center gap-1">
                            <i class="fa fa-star text-[9px]"></i> Featured
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5">
                    Parent: <strong class="text-gray-700">{{ $category->parent ? $category->parent->name : 'None (Top-Level)' }}</strong>
                    &bull; Products: <strong class="text-blue-600">{{ $category->products_count ?? $category->products()->count() }}</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Registraion Info (#custom-tabs-four-home / #registertab)
         2. Media & Icon (#custom-tabs-four-messages / #image) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="categoryTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchCategoryTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="category-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-id-card"></i>
            <span>Registraion Info</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchCategoryTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="category-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-image"></i>
            <span>Media & Icon</span>
        </a>
    </div>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: Registraion Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="category-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-layer-group text-blue-600"></i>
                    <span>Category Hierarchy & Information</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Specify category naming and parent relationship</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Parent Category (Sub-Category of)
                    </label>
                    <select name="parent_id" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">None (Top-Level Category)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Sort Order Index
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ $category->is_featured ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span class="text-xs font-bold text-gray-700">Display as Featured Category</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span class="text-xs font-bold text-gray-700">Active (Visible on Store)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Category</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Media & Icon (#custom-tabs-four-messages) -->
        <div id="custom-tabs-four-messages" class="category-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-image text-indigo-600"></i>
                    <span>Category Graphic Asset & Icon</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Attach banner/thumbnail image and icon code for navigation menu</p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Current Category Image Preview</label>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center">
                        <img id="cat_image_preview" src="{{ $imgSrc }}" alt="{{ $category->name }}"
                             class="max-h-40 rounded-xl object-contain border border-gray-200 shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Upload New Image File</label>
                        <input type="file" name="image_file" accept="image/*" onchange="previewCatImage(this)"
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-200 rounded-xl p-1 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Or Image Web URL</label>
                        <input type="text" name="image" value="{{ old('image', $category->image) }}" placeholder="https://..."
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Icon Name (FontAwesome / Lucide)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" placeholder="e.g. utensils, mobile, shirt"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchCategoryTab('custom-tabs-four-home')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Registration Info
                </button>

                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Media & Category</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function switchCategoryTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'info': 'custom-tabs-four-home',
        'custom-tabs-four-messages': 'custom-tabs-four-messages',
        'image': 'custom-tabs-four-messages',
        'media': 'custom-tabs-four-messages'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.category-tab-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.category-tab-btn').forEach(btn => {
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
        switchCategoryTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchCategoryTab(hash);
    }
});

function previewCatImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cat_image_preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
