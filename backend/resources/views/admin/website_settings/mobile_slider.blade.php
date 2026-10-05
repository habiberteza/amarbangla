@extends('admin.layout')

@section('title', 'Mobile App Slider - Amar Bangla Super Admin')
@section('page-title', 'Mobile App & Mobile Web Slider (super_admin/website_settings/mobile_slider)')

@section('content')
<div class="space-y-6">

    <!-- Mobile Sliders Management -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Mobile App & Responsive Touch Sliders</h3>
                <p class="text-xs text-gray-500">Carousel banners optimized for smartphone portrait view (Android, iOS & Mobile PWA browsers).</p>
            </div>
            <button onclick="document.getElementById('addMobileSlideModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow">
                <i class="fa fa-plus"></i> Add Mobile Slide
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($sliders as $slide)
            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm bg-white flex flex-col">
                <div class="h-44 bg-gray-100 relative overflow-hidden">
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] font-bold bg-black/60 text-white">
                        <i class="fa fa-mobile-screen mr-1"></i> Mobile #{{ $slide->sort_order }}
                    </span>
                </div>

                <div class="p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-xs text-gray-800 line-clamp-1">{{ $slide->title ?: 'Mobile Slide' }}</h4>
                        <p class="text-[11px] text-gray-400 truncate mt-0.5">{{ $slide->link_url ?: '#' }}</p>
                    </div>

                    <div class="flex items-center justify-end pt-2 mt-2 border-t border-gray-100">
                        <form action="{{ route('admin.website_settings.destroy_banner', $slide->id) }}" method="POST" onsubmit="return confirm('Remove this mobile slide?');">
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
                <i class="fa fa-mobile-screen text-3xl mb-2 text-gray-300"></i>
                <p class="text-xs font-semibold">No mobile slides created yet.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Add Modal -->
<div id="addMobileSlideModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Add Mobile Touch Slide</h3>
            <button onclick="document.getElementById('addMobileSlideModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.website_settings.store_banner') }}" method="POST" class="space-y-3 mt-4">
            @csrf
            <input type="hidden" name="position" value="hero_slider">

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Slide Title</label>
                <input type="text" name="title" placeholder="e.g. Free Delivery Friday"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Mobile Image URL <span class="text-rose-500">*</span></label>
                <input type="text" name="image_url" required placeholder="https://example.com/mobile-banner.jpg"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <p class="text-[10px] text-gray-400 mt-0.5">Recommended resolution: 800x450px or 1080x600px</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deep Link / URL</label>
                    <input type="text" name="link_url" placeholder="/category/fashion"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="1" min="1"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Display this mobile slide</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('addMobileSlideModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                    Save Mobile Slide
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
