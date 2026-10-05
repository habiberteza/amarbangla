<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
    <div class="border-b border-gray-200 bg-gray-50/70 px-4 pt-3">
        <ul class="flex flex-wrap -mb-px text-xs font-bold text-center text-gray-500" role="tablist">
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.logo') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.logo') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-image text-blue-600"></i>
                    <span>Logo & Branding</span>
                </a>
            </li>
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.slider') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.slider') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-sliders text-emerald-600"></i>
                    <span>Hero Sliders</span>
                </a>
            </li>
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.mobile_slider') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.mobile_slider') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-mobile-screen text-indigo-600"></i>
                    <span>Mobile Sliders</span>
                </a>
            </li>
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.banner') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.banner') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-rectangle-ad text-amber-500"></i>
                    <span>Top Banners</span>
                </a>
            </li>
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.home_banner') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.home_banner') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-table-cells-large text-purple-600"></i>
                    <span>Home Banners</span>
                </a>
            </li>
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.banner_product') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.banner_product') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-box-open text-teal-600"></i>
                    <span>Banner Products</span>
                </a>
            </li>
            <li class="mr-2" role="presentation">
                <a href="{{ route('admin.website_settings.footer') }}"
                   class="inline-flex items-center gap-2 py-3 px-4 border-b-2 rounded-t-lg transition {{ request()->routeIs('admin.website_settings.footer') ? 'text-blue-600 border-blue-600 active bg-white' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="fa fa-shoe-prints text-rose-600"></i>
                    <span>Footer Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>
