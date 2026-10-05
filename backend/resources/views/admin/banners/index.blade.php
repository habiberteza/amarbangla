@extends('admin.layout')

@section('title', 'Sliders & Banners - Amar Bangla Super Admin')
@section('page-title', 'Homepage Sliders & Promotional Banners')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Banner Form -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-bold text-gray-800 text-sm mb-4">Add Slider or Banner</h2>
        <form action="{{ route('admin.banners.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Position / Placement *</label>
                <select name="position" required class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="hero_slider">Hero Main Carousel Slider</option>
                    <option value="home_banner_left">Home Left Featured Banner</option>
                    <option value="home_banner_top">Home Right Top Banner</option>
                    <option value="home_banner_bottom">Home Right Bottom Banner</option>
                    <option value="home_banner_side">Home Side Banner</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Image URL *</label>
                <input type="text" name="image_url" required placeholder="https://..."
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Headline Title</label>
                <input type="text" name="title" placeholder="Special Campaign Offer"
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Subtitle</label>
                <input type="text" name="subtitle" placeholder="Up to 40% Off"
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Target Link URL</label>
                <input type="text" name="link_url" placeholder="/shops?campaign=1"
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div class="flex items-center gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Order</label>
                    <input type="number" name="sort_order" value="1" class="w-20 px-3 py-1.5 bg-gray-50 border border-gray-300 rounded-lg text-xs">
                </div>
                <label class="flex items-center gap-2 cursor-pointer pt-4">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-medium text-gray-700">Active</span>
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow transition">
                Add Slide / Banner
            </button>
        </form>
    </div>

    <!-- Active Banners List -->
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800 text-sm">Active Banners & Sliders ({{ $banners->count() }})</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($banners as $banner)
                <div class="border border-gray-200 rounded-lg overflow-hidden flex flex-col group bg-gray-50">
                    <div class="h-32 bg-gray-200 relative overflow-hidden">
                        <img src="{{ $banner->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" alt="">
                        <span class="absolute top-2 left-2 bg-black/70 text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                            {{ str_replace('_', ' ', $banner->position) }}
                        </span>
                    </div>
                    <div class="p-3 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-gray-800 text-xs">{{ $banner->title ?? 'Untitled Banner' }}</h4>
                            <p class="text-[11px] text-gray-500">{{ $banner->subtitle ?? 'No subtitle' }}</p>
                        </div>
                        <div class="mt-3 pt-2 border-t border-gray-200 flex items-center justify-between">
                            <span class="text-[10px] font-mono text-blue-600 truncate max-w-[140px]">{{ $banner->link_url ?? '#' }}</span>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Remove this banner?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs p-1">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
