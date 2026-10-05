@extends('admin.layout')

@section('title', 'Website Logo & Branding - Amar Bangla Super Admin')
@section('page-title', 'Website Branding & Logos (super_admin/website_settings/logo)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-base font-bold text-gray-900">Brand Identity, Logo & Favicon</h3>
            <p class="text-xs text-gray-500 mt-1">Configure your storefront primary visual assets, logos and icons for desktop, mobile web, and apps.</p>
        </div>

        <form action="{{ route('admin.website_settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Primary Header Logo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="md:col-span-1 flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-gray-200 text-center">
                    <img src="{{ $settings['site_logo'] ?? 'https://superadmin.amarbangla.com.bd/uploads/logo.png' }}"
                         alt="Header Logo" class="max-h-16 object-contain mb-2">
                    <span class="text-[11px] font-semibold text-gray-400">Current Header Logo</span>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">Primary Header Logo URL <span class="text-rose-500">*</span></label>
                    <input type="text" name="site_logo" value="{{ $settings['site_logo'] ?? 'https://superadmin.amarbangla.com.bd/uploads/logo.png' }}" required
                           class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <p class="text-[11px] text-gray-500">Recommended dimension: 250x60px PNG with transparent background.</p>
                </div>
            </div>

            <!-- Dark / Footer Logo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="md:col-span-1 flex flex-col items-center justify-center p-4 bg-slate-900 rounded-xl border border-gray-800 text-center">
                    <img src="{{ $settings['dark_logo'] ?? ($settings['site_logo'] ?? 'https://superadmin.amarbangla.com.bd/uploads/logo.png') }}"
                         alt="Dark Logo" class="max-h-16 object-contain mb-2">
                    <span class="text-[11px] font-semibold text-slate-400">Current Dark Logo</span>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">Dark Background / Footer Logo URL</label>
                    <input type="text" name="dark_logo" value="{{ $settings['dark_logo'] ?? '' }}" placeholder="https://example.com/logo-white.png"
                           class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <p class="text-[11px] text-gray-500">Displayed on dark footers or night mode storefronts.</p>
                </div>
            </div>

            <!-- Favicon -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="md:col-span-1 flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-gray-200 text-center">
                    <img src="{{ $settings['site_favicon'] ?? 'https://superadmin.amarbangla.com.bd/uploads/favicon.png' }}"
                         alt="Favicon" class="w-10 h-10 object-contain mb-2">
                    <span class="text-[11px] font-semibold text-gray-400">Current Favicon</span>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">Browser Tab Favicon URL</label>
                    <input type="text" name="site_favicon" value="{{ $settings['site_favicon'] ?? '' }}" placeholder="https://example.com/favicon.ico"
                           class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <p class="text-[11px] text-gray-500">Recommended format: 64x64px or 32x32px .PNG or .ICO.</p>
                </div>
            </div>

            <!-- Admin Logo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center p-4 bg-gray-50 rounded-2xl border border-gray-200">
                <div class="md:col-span-1 flex flex-col items-center justify-center p-4 bg-slate-900 rounded-xl border border-gray-800 text-center">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow mb-2">
                        AB
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">Admin Brand Badge</span>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-xs font-bold text-gray-800">Admin Panel Logo / Icon URL</label>
                    <input type="text" name="admin_logo" value="{{ $settings['admin_logo'] ?? '' }}" placeholder="https://example.com/admin-badge.png"
                           class="w-full py-2.5 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <p class="text-[11px] text-gray-500">Displayed in the admin dashboard sidebar and login screen.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Brand Logos</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
