@extends('admin.layout')

@section('title', 'Edit Group Post - Amar Bangla Super Admin')
@section('page-title', 'Edit Group Post: ' . $groupPost->title . ' (super_admin/group_post_update/' . $groupPost->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.group_posts.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Group Posts</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Post #{{ $groupPost->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-comments text-indigo-600"></i>
                <span>Edit Group Post: {{ $groupPost->title }}</span>
            </h2>
            <p class="text-xs text-gray-500">Manage post discussion content, categories, featured banners and attached video streams</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>group_post_update/{{ $groupPost->id }}</span>
            </span>
        </div>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
        <div class="font-bold flex items-center gap-2">
            <i class="fa fa-triangle-exclamation"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Post Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @php
                $img = $groupPost->image;
                $imgSrc = $img ? (str_starts_with($img, 'http') ? $img : asset($img)) : asset('uploads/no_image.jpg');
            @endphp
            <img src="{{ $imgSrc }}" alt="{{ $groupPost->title }}"
                 class="w-16 h-16 rounded-2xl object-cover border border-gray-200 shadow-xs"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($groupPost->title) }}&background=6366f1&color=fff'">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ $groupPost->title }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $groupPost->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ $groupPost->status }}
                    </span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                        {{ $groupPost->category }}
                    </span>
                </div>
                <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 font-mono">
                    <span><i class="fa fa-eye text-blue-500 mr-1"></i>{{ $groupPost->views_count ?? 0 }} Views</span>
                    <span>&bull;</span>
                    <span><i class="fa fa-heart text-rose-500 mr-1"></i>{{ $groupPost->likes_count ?? 0 }} Likes</span>
                    <span>&bull;</span>
                    <span>Created: {{ $groupPost->created_at ? $groupPost->created_at->format('d M, Y') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Post Content (#custom-tabs-four-home / #registertab)
         2. Media & Videos (#custom-tabs-four-messages / #media) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="groupPostTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchGroupPostTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="group-post-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-indigo-600 text-white shadow-sm">
            <i class="fa fa-align-left"></i>
            <span>Post Content</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchGroupPostTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="group-post-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-photo-film"></i>
            <span>Media & Videos</span>
        </a>
    </div>

    <form action="{{ route('admin.group_posts.update', $groupPost->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: Post Content (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="group-post-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-align-left text-indigo-600"></i>
                    <span>Post Details & Body</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Post headline title, topic category, publishing state, and editorial content</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Post Title / Subject <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $groupPost->title) }}" required
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="General" {{ old('category', $groupPost->category) == 'General' ? 'selected' : '' }}>General Discussion</option>
                            <option value="Announcements" {{ old('category', $groupPost->category) == 'Announcements' ? 'selected' : '' }}>Platform Announcements</option>
                            <option value="Seller Discussion" {{ old('category', $groupPost->category) == 'Seller Discussion' ? 'selected' : '' }}>Seller Community & Discussion</option>
                            <option value="Offers & Deals" {{ old('category', $groupPost->category) == 'Offers & Deals' ? 'selected' : '' }}>Offers, Promotions & Deals</option>
                            <option value="Tips & Advice" {{ old('category', $groupPost->category) == 'Tips & Advice' ? 'selected' : '' }}>Tips, Advice & Tutorials</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Publication Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="published" {{ $groupPost->status === 'published' ? 'selected' : '' }}>Published (Visible to all users)</option>
                            <option value="draft" {{ $groupPost->status === 'draft' ? 'selected' : '' }}>Draft (Admin preview only)</option>
                            <option value="archived" {{ $groupPost->status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Post Content & Discussion Body <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content" rows="7" required placeholder="Write post content, guidelines, markdown or announcements..."
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition leading-relaxed">{{ old('content', $groupPost->content) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.group_posts.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Post</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Media & Videos (#custom-tabs-four-messages) -->
        <div id="custom-tabs-four-messages" class="group-post-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-photo-film text-indigo-600"></i>
                    <span>Attached Media, Banners & Video Embeds</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Attach banner graphic, YouTube video link, or Facebook video embed</p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Current Featured Image Preview</label>
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center">
                        <img id="post_image_preview" src="{{ $imgSrc }}" alt="{{ $groupPost->title }}"
                             class="max-h-48 rounded-xl object-contain border border-gray-200 shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Upload New Image File</label>
                        <input type="file" name="image_file" accept="image/*" onchange="previewPostImage(this)"
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-200 rounded-xl p-1 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Or Image Web URL</label>
                        <input type="text" name="image" value="{{ old('image', $groupPost->image) }}" placeholder="https://example.com/image.jpg"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            <i class="fa-brands fa-youtube text-rose-600 mr-1"></i> YouTube Video Link / ID
                        </label>
                        <input type="text" name="youtube_video" value="{{ old('youtube_video', $groupPost->youtube_video ?? '') }}"
                               placeholder="e.g. https://www.youtube.com/watch?v=XXXX or video ID"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            <i class="fa-brands fa-facebook text-blue-600 mr-1"></i> Facebook Video URL / Embed
                        </label>
                        <input type="text" name="facebook_video" value="{{ old('facebook_video', $groupPost->facebook_video ?? '') }}"
                               placeholder="e.g. https://www.facebook.com/watch/?v=XXXX"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchGroupPostTab('custom-tabs-four-home')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Post Content
                </button>

                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Media & Post</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function switchGroupPostTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'content': 'custom-tabs-four-home',
        'custom-tabs-four-messages': 'custom-tabs-four-messages',
        'media': 'custom-tabs-four-messages',
        'video': 'custom-tabs-four-messages'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.group-post-tab-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.group-post-tab-btn').forEach(btn => {
        btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });

    const activeBtn = document.getElementById('btn-' + activeId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-indigo-600', 'text-white', 'shadow-sm');
    }

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchGroupPostTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchGroupPostTab(hash);
    }
});

function previewPostImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('post_image_preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
