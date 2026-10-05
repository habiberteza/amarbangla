@extends('admin.layout')

@section('title', 'Update Product - ' . $product->name . ' - Amar Bangla Super Admin')
@section('page-title', 'Product Edit (super_admin/demo_products/' . $product->id . '/edit)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Products</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Product #{{ $product->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-box-open text-blue-600"></i>
                <span>Product Edit: {{ $product->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Update general merchandise specifications, inventory pricing, and product photos</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.create') }}" class="px-3.5 py-1.5 bg-blue-600 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm hover:bg-blue-700">
                <i class="fa fa-plus text-[10px]"></i>
                <span>+ Add Product</span>
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-800 border border-blue-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>demo_products/{{ $product->id }}</span>
            </span>
        </div>
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

    <!-- Product Summary Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-gray-100 shadow-sm"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=2563eb&color=fff'">
            <div>
                <h3 class="text-base font-black text-gray-900">{{ $product->name }}</h3>
                <p class="text-xs text-gray-500 font-mono">SKU: {{ $product->item_code ?: 'N/A' }} &bull; Shop: {{ $product->shop ? $product->shop->name : 'N/A' }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($product->category)
                        <span class="text-[10px] font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">
                            <i class="fa fa-folder mr-1"></i>{{ $product->category->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-right">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Regular Price</span>
            <span class="text-base font-extrabold font-mono text-blue-700">
                ৳{{ number_format($product->price, 2) }}
            </span>
        </div>
    </div>

    <!-- Navigation Tabs:
         1. General Info (#custom-tabs-four-home)
         2. Photo (#custom-tabs-four-messages) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="productTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchProductTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="prod-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-info-circle"></i>
            <span>General Info</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchProductTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="prod-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-image"></i>
            <span>Photo</span>
        </a>
    </div>

    <!-- Unified Form with Tab Panes -->
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: General Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="prod-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-info-circle text-blue-600"></i>
                        <span>General Information</span>
                    </h3>
                    <p class="text-xs text-gray-400">Specifications, pricing, inventory stock, and associated merchant shop</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">demo_products/{{ $product->id }}#custom-tabs-four-home</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Product Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Assign to Shop / Outlet <span class="text-rose-500">*</span></label>
                    <select name="shop_id" required
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ old('shop_id', $product->shop_id) == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }} ({{ ucfirst($shop->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Category</label>
                    <select name="category_id"
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">Uncategorized</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Item Code / SKU</label>
                    <input type="text" name="item_code" value="{{ old('item_code', $product->item_code) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Brand Name</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Regular Price (৳) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Discount / Offer Price (৳)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Stock Quantity <span class="text-rose-500">*</span></label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div class="flex items-center gap-4 pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>Active for Sale</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                        <span>Featured Product</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700">
                        <input type="checkbox" name="is_express" value="1" {{ old('is_express', $product->is_express) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Express Delivery</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full p-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update Product</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Photo (#custom-tabs-four-messages) -->
        <div id="custom-tabs-four-messages" class="prod-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3 mb-2 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa fa-image text-blue-600"></i>
                        <span>Product Photo / Gallery</span>
                    </h3>
                    <p class="text-xs text-gray-400">Primary display image and image asset uploader</p>
                </div>
                <span class="text-[11px] font-mono text-gray-400">demo_products/{{ $product->id }}#custom-tabs-four-messages</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <!-- Preview -->
                <div class="flex flex-col items-center justify-center p-4 bg-gray-50 border border-gray-200 rounded-2xl text-center">
                    <img src="{{ $product->primary_image_url }}" id="productPhotoPreview" class="w-48 h-48 rounded-2xl object-cover shadow-md border-2 border-white" alt="{{ $product->name }}"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=2563eb&color=fff'">
                    <span class="text-[11px] text-gray-500 mt-2 font-mono">{{ basename($product->main_image ?: 'default_image') }}</span>
                </div>

                <!-- Input -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Upload New Photo</label>
                        <input type="file" name="photo" id="photo" accept="image/*" onchange="previewProductPhoto(this)"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-800 hover:file:bg-blue-200 cursor-pointer focus:outline-none">
                        <p class="text-[11px] text-gray-400 mt-1">Accepts PNG, JPG, JPEG up to 3MB</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Or Image URL</label>
                        <input type="text" name="main_image" value="{{ old('main_image', $product->main_image) }}" placeholder="https://example.com/image.jpg"
                               class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5">
                    <i class="fa fa-save"></i>
                    <span>Update Product</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    function previewProductPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('productPhotoPreview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function switchProductTab(targetTabId) {
        const tabMap = {
            'custom-tabs-four-home': 'custom-tabs-four-home',
            'registertab': 'custom-tabs-four-home',
            'tab-general': 'custom-tabs-four-home',
            'custom-tabs-four-messages': 'custom-tabs-four-messages',
            'photo': 'custom-tabs-four-messages',
            'tab-photo': 'custom-tabs-four-messages'
        };

        const activeId = tabMap[targetTabId] || 'custom-tabs-four-home';

        const panes = document.querySelectorAll('.prod-tab-pane');
        panes.forEach(pane => {
            if (pane.id === activeId) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        });

        const tabButtons = [
            { id: 'btn-custom-tabs-four-home', tab: 'custom-tabs-four-home' },
            { id: 'btn-custom-tabs-four-messages', tab: 'custom-tabs-four-messages' }
        ];

        tabButtons.forEach(btnInfo => {
            const btn = document.getElementById(btnInfo.id);
            if (!btn) return;
            if (btnInfo.tab === activeId) {
                btn.className = 'prod-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm';
            } else {
                btn.className = 'prod-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200';
            }
        });

        if (history.replaceState) {
            history.replaceState(null, null, '#' + activeId);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const hash = window.location.hash ? window.location.hash.replace('#', '') : '';
        if (hash) {
            switchProductTab(hash);
        } else {
            switchProductTab('custom-tabs-four-home');
        }
    });
</script>
@endsection
