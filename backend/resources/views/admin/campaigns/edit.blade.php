@extends('admin.layout')

@section('title', 'Edit Campaign - Amar Bangla Super Admin')
@section('page-title', 'Edit Campaign: ' . $campaign->title . ' (super_admin/campaign_update/' . $campaign->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.campaigns.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Campaigns</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Campaign #{{ $campaign->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-bullhorn text-amber-500"></i>
                <span>Edit Campaign: {{ $campaign->title }}</span>
            </h2>
            <p class="text-xs text-gray-500">Update promotional campaign details, banners, timeline and discount settings</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>campaign_update/{{ $campaign->id }}</span>
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

    <!-- Campaign Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            @php
                $banner = $campaign->banner_image;
                $bannerSrc = $banner ? (str_starts_with($banner, 'http') ? $banner : asset($banner)) : asset('uploads/no_image.jpg');
            @endphp
            <img src="{{ $bannerSrc }}" alt="{{ $campaign->title }}"
                 class="w-20 h-14 rounded-xl object-cover border border-gray-200 shadow-xs"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($campaign->title) }}&background=f59e0b&color=fff'">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ $campaign->title }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $campaign->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ $campaign->status }}
                    </span>
                    @if($campaign->is_featured)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800 flex items-center gap-1">
                            <i class="fa fa-star text-[9px]"></i> Featured
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5">{{ $campaign->subtitle ?: 'Seasonal Promotional Campaign' }}</p>
                <div class="flex items-center gap-2 mt-1 text-[11px] text-gray-500 font-mono">
                    <span>Discount: <strong class="text-emerald-700 font-bold">{{ $campaign->discount_percentage ?? 0 }}% OFF</strong></span>
                    <span>&bull;</span>
                    <span>{{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('d M, Y') : 'Immediate' }} &rarr; {{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('d M, Y') : 'No expiry' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Campaign Info (#custom-tabs-four-home / #registertab)
         2. Banner & Media (#custom-tabs-four-messages / #media) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="campaignTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchCampaignTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="campaign-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-amber-500 text-white shadow-sm">
            <i class="fa fa-bullhorn"></i>
            <span>Campaign Info</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchCampaignTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="campaign-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-image"></i>
            <span>Banner & Media</span>
        </a>
    </div>

    <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: Campaign Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="campaign-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-bullhorn text-amber-500"></i>
                    <span>Campaign Information & Timeline</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Campaign headline, discount percentage, runtime duration and active status</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Campaign Title <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $campaign->title) }}" required
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Subtitle / Tagline
                    </label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $campaign->subtitle) }}"
                           placeholder="Short catchy tagline..."
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Max Discount Percentage (%)
                        </label>
                        <input type="number" step="0.01" min="0" max="100" name="discount_percentage"
                               value="{{ old('discount_percentage', $campaign->discount_percentage) }}"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                            <option value="active" {{ $campaign->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="upcoming" {{ $campaign->status === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="expired" {{ $campaign->status === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="disabled" {{ $campaign->status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Start Date</label>
                        <input type="date" name="start_date"
                               value="{{ old('start_date', $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('Y-m-d') : '') }}"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">End Date</label>
                        <input type="date" name="end_date"
                               value="{{ old('end_date', $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d') : '') }}"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Description / Campaign Terms</label>
                    <textarea name="description" rows="3" placeholder="Campaign promotion rules and terms..."
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">{{ old('description', $campaign->description) }}</textarea>
                </div>

                <div class="pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ $campaign->is_featured ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-bold text-gray-700">Display prominently on App / Web Homepage banner carousel</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.campaigns.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Campaign</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Banner & Media (#custom-tabs-four-messages) -->
        <div id="custom-tabs-four-messages" class="campaign-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-image text-indigo-600"></i>
                    <span>Campaign Promotional Banner & Media</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Upload hero banner image (Recommended Size: 390x100 or 1200x400)</p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Current Campaign Banner Preview
                    </label>
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center">
                        <img id="campaign_banner_preview" src="{{ $bannerSrc }}" alt="{{ $campaign->title }}"
                             class="max-h-48 rounded-xl object-contain border border-gray-200 shadow-2xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Upload New Banner File
                        </label>
                        <input type="file" name="image" id="campaign_image_input" accept="image/*" onchange="previewCampaignBanner(this)"
                               class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-200 rounded-xl p-1 bg-gray-50">
                        <p class="text-[10px] text-gray-400 mt-1">Recommended size: <strong>390x100</strong> for mobile or <strong>1200x400</strong> for web.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Or Banner Image URL
                        </label>
                        <input type="text" name="banner_image" value="{{ old('banner_image', $campaign->banner_image) }}"
                               placeholder="https://example.com/banner.jpg"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchCampaignTab('custom-tabs-four-home')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Campaign Info
                </button>

                <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Campaign Banner</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function switchCampaignTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'info': 'custom-tabs-four-home',
        'custom-tabs-four-messages': 'custom-tabs-four-messages',
        'media': 'custom-tabs-four-messages',
        'banner': 'custom-tabs-four-messages'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.campaign-tab-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.campaign-tab-btn').forEach(btn => {
        btn.classList.remove('bg-amber-500', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });

    const activeBtn = document.getElementById('btn-' + activeId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-amber-500', 'text-white', 'shadow-sm');
    }

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchCampaignTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchCampaignTab(hash);
    }
});

function previewCampaignBanner(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('campaign_banner_preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
