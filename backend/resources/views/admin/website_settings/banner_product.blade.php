@extends('admin.layout')

@section('title', 'Product Page Banners - Amar Bangla Super Admin')
@section('page-title', 'Product Page & Category Banners (super_admin/website_settings/banner_product)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-base font-bold text-gray-900">Product Catalog & Product Details Banners</h3>
            <p class="text-xs text-gray-500 mt-1">Configure advertisement strips and promotional cards shown on single product detail pages and catalog search grids.</p>
        </div>

        <form action="{{ route('admin.website_settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Product Details Sidebar Banner -->
            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200 space-y-4">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Product Page Sidebar Sticky Banner</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Banner Image URL</label>
                        <input type="text" name="banner_product_sidebar" value="{{ $settings['banner_product_sidebar'] ?? '' }}" placeholder="https://example.com/product-sidebar.jpg"
                               class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Link URL</label>
                        <input type="text" name="banner_product_sidebar_link" value="{{ $settings['banner_product_sidebar_link'] ?? '' }}" placeholder="/campaign/flash-sale"
                               class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Product Page Header / Breadcrumb Strip -->
            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-200 space-y-4">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Catalog Header Promotional Strip</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Header Strip Banner URL</label>
                        <input type="text" name="banner_product_top" value="{{ $settings['banner_product_top'] ?? '' }}" placeholder="https://example.com/catalog-header.jpg"
                               class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Link URL</label>
                        <input type="text" name="banner_product_top_link" value="{{ $settings['banner_product_top_link'] ?? '' }}" placeholder="/products?deals=true"
                               class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end pt-3 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Product Page Banners</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
