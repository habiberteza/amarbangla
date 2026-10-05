@extends('admin.layout')

@section('title', 'System Settings - Amar Bangla Super Admin')
@section('page-title', 'Platform Settings & Configurations (super_admin/settings)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-sliders text-blue-600"></i>
                <span>General Platform Settings & System Preferences</span>
            </h2>
            <p class="text-xs text-gray-500">Configure global site identity, customer support hotlines, delivery fee policies, and marketing tracking</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>super_admin/settings</span>
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-xs font-semibold shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Navigation Tabs Matching Super Admin:
         1. General Info (#custom-tabs-four-home / #registertab)
         2. Support & Contact (#custom-tabs-four-profile / #support)
         3. Delivery Charges (#custom-tabs-four-settings / #delivery)
         4. Marketing & Pixels (#custom-tabs-four-messages / #marketing) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="settingsTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchSettingsTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-globe"></i>
            <span>General Info</span>
        </a>

        <a href="#custom-tabs-four-profile" onclick="switchSettingsTab('custom-tabs-four-profile'); return false;" id="btn-custom-tabs-four-profile"
           class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-headset"></i>
            <span>Support & Contact</span>
        </a>

        <a href="#custom-tabs-four-settings" onclick="switchSettingsTab('custom-tabs-four-settings'); return false;" id="btn-custom-tabs-four-settings"
           class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-truck-fast"></i>
            <span>Delivery Charges</span>
        </a>

        <a href="#custom-tabs-four-messages" onclick="switchSettingsTab('custom-tabs-four-messages'); return false;" id="btn-custom-tabs-four-messages"
           class="settings-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-chart-line"></i>
            <span>Marketing & Pixels</span>
        </a>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- TAB 1: General Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="settings-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-globe text-blue-600"></i>
                    <span>Store Identity & Business Location</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Primary platform title, brand slogan and corporate headquarters address</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Website Title / Brand Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'Amar Bangla' }}" required
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Platform Tagline / Slogan</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'Amar Bangla - All in One Marketplace' }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Head Office / Location Address</label>
                    <textarea name="address" rows="3" placeholder="Headquarters street address, city, Bangladesh..."
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">{{ $settings['address'] ?? 'Dhaka, Khulna & All across Bangladesh' }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchSettingsTab('custom-tabs-four-profile')"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                    <span>Next: Support & Contact</span>
                    <i class="fa fa-arrow-right text-[11px]"></i>
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold text-xs rounded-xl shadow transition">
                    Save Changes
                </button>
            </div>
        </div>

        <!-- TAB 2: Support & Contact (#custom-tabs-four-profile) -->
        <div id="custom-tabs-four-profile" class="settings-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-headset text-indigo-600"></i>
                    <span>Customer Support & Communications</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Hotline numbers, official support email and official Facebook page</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Customer Support Hotline Phone</label>
                    <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '+880 1714070770' }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Official Support Email</label>
                    <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'suggestionbd@gmail.com' }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Official Facebook Page URL</label>
                    <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://www.facebook.com/..."
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchSettingsTab('custom-tabs-four-home')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to General Info
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold text-xs rounded-xl shadow transition">
                    Save Changes
                </button>
            </div>
        </div>

        <!-- TAB 3: Delivery Charges (#custom-tabs-four-settings) -->
        <div id="custom-tabs-four-settings" class="settings-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-truck-fast text-emerald-600"></i>
                    <span>Default Courier Delivery Charges</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Global baseline shipping fee rules applied across Bangladesh checkout</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Delivery Charge Inside Dhaka (৳)</label>
                    <input type="number" step="0.01" name="delivery_charge_inside_dhaka" value="{{ $settings['delivery_charge_inside_dhaka'] ?? '60' }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-emerald-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Delivery Charge Outside Dhaka (৳)</label>
                    <input type="number" step="0.01" name="delivery_charge_outside_dhaka" value="{{ $settings['delivery_charge_outside_dhaka'] ?? '120' }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-blue-700 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchSettingsTab('custom-tabs-four-profile')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Support
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold text-xs rounded-xl shadow transition">
                    Save Changes
                </button>
            </div>
        </div>

        <!-- TAB 4: Marketing & Pixels (#custom-tabs-four-messages) -->
        <div id="custom-tabs-four-messages" class="settings-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-chart-line text-purple-600"></i>
                    <span>Advertising & Analytics Pixels</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Integrate Facebook Meta Pixel and Google conversion measurement tags</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Meta Pixel ID (Facebook Ads)</label>
                    <input type="text" name="meta_pixel" value="{{ $settings['meta_pixel'] ?? '754148889720140' }}" placeholder="e.g. 754148889720140"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Google Analytics Tag (GA4 Measurement ID)</label>
                    <input type="text" name="google_analytics" value="{{ $settings['google_analytics'] ?? '' }}" placeholder="G-XXXXXXXXXX"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchSettingsTab('custom-tabs-four-settings')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Delivery Charges
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save All Platform Settings</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function switchSettingsTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'general': 'custom-tabs-four-home',
        'custom-tabs-four-profile': 'custom-tabs-four-profile',
        'support': 'custom-tabs-four-profile',
        'contact': 'custom-tabs-four-profile',
        'custom-tabs-four-settings': 'custom-tabs-four-settings',
        'delivery': 'custom-tabs-four-settings',
        'custom-tabs-four-messages': 'custom-tabs-four-messages',
        'marketing': 'custom-tabs-four-messages',
        'pixel': 'custom-tabs-four-messages'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.settings-tab-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.settings-tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });

    const activeBtn = document.getElementById('btn-' + activeId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchSettingsTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchSettingsTab(hash);
    }
});
</script>
@endsection
