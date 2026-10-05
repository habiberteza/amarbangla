@extends('admin.layout')

@section('title', 'Marketing Campaigns - Amar Bangla Super Admin')
@section('page-title', 'Marketing & Promotional Campaigns (super_admin/campaign)')

@section('content')
<div class="space-y-6">

    <!-- Header Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Total Campaigns</p>
                <h3 class="text-xl font-extrabold text-gray-800 mt-0.5">{{ \App\Models\Campaign::count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                <i class="fa fa-bullhorn"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Active Now</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ \App\Models\Campaign::where('status', 'active')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                <i class="fa fa-circle-play"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Upcoming</p>
                <h3 class="text-xl font-extrabold text-amber-600 mt-0.5">{{ \App\Models\Campaign::where('status', 'upcoming')->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                <i class="fa fa-calendar-days"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold tracking-wider text-gray-400 uppercase">Featured</p>
                <h3 class="text-xl font-extrabold text-purple-600 mt-0.5">{{ \App\Models\Campaign::where('is_featured', true)->count() }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-sm">
                <i class="fa fa-star"></i>
            </div>
        </div>
    </div>

    <!-- Actions & Filter -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.campaigns.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search campaigns by title or subtitle..."
                       class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <i class="fa fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>

            <select name="status" onchange="this.form.submit()" class="py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded-xl text-xs font-semibold transition">
                Filter
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.campaigns.index') }}" class="text-xs text-gray-500 hover:text-rose-600 underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.campaigns.create') }}"
           class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 whitespace-nowrap">
            <i class="fa fa-plus"></i>
            <span>Create Campaign</span>
        </a>
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($campaigns as $camp)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
            <!-- Banner image / header -->
            <div class="h-36 bg-gradient-to-tr from-slate-800 to-blue-900 relative overflow-hidden flex items-center justify-center">
                @if($camp->banner_image)
                    <img src="{{ $camp->banner_image }}" alt="{{ $camp->title }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center text-white/70 p-4">
                        <i class="fa fa-gift text-3xl mb-1 text-white/50"></i>
                        <p class="text-xs font-semibold">{{ $camp->title }}</p>
                    </div>
                @endif

                <!-- Discount Tag -->
                @if($camp->discount_percentage > 0)
                <span class="absolute top-3 left-3 px-2.5 py-1 bg-rose-600 text-white font-extrabold text-[11px] rounded-lg shadow">
                    UP TO {{ number_format($camp->discount_percentage) }}% OFF
                </span>
                @endif

                <!-- Status Tag -->
                <div class="absolute top-3 right-3 flex items-center gap-1.5">
                    @if($camp->is_featured)
                        <span class="w-6 h-6 rounded-full bg-amber-400 text-amber-950 flex items-center justify-center text-[10px] shadow" title="Featured Campaign">
                            <i class="fa fa-star"></i>
                        </span>
                    @endif

                    @php
                        $badgeClasses = match($camp->status) {
                            'active'   => 'bg-emerald-500 text-white',
                            'upcoming' => 'bg-amber-500 text-white',
                            'expired'  => 'bg-gray-600 text-white',
                            'disabled' => 'bg-rose-500 text-white',
                            default    => 'bg-gray-500 text-white'
                        };
                    @endphp
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider shadow {{ $badgeClasses }}">
                        {{ $camp->status }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $camp->title }}</h3>
                    @if($camp->subtitle)
                        <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $camp->subtitle }}</p>
                    @endif

                    @if($camp->description)
                        <p class="text-[11px] text-gray-600 line-clamp-2 mt-2">{{ $camp->description }}</p>
                    @endif

                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                        <div>
                            <i class="fa fa-calendar-alt text-gray-400 mr-1"></i>
                            <span>{{ $camp->start_date ? \Carbon\Carbon::parse($camp->start_date)->format('M d, Y') : 'Immediate' }}</span>
                        </div>
                        <div>
                            <i class="fa fa-flag-checkered text-gray-400 mr-1"></i>
                            <span>{{ $camp->end_date ? \Carbon\Carbon::parse($camp->end_date)->format('M d, Y') : 'Ongoing' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 mt-3 border-t border-gray-100">
                    <a href="{{ route('admin.campaigns.edit', $camp->id) }}"
                       class="px-3 py-1.5 bg-gray-100 hover:bg-blue-50 text-gray-700 hover:text-blue-600 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                        <i class="fa fa-pen text-[10px]"></i>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.campaigns.destroy', $camp->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this campaign?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-rose-50 text-gray-700 hover:text-rose-600 rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                            <i class="fa fa-trash text-[10px]"></i>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-12 rounded-2xl border border-gray-200 text-center">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3 text-lg">
                <i class="fa fa-bullhorn"></i>
            </div>
            <h4 class="text-sm font-bold text-gray-800">No campaigns found</h4>
            <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Get started by launching your first flash sale, eid festival, or clearance promotional campaign.</p>
            <a href="{{ route('admin.campaigns.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                <i class="fa fa-plus"></i>
                <span>Create Campaign</span>
            </a>
        </div>
        @endforelse
    </div>

    @if($campaigns->hasPages())
    <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        {{ $campaigns->links() }}
    </div>
    @endif

</div>
@endsection
