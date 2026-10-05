@extends('admin.layout')

@section('title', 'Website Banners - Amar Bangla Super Admin')
@section('page-title', 'Desktop Website Banners (super_admin/website_settings/banner)')

@section('content')
<div class="space-y-6">

    <!-- Global Website Banner URLs -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Global Website Header & Top Banners</h3>
        <p class="text-xs text-gray-500 mb-6">These promotional banners appear at the top header and above product showcases on the desktop storefront.</p>

        <form action="{{ route('admin.website_settings.update') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Top Announcement Header Banner URL</label>
                    <input type="text" name="banner_desktop_header" value="{{ $settings['banner_desktop_header'] ?? '' }}" placeholder="https://example.com/top-header-strip.jpg"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Header Banner Target Link</label>
                    <input type="text" name="banner_desktop_header_link" value="{{ $settings['banner_desktop_header_link'] ?? '' }}" placeholder="/campaign/eid-sale"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Middle Homepage Full-Width Banner URL</label>
                    <input type="text" name="banner_desktop_middle" value="{{ $settings['banner_desktop_middle'] ?? '' }}" placeholder="https://example.com/middle-banner.jpg"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Middle Banner Target Link</label>
                    <input type="text" name="banner_desktop_middle_link" value="{{ $settings['banner_desktop_middle_link'] ?? '' }}" placeholder="/shop/bengal-grocery"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end pt-3 border-t border-gray-100">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow transition">
                    Save Banner URLs
                </button>
            </div>
        </form>
    </div>

    <!-- Additional Banner Items Grid -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Desktop Promotional Banner Cards</h3>
                <p class="text-xs text-gray-500">Banner cards shown across sections on desktop.</p>
            </div>
            <button onclick="document.getElementById('addBannerModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa fa-plus"></i> Add Card
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($banners as $b)
            <div class="border border-gray-200 rounded-xl overflow-hidden group">
                <div class="h-32 bg-gray-100 relative">
                    <img src="{{ $b->image_url }}" alt="{{ $b->title }}" class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-black/60 text-white">
                        {{ $b->position }}
                    </span>
                </div>
                <div class="p-3 flex items-center justify-between bg-white">
                    <div>
                        <h4 class="font-bold text-xs text-gray-800 line-clamp-1">{{ $b->title ?: 'Banner Card' }}</h4>
                        <p class="text-[11px] text-gray-400 truncate">{{ $b->link_url ?: '#' }}</p>
                    </div>
                    <form action="{{ route('admin.website_settings.destroy_banner', $b->id) }}" method="POST" onsubmit="return confirm('Remove this banner?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-50 text-gray-500 hover:text-rose-600 transition">
                            <i class="fa fa-trash-alt text-[10px]"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-8 text-center text-gray-400 text-xs">
                No custom desktop banner cards configured yet.
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Add Banner Modal -->
<div id="addBannerModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Add Desktop Banner Card</h3>
            <button onclick="document.getElementById('addBannerModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.website_settings.store_banner') }}" method="POST" class="space-y-3 mt-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Title</label>
                <input type="text" name="title" placeholder="e.g. 20% Off Weekend Deal"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Banner Image URL <span class="text-rose-500">*</span></label>
                <input type="text" name="image_url" required placeholder="https://example.com/banner.jpg"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Target Link URL</label>
                <input type="text" name="link_url" placeholder="/products?category=grocery"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Banner Position</label>
                <select name="position" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="home_banner_top">Home Banner Top</option>
                    <option value="home_banner_bottom">Home Banner Bottom</option>
                    <option value="home_banner_left">Home Banner Left</option>
                </select>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Display this banner</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addBannerModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                    Save Banner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
