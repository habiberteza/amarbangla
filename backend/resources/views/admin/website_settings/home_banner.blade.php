@extends('admin.layout')

@section('title', 'Homepage Banners - Amar Bangla Super Admin')
@section('page-title', 'Homepage Grid & Promotional Banners (super_admin/website_settings/home_banner)')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Homepage Multi-Banner Grid & Deal Strips</h3>
                <p class="text-xs text-gray-500">Configure secondary promotion strips, category cards, and season deal banners on the homepage.</p>
            </div>
            <button onclick="document.getElementById('addHomeBannerModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow">
                <i class="fa fa-plus"></i> Add Promo Banner
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($banners as $b)
            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col">
                <div class="h-36 bg-gray-100 relative overflow-hidden">
                    <img src="{{ $b->image_url }}" alt="{{ $b->title }}" class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-600 text-white capitalize">
                        {{ str_replace('_', ' ', $b->position) }}
                    </span>
                </div>

                <div class="p-3.5 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-xs text-gray-800 line-clamp-1">{{ $b->title ?: 'Promo Banner' }}</h4>
                        @if($b->subtitle)
                            <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ $b->subtitle }}</p>
                        @endif
                        <p class="text-[11px] text-blue-600 truncate mt-1 font-mono">{{ $b->link_url ?: '#' }}</p>
                    </div>

                    <div class="flex items-center justify-end pt-2 mt-2 border-t border-gray-100">
                        <form action="{{ route('admin.website_settings.destroy_banner', $b->id) }}" method="POST" onsubmit="return confirm('Remove this banner?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-[11px] font-semibold transition flex items-center gap-1">
                                <i class="fa fa-trash-alt text-[9px]"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-10 text-center text-gray-400">
                <p class="text-xs font-semibold">No promotional banners configured yet.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Add Modal -->
<div id="addHomeBannerModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Add Homepage Promotion Banner</h3>
            <button onclick="document.getElementById('addHomeBannerModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.website_settings.store_banner') }}" method="POST" class="space-y-3 mt-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Banner Title</label>
                <input type="text" name="title" placeholder="e.g. Flash Deal: Fresh Fruits"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Subtitle / Tagline</label>
                <input type="text" name="subtitle" placeholder="e.g. Best organic harvest from Khulna"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Banner Image URL <span class="text-rose-500">*</span></label>
                <input type="text" name="image_url" required placeholder="https://example.com/promo-banner.jpg"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Link URL</label>
                    <input type="text" name="link_url" placeholder="/shop/grocery"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Position</label>
                    <select name="position" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="home_banner_top">Home Banner Top</option>
                        <option value="home_banner_left">Home Banner Left</option>
                        <option value="home_banner_bottom">Home Banner Bottom</option>
                        <option value="home_banner_side">Home Banner Side</option>
                    </select>
                </div>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Display this banner</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addHomeBannerModal').classList.add('hidden')"
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
