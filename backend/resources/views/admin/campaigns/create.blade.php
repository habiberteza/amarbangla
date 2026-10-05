@extends('admin.layout')

@section('title', 'Create Campaign - Amar Bangla Super Admin')
@section('page-title', 'Create Promotional Campaign (super_admin/campaign)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.campaigns.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Campaigns</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.campaigns.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Campaign Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Eid Mega Sale 2026, Boishakhi Dhamaka"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('title') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Subtitle / Tagline</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Save up to 50% on all electronics and fashion"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Max Discount Percentage (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="discount_percentage" value="{{ old('discount_percentage', '20') }}"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="active">Active</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="expired">Expired</option>
                            <option value="disabled">Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Banner Image URL</label>
                    <input type="text" name="banner_image" value="{{ old('banner_image') }}" placeholder="https://example.com/banner.jpg"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Description / Campaign Terms</label>
                    <textarea name="description" rows="3" placeholder="Enter terms and promotional rules..."
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-semibold text-gray-700">Display prominently on App / Web Homepage banner carousel</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.campaigns.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Create Campaign</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
