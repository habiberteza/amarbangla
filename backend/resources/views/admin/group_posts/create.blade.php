@extends('admin.layout')

@section('title', 'Create Group Post - Amar Bangla Super Admin')
@section('page-title', 'Create Community Feed / Group Post (super_admin/group_post)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.group_posts.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Group Posts</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.group_posts.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Post Title / Subject <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. New Vendor Payout Policy updates for Q4"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('title') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Category <span class="text-rose-500">*</span></label>
                        <select name="category" required class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General</option>
                            <option value="Announcements" {{ old('category') == 'Announcements' ? 'selected' : '' }}>Announcements</option>
                            <option value="Seller Discussion" {{ old('category') == 'Seller Discussion' ? 'selected' : '' }}>Seller Discussion</option>
                            <option value="Offers & Deals" {{ old('category') == 'Offers & Deals' ? 'selected' : '' }}>Offers & Deals</option>
                            <option value="Tips & Advice" {{ old('category') == 'Tips & Advice' ? 'selected' : '' }}>Tips & Advice</option>
                        </select>
                        @error('category') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Featured Image URL</label>
                    <input type="text" name="image" value="{{ old('image') }}" placeholder="https://example.com/post-image.jpg"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Post Content & Discussion <span class="text-rose-500">*</span></label>
                    <textarea name="content" rows="6" required placeholder="Write details of the community group announcement or discussion topic..."
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('content') }}</textarea>
                    @error('content') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.group_posts.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-paper-plane"></i>
                    <span>Publish Post</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
