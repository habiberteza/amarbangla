@extends('admin.layout')

@section('title', 'Product Catalog & Inventory (Demo Product List) - Amar Bangla Super Admin')
@section('page-title', 'Product Catalog & Inventory (super_admin/products & demo_product_list)')

@section('content')
<div class="space-y-6">

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

    <!-- Top KPI Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Products</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ number_format($totalProducts) }}</h3>
                <span class="text-[10px] text-gray-500 font-medium">Master Catalog Items</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-boxes-stacked"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Active Status</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ number_format($activeProducts) }}</h3>
                <span class="text-[10px] text-emerald-600 font-medium">Live & Sellable</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Out of Stock</p>
                <h3 class="text-xl font-extrabold {{ $outOfStock > 0 ? 'text-rose-600' : 'text-gray-700' }} mt-0.5">
                    {{ number_format($outOfStock) }}
                </h3>
                <span class="text-[10px] {{ $outOfStock > 0 ? 'text-rose-500' : 'text-gray-500' }} font-medium">Needs Replenishment</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                <i class="fa fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-wider text-gray-400 uppercase">Total Inventory Value</p>
                <h3 class="text-xl font-extrabold text-indigo-700 mt-0.5">
                    ৳ {{ number_format($totalInventoryValue, 0) }}
                </h3>
                <span class="text-[10px] text-indigo-600 font-medium">Asset Valuation</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                <i class="fa fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                    <i class="fa fa-filter"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Product Search & Catalog Filters</h4>
                    <p class="text-[11px] text-gray-500">Filter by Shop, Category, Status, Express Delivery, or Free Keywords</p>
                </div>
            </div>

            <!-- Export Buttons: CSV, xl, pdf, print + Add New Product -->
            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex rounded-xl shadow-sm border border-gray-200 overflow-hidden bg-gray-50 p-0.5 text-xs font-bold">
                    <a href="{{ route('admin.products.export', array_merge(request()->query(), ['format' => 'csv'])) }}"
                       title="Export filtered products as CSV"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-blue-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-csv text-emerald-600"></i>
                        <span>CSV</span>
                    </a>
                    <a href="{{ route('admin.products.export', array_merge(request()->query(), ['format' => 'excel'])) }}"
                       title="Export filtered products as Excel (xl)"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-emerald-700 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-excel text-emerald-600"></i>
                        <span>xl</span>
                    </a>
                    <a href="{{ route('admin.products.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                       target="_blank"
                       title="Export printable PDF format"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-rose-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-file-pdf text-rose-500"></i>
                        <span>pdf</span>
                    </a>
                    <a href="{{ route('admin.products.export', array_merge(request()->query(), ['format' => 'print'])) }}"
                       target="_blank"
                       title="Direct Print Layout"
                       class="px-2.5 py-1.5 hover:bg-white text-gray-700 hover:text-indigo-600 rounded-lg transition flex items-center gap-1.5">
                        <i class="fa fa-print text-indigo-600"></i>
                        <span>print</span>
                    </a>
                </div>

                <a href="{{ route('admin.products.create') }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                    <i class="fa fa-plus"></i>
                    <span>Add New Product</span>
                </a>
            </div>
        </div>

        <form id="productFilterForm" method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- 1. Search Query -->
                <div class="relative">
                    <input type="text" name="search" id="product_search" value="{{ request('search') }}"
                           placeholder="Search by name, SKU/item code, brand..."
                           class="w-full pl-9 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
                    @if(request('search'))
                        <button type="button" onclick="document.getElementById('product_search').value=''; document.getElementById('productFilterForm').submit();" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-rose-500 text-xs">
                            <i class="fa fa-times"></i>
                        </button>
                    @endif
                </div>

                <!-- 2. Shop Dropdown -->
                <div>
                    <select name="shop_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Shops & Outlets</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                {{ $shop->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Category Dropdown -->
                <div>
                    <select name="category_id" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Product Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 4. Status Dropdown -->
                <div>
                    <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    </select>
                </div>
            </div>

            <!-- Submit & Reset Actions -->
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                    Found <strong class="text-gray-800">{{ $products->total() }}</strong> products matching criteria
                </span>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['search', 'shop_id', 'category_id', 'status', 'is_express', 'is_featured']))
                        <a href="{{ route('admin.products.index') }}"
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa fa-rotate-left"></i>
                            <span>Reset Filters</span>
                        </a>
                    @endif
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                        <i class="fa fa-check"></i>
                        <span>Apply Filters</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3.5 w-14 text-center">No</th>
                        <th class="px-4 py-3.5 text-center w-16">Image</th>
                        <th class="px-5 py-3.5">Product Title & SKU</th>
                        <th class="px-5 py-3.5">Shop / Outlet</th>
                        <th class="px-5 py-3.5">Category</th>
                        <th class="px-5 py-3.5">Price</th>
                        <th class="px-5 py-3.5 text-center">Stock</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/80 transition">
                        <!-- 1. No -->
                        <td class="px-4 py-3.5 text-center font-bold text-gray-500 font-mono">
                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                        </td>

                        <!-- 2. Image -->
                        <td class="px-4 py-3.5 text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl overflow-hidden bg-gray-50 border border-gray-200 flex items-center justify-center p-0.5 shadow-xs">
                                <img src="{{ $product->main_image ?? 'https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg' }}"
                                     alt="{{ $product->name }}"
                                     onerror="this.onerror=null; this.src='https://amarbangla.com.bd/uploads/website_image/logo_1625893869.jpg';"
                                     class="w-full h-full object-cover rounded-lg">
                            </div>
                        </td>

                        <!-- 3. Product Title & SKU -->
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('admin.products.edit', $product) }}" class="font-extrabold text-gray-900 hover:text-blue-600 text-sm transition">
                                    {{ $product->name }}
                                </a>
                                @if($product->is_featured)
                                    <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-1.5 py-0.5 rounded shadow-xs" title="Featured Product">
                                        <i class="fa fa-star text-[9px] mr-0.5"></i> Featured
                                    </span>
                                @endif
                                @if($product->is_express)
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-extrabold px-1.5 py-0.5 rounded shadow-xs">
                                        <i class="fa fa-bolt text-[9px] mr-0.5"></i> Express
                                    </span>
                                @endif
                            </div>
                            <div class="text-[11px] text-gray-500 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span>Code: <strong class="font-mono text-gray-700">{{ $product->item_code ?? 'N/A' }}</strong></span>
                                @if($product->brand)
                                    <span>Brand: <strong class="text-gray-700">{{ $product->brand }}</strong></span>
                                @endif
                            </div>
                        </td>

                        <!-- 4. Shop / Outlet -->
                        <td class="px-5 py-3.5 font-medium text-gray-800">
                            <div class="flex items-center gap-1.5">
                                <i class="fa fa-store text-blue-500 text-xs"></i>
                                <span>{{ $product->shop->name ?? 'Amar Bangla Store' }}</span>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-0.5">
                                {{ $product->shop->district ?? '' }} {{ $product->shop->division ? '(' . $product->shop->division . ')' : '' }}
                            </div>
                        </td>

                        <!-- 5. Category -->
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">
                                {{ $product->category->name ?? 'General' }}
                            </span>
                        </td>

                        <!-- 6. Price -->
                        <td class="px-5 py-3.5">
                            @if($product->discount_price)
                                <div class="font-extrabold text-emerald-600 text-xs">৳ {{ number_format($product->discount_price, 2) }}</div>
                                <div class="text-[10px] text-gray-400 line-through">৳ {{ number_format($product->price, 2) }}</div>
                            @else
                                <div class="font-extrabold text-gray-900 text-xs">৳ {{ number_format($product->price, 2) }}</div>
                            @endif
                        </td>

                        <!-- 7. Stock -->
                        <td class="px-5 py-3.5 text-center">
                            @if($product->stock_quantity > 10)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa fa-check text-[9px]"></i> {{ $product->stock_quantity }}
                                </span>
                            @elseif($product->stock_quantity > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class="fa fa-clock text-[9px]"></i> {{ $product->stock_quantity }} left
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="fa fa-times text-[9px]"></i> Out of Stock
                                </span>
                            @endif
                        </td>

                        <!-- 8. Status Toggle -->
                        <td class="px-5 py-3.5 text-center">
                            <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        title="Click to toggle active/inactive"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition {{ $product->is_active ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-emerald-600 animate-pulse' : 'bg-gray-400' }}"></span>
                                    <span>{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                                </button>
                            </form>
                        </td>

                        <!-- 9. Actions -->
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition"
                                   title="Edit Product Details">
                                    <i class="fa fa-pen-to-square text-xs"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete &quot;{{ addslashes($product->name) }}&quot;?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition"
                                            title="Delete Product">
                                        <i class="fa fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center text-gray-400">
                            <div class="w-12 h-12 mx-auto rounded-full bg-gray-50 flex items-center justify-center text-gray-400 mb-2">
                                <i class="fa fa-boxes-stacked text-xl"></i>
                            </div>
                            <p class="font-medium text-xs">No products found matching criteria.</p>
                            @if(request()->hasAny(['search', 'shop_id', 'category_id', 'status']))
                                <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline text-xs mt-1 inline-block">
                                    Clear all filters
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ number_format($products->total()) }} items
            </span>
            <div>
                {{ $products->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
