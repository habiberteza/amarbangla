@extends('admin.layout')

@section('title', 'Footer Settings - Amar Bangla Super Admin')
@section('page-title', 'Website Footer & Social Links (super_admin/website_settings/footer)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <div class="border-b border-gray-100 pb-4 mb-6">
            <h3 class="text-base font-bold text-gray-900">Website Footer Content & Social Media</h3>
            <p class="text-xs text-gray-500 mt-1">Configure company descriptions, customer helpdesk details, payment badges, and social media handles.</p>
        </div>

        <form action="{{ route('admin.website_settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- About Section -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">About Company (Footer Column 1)</label>
                <textarea name="footer_about" rows="3"
                          class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ $settings['footer_about'] ?? 'Amar Bangla is Bangladesh\'s premier hyper-local e-commerce marketplace connecting rural and urban merchants with millions of nationwide shoppers.' }}</textarea>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Helpdesk Hotline Phone</label>
                    <input type="text" name="footer_phone" value="{{ $settings['footer_phone'] ?? ($settings['contact_phone'] ?? '+880 1700-000000') }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Customer Support Email</label>
                    <input type="email" name="footer_email" value="{{ $settings['footer_email'] ?? ($settings['contact_email'] ?? 'support@amarbangla.com.bd') }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <!-- Copyright -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Copyright Statement</label>
                <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© 2026 Amar Bangla. All rights reserved.' }}"
                       class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <!-- Social Media Handles -->
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4">Official Social Media URLs</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fab fa-facebook text-blue-600 mr-1.5"></i> Facebook Page URL</label>
                        <input type="text" name="social_facebook" value="{{ $settings['social_facebook'] ?? 'https://facebook.com/amarbangla.com.bd' }}"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fab fa-youtube text-rose-600 mr-1.5"></i> YouTube Channel URL</label>
                        <input type="text" name="social_youtube" value="{{ $settings['social_youtube'] ?? 'https://youtube.com/@amarbangla' }}"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fab fa-instagram text-pink-600 mr-1.5"></i> Instagram Profile URL</label>
                        <input type="text" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/amarbangla"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fab fa-tiktok text-gray-900 mr-1.5"></i> TikTok Profile URL</label>
                        <input type="text" name="social_tiktok" value="{{ $settings['social_tiktok'] ?? '' }}" placeholder="https://tiktok.com/@amarbangla"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Mobile Apps Links -->
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4">Mobile App Download Badges</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fab fa-google-play text-emerald-600 mr-1.5"></i> Google Play Store URL</label>
                        <input type="text" name="app_playstore" value="{{ $settings['app_playstore'] ?? '' }}" placeholder="https://play.google.com/store/apps/details?id=bd.com.amarbangla"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1"><i class="fab fa-apple text-gray-800 mr-1.5"></i> Apple App Store URL</label>
                        <input type="text" name="app_appstore" value="{{ $settings['app_appstore'] ?? '' }}" placeholder="https://apps.apple.com/app/amarbangla"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end pt-3 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Footer Settings</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
