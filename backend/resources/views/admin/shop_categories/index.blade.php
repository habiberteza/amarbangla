@extends('admin.layout')

@section('title', 'Shop Categories - Amar Bangla Super Admin')
@section('page-title', 'Shop Category Management (super_admin/shop_category)')

@section('content')
<div class="space-y-6">

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Shop Categories</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ \App\Models\ShopCategory::whereNull('deleted')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-store"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Show on Home Page</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ \App\Models\ShopCategory::whereNull('deleted')->where('show_home', '1')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-check-circle"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Main / Top-Level Categories</p>
                <h3 class="text-xl font-extrabold text-purple-600 mt-0.5">{{ \App\Models\ShopCategory::whereNull('deleted')->where(function($q){ $q->whereNull('parent_cat_id')->orWhere('parent_cat_id', 0); })->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                <i class="fa fa-layer-group"></i>
            </div>
        </div>
    </div>

    <!-- Actions & Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.shop_categories.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category by name, title or ID..."
                       class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>

            <select name="show_home" onchange="this.form.submit()" class="py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700">
                <option value="">All Home Visibility</option>
                <option value="1" {{ request('show_home') === '1' ? 'selected' : '' }}>Shown on Home</option>
                <option value="0" {{ request('show_home') === '0' ? 'selected' : '' }}>Hidden from Home</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-xs font-semibold transition">
                Filter
            </button>

            @if(request('search') || request('show_home') !== null)
                <a href="{{ route('admin.shop_categories.index') }}" class="text-xs text-gray-500 hover:text-rose-600 underline">Reset</a>
            @endif
        </form>

        <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 whitespace-nowrap">
            <i class="fa fa-plus"></i>
            <span>Add Shop Category</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4 w-14">Image</th>
                        <th class="py-3 px-4 w-16">ID</th>
                        <th class="py-3 px-4">Category Name</th>
                        <th class="py-3 px-4">Parent Category</th>
                        <th class="py-3 px-4">Bangla / Display Title</th>
                        <th class="py-3 px-4 text-center">Show on Home</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3 px-4">
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}"
                                 class="w-10 h-10 rounded-xl object-cover border border-gray-200 shadow-xs bg-gray-50"
                                 onerror="this.onerror=null; this.src='{{ asset('uploads/schools/no_image.jpg') }}';">
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-gray-500">
                            #{{ $cat->shop_cat_id }}
                        </td>
                        <td class="py-3 px-4 font-bold text-gray-900">
                            <a href="{{ route('admin.shop_categories.edit', $cat->shop_cat_id) }}" class="hover:text-blue-600">
                                {{ $cat->name }}
                            </a>
                        </td>
                        <td class="py-3 px-4 text-gray-600">
                            @if($cat->parent)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-[11px] font-semibold">
                                    <i class="fa fa-folder text-gray-400 text-[10px]"></i>
                                    {{ $cat->parent->name }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-[11px]">Main Category</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-semibold text-emerald-700">
                            {{ $cat->title ?: '—' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($cat->show_home == '1')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    ✓ Show on Home
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                    Hidden
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('admin.shop_categories.edit', $cat->shop_cat_id) }}"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition"
                               title="Edit Category (super_admin/shop_category_update/{{ $cat->shop_cat_id }})">
                                <i class="fa fa-pen text-[11px]"></i>
                            </a>
                            <form action="{{ route('admin.shop_categories.destroy', $cat->shop_cat_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this shop category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition" title="Delete Category">
                                    <i class="fa fa-trash-alt text-[11px]"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            No shop categories found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Add Modal -->
<div id="addCategoryModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-200 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <i class="fa fa-plus-circle text-blue-600"></i>
                <span>Add New Shop Category</span>
            </h3>
            <button onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.shop_categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Category Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. GROCERY, Fast Food, Meat & Fish"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Parent Category</label>
                <select name="parent_cat_id" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="0">None (Top Level / Main Category)</option>
                    @foreach($parentCategories as $pCat)
                        <option value="{{ $pCat->shop_cat_id }}">{{ $pCat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Bangla / Display Title</label>
                <input type="text" name="title" placeholder="e.g. গ্রোসারী শপ, কাঁচা বাজার, Fast Food"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Show on Home Page</label>
                <select name="show_home" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="0">No (Do not show on homepage)</option>
                    <option value="1">Yes (Show on homepage category grid)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Category Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full py-1.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <p class="text-[11px] text-gray-400 mt-1">Recommended size: 150x167 px. JPG, PNG or WEBP.</p>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
