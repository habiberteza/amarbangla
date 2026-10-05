@extends('admin.layout')

@section('title', 'Add Product - Amar Bangla Super Admin')
@section('page-title', 'Create New Product')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-base">Product Information</h2>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                &larr; Back to Products
            </a>
        </div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Product Title *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Men's T-shirt white amar bangla"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Assign to Shop / Outlet *</label>
                    <select name="shop_id" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}">{{ $shop->name }} ({{ ucfirst($shop->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">Uncategorized</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Item Code / SKU</label>
                    <input type="text" name="item_code" value="{{ old('item_code') }}" placeholder="e.g. 55220"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Brand Name</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="e.g. Winner fashions"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Regular Price (৳) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Discount / Offer Price (৳)</label>
                    <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 50) }}" required
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Main Image URL</label>
                    <input type="text" name="main_image" value="{{ old('main_image') }}" placeholder="https://..."
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Fabrication</label>
                    <input type="text" name="fabrication" value="{{ old('fabrication') }}" placeholder="e.g. 95% cotton 5% elastic s/j"
                           class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">GSM & Gender</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="gsm" value="{{ old('gsm') }}" placeholder="GSM (160-170)"
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <select name="gender" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="">Any</option>
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                            <option value="Kids">Kids</option>
                            <option value="Unisex">Unisex</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Short Summary</label>
                <textarea name="short_description" rows="2" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('short_description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Full Description & Specifications</label>
                <textarea name="description" rows="4" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('description') }}</textarea>
            </div>

            <div class="flex flex-wrap items-center gap-6 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_express" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Eligible for Express 2h Delivery</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Feature on Homepage</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Active (Visible for purchase)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow transition">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
