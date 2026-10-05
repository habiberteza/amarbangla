@extends('admin.layout')

@section('title', 'Community Group Posts - Amar Bangla Super Admin')
@section('page-title', 'Community Feed & Group Posts (super_admin/group_post)')

@section('content')
<div class="space-y-6">

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Posts</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ \App\Models\GroupPost::count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-users-rectangle"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Published</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ \App\Models\GroupPost::where('status', 'published')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-check-double"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Views</p>
                <h3 class="text-xl font-extrabold text-purple-600 mt-0.5">{{ number_format(\App\Models\GroupPost::sum('views_count')) }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                <i class="fa fa-eye"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Likes</p>
                <h3 class="text-xl font-extrabold text-rose-600 mt-0.5">{{ number_format(\App\Models\GroupPost::sum('likes_count')) }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                <i class="fa fa-heart"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.group_posts.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts by title or content..."
                       class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>

            <select name="category" onchange="this.form.submit()" class="py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <option value="">All Categories</option>
                <option value="General" {{ request('category') == 'General' ? 'selected' : '' }}>General</option>
                <option value="Announcements" {{ request('category') == 'Announcements' ? 'selected' : '' }}>Announcements</option>
                <option value="Seller Discussion" {{ request('category') == 'Seller Discussion' ? 'selected' : '' }}>Seller Discussion</option>
                <option value="Offers & Deals" {{ request('category') == 'Offers & Deals' ? 'selected' : '' }}>Offers & Deals</option>
                <option value="Tips & Advice" {{ request('category') == 'Tips & Advice' ? 'selected' : '' }}>Tips & Advice</option>
            </select>

            <select name="status" onchange="this.form.submit()" class="py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-xs font-semibold transition">
                Filter
            </button>

            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('admin.group_posts.index') }}" class="text-xs text-gray-500 hover:text-rose-600 underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.group_posts.create') }}"
           class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 whitespace-nowrap">
            <i class="fa fa-pen-nib"></i>
            <span>Create Group Post</span>
        </a>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Post & Topic</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Author</th>
                        <th class="py-3 px-4 text-center">Engagement</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Published</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3.5 px-4 max-w-sm">
                            <div class="flex items-start gap-3">
                                @if($post->image)
                                    <img src="{{ $post->image }}" alt="" class="w-10 h-10 rounded-xl object-cover border border-gray-200 flex-shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa fa-newspaper text-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-bold text-gray-900 line-clamp-1">{{ $post->title }}</h4>
                                    <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $post->category }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-gray-700">
                            {{ $post->user ? $post->user->name : 'Super Admin' }}
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            <div class="inline-flex items-center gap-3 text-[11px] text-gray-600">
                                <span title="Views"><i class="fa fa-eye text-gray-400 mr-1"></i>{{ $post->views_count }}</span>
                                <span title="Likes"><i class="fa fa-heart text-rose-400 mr-1"></i>{{ $post->likes_count }}</span>
                            </div>
                        </td>

                        <td class="py-3.5 px-4 text-center">
                            @php
                                $badgeClass = match($post->status) {
                                    'published' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'draft'     => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'archived'  => 'bg-gray-100 text-gray-700 border-gray-200',
                                    default     => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $badgeClass }}">
                                {{ $post->status }}
                            </span>
                        </td>

                        <td class="py-3.5 px-4 text-gray-500 text-[11px] whitespace-nowrap">
                            {{ $post->created_at->format('M d, Y') }}
                        </td>

                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('admin.group_posts.edit', $post->id) }}"
                               class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition"
                               title="Edit Post">
                                <i class="fa fa-pen text-[11px]"></i>
                            </a>

                            <form action="{{ route('admin.group_posts.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this group post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition" title="Delete Post">
                                    <i class="fa fa-trash-alt text-[11px]"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400">
                            No group posts found matching criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
