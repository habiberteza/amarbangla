<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Amar Bangla Super Admin')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        secondary: '#d97706',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-slate-900 text-slate-200 flex-shrink-0 flex flex-col shadow-xl">
            <!-- Brand -->
            <div class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">AB</span>
                    <div>
                        <span class="font-bold text-white text-base tracking-wide block leading-tight">Amar Bangla</span>
                        <span class="text-xs text-blue-400 font-semibold tracking-wider uppercase">Super Admin</span>
                    </div>
                </a>
            </div>

            <!-- Nav Links (Matched exact order from https://superadmin.amarbangla.com.bd/super_admin/shop_category) -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-xs font-semibold">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-home w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-2 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3">MAIN NAVIGATION</div>

                <!-- 1. Shops category -->
                <a href="{{ route('admin.shop_categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.shop_categories.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-hospital w-4 text-center"></i>
                    <span>Shops category</span>
                </a>

                <!-- 2. Shops -->
                <a href="{{ route('admin.shops.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.shops.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa fa-store w-4 text-center"></i>
                        <span>Shops</span>
                        <span class="sr-only">Shop Management System</span>
                    </div>
                    @php $shopCount = \App\Models\Shop::count(); @endphp
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ request()->routeIs('admin.shops.*') ? 'bg-blue-700 text-white' : 'bg-slate-800 text-slate-400' }}">{{ $shopCount }}</span>
                </a>

                <!-- 3. License -->
                <a href="{{ route('admin.licenses.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.licenses.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-id-card w-4 text-center"></i>
                    <span>License</span>
                </a>

                <!-- 4. Users -->
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-users w-4 text-center"></i>
                    <span>Users</span>
                </a>

                <!-- 5. User Role -->
                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.roles.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-cubes w-4 text-center"></i>
                    <span>User Role</span>
                </a>

                <!-- 6. Settings -->
                <a href="{{ route('admin.general_settings.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ (request()->routeIs('admin.general_settings.*') || request()->routeIs('admin.settings.*')) ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-gears w-4 text-center"></i>
                    <span>Settings</span>
                </a>

                <!-- 7. Shop commission -->
                <a href="{{ route('admin.shops_commission.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.shops_commission.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-book w-4 text-center"></i>
                    <span>Shop commission</span>
                </a>

                <!-- 8. ledger -->
                <a href="{{ route('admin.ledger.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.ledger.*') || request()->routeIs('admin.ledgers.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-book w-4 text-center"></i>
                    <span>ledger</span>
                </a>

                <!-- 9. Customer (Treeview) -->
                @php $isCustomerActive = request()->routeIs('admin.customers.*') || request()->routeIs('admin.customer_types.*'); @endphp
                <details class="group" {{ $isCustomerActive ? 'open' : '' }}>
                    <summary class="list-none flex items-center justify-between px-3 py-2 rounded-lg cursor-pointer transition select-none {{ $isCustomerActive ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-2.5">
                            <i class="fa fa-users w-4 text-center"></i>
                            <span>Customer</span>
                        </div>
                        <i class="fa fa-chevron-right text-[10px] transition-transform duration-200 group-open:rotate-90 text-slate-400"></i>
                    </summary>
                    <div class="pl-7 pr-2 py-1 space-y-1">
                        <a href="{{ route('admin.customer_types.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.customer_types.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-child w-3.5 text-center"></i>
                            <span>Customer type</span>
                        </a>
                        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.customers.*') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-user-plus w-3.5 text-center"></i>
                            <span>Customers</span>
                        </a>
                    </div>
                </details>

                <!-- 10. All Order -->
                @php $pendingOrders = \App\Models\Order::where('status', 'pending')->count(); @endphp
                <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa fa-chart-line w-4 text-center"></i>
                        <span>All Order</span>
                    </div>
                    @if($pendingOrders > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white">{{ $pendingOrders }}</span>
                    @endif
                </a>

                <!-- 11. Calling Request -->
                @php $pendingCalls = \App\Models\CallingRequest::where('status', 'pending')->count(); @endphp
                <a href="{{ route('admin.calling_requests.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.calling_requests.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa fa-phone w-4 text-center"></i>
                        <span>Calling Request</span>
                    </div>
                    @if($pendingCalls > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-500 text-white">{{ $pendingCalls }}</span>
                    @endif
                </a>

                <!-- 12. Seller -->
                <a href="{{ route('admin.vendors.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ (request()->routeIs('admin.vendors.*') || request()->routeIs('admin.sellers.*')) ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-chart-line w-4 text-center"></i>
                    <span>Seller</span>
                    <span class="sr-only">Seller Management System</span>
                </a>

                <!-- 13. Delivery Boy -->
                <a href="{{ route('admin.delivery_boys.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.delivery_boys.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-chart-line w-4 text-center"></i>
                    <span>Delivery Boy</span>
                </a>

                <!-- 14. Agent -->
                <a href="{{ route('admin.agents.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.agents.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-chart-line w-4 text-center"></i>
                    <span>Agent</span>
                </a>

                <!-- 15. Global Address -->
                <a href="{{ route('admin.global_address.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.global_address.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-address-book w-4 text-center"></i>
                    <span>Global Address</span>
                </a>

                <!-- 16. Product Category -->
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-address-book w-4 text-center"></i>
                    <span>Product Category</span>
                </a>

                <!-- 17. Products -->
                @php $productCount = \App\Models\Product::count(); @endphp
                <a href="{{ route('admin.products.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <div class="flex items-center gap-2.5">
                        <i class="fa fa-address-book w-4 text-center"></i>
                        <span>Products</span>
                    </div>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ request()->routeIs('admin.products.*') ? 'bg-blue-700 text-white' : 'bg-slate-800 text-slate-400' }}">{{ number_format($productCount) }}</span>
                </a>

                <!-- 18. Product Color -->
                <a href="{{ route('admin.product_color.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.product_color.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-address-book w-4 text-center"></i>
                    <span>Product Color</span>
                </a>

                <!-- 19. Campaign -->
                <a href="{{ route('admin.campaigns.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.campaigns.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-address-book w-4 text-center"></i>
                    <span>Campaign</span>
                </a>

                <!-- 20. Group Post -->
                <a href="{{ route('admin.group_posts.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.group_posts.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-address-book w-4 text-center"></i>
                    <span>Group Post</span>
                </a>

                <!-- 21. Website settings (Treeview) -->
                @php $isWebActive = request()->routeIs('admin.website_settings.*') || request()->routeIs('admin.banners.*'); @endphp
                <details class="group" {{ $isWebActive ? 'open' : '' }}>
                    <summary class="list-none flex items-center justify-between px-3 py-2 rounded-lg cursor-pointer transition select-none {{ $isWebActive ? 'bg-slate-800 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-2.5">
                            <i class="fa fa-cubes w-4 text-center"></i>
                            <span>Website settings</span>
                        </div>
                        <i class="fa fa-chevron-right text-[10px] transition-transform duration-200 group-open:rotate-90 text-slate-400"></i>
                    </summary>
                    <div class="pl-7 pr-2 py-1 space-y-1">
                        <a href="{{ route('admin.website_settings.logo') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.logo') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Logo</span>
                        </a>
                        <a href="{{ route('admin.website_settings.banner') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.banner') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Banner</span>
                        </a>
                        <a href="{{ route('admin.website_settings.banner_product') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.banner_product') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Banner product page</span>
                        </a>
                        <a href="{{ route('admin.website_settings.slider') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.slider') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Slider</span>
                        </a>
                        <a href="{{ route('admin.website_settings.footer') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.footer') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Footer</span>
                        </a>
                        <a href="{{ route('admin.website_settings.mobile_slider') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.mobile_slider') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Mobile Slider</span>
                        </a>
                        <a href="{{ route('admin.website_settings.home_banner') }}" class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg transition text-xs {{ request()->routeIs('admin.website_settings.home_banner') ? 'bg-blue-600 text-white font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa fa-cubes w-3.5 text-center"></i>
                            <span>Home banner</span>
                        </a>
                    </div>
                </details>

                <!-- 22. Sms panel -->
                <a href="{{ route('admin.sms_panel.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.sms_panel.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-cubes w-4 text-center"></i>
                    <span>Sms panel</span>
                </a>

                <!-- 23. Gen settings -->
                <a href="{{ route('admin.general_settings.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.general_settings.*') || request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white shadow' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa fa-cubes w-4 text-center"></i>
                    <span>Gen settings</span>
                </a>
            </nav>

            <!-- User Footer -->
            <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                        {{ substr(auth()->user()->name ?? 'SA', 0, 2) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@amarbangla.com.bd' }}</p>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-400 hover:text-red-400 p-1.5 transition">
                        <i class="fa fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 px-6 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-4">
                    <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="https://amarbangla.com.bd" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200 transition">
                        <i class="fa fa-external-link"></i> Visit Amar Bangla
                    </a>
                </div>
            </header>

            <!-- Alerts -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-5 flex items-center gap-3 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm text-emerald-800 text-sm">
                        <i class="fa fa-circle-check text-emerald-500 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error') || (isset($errors) && $errors->any()))
                    <div class="mb-5 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm text-rose-800 text-sm">
                        <div class="flex items-center gap-3 font-semibold mb-1">
                            <i class="fa fa-triangle-exclamation text-rose-500 text-lg"></i>
                            <span>{{ session('error') ?? 'Please check the form for errors:' }}</span>
                        </div>
                        @if(isset($errors) && $errors->any())
                            <ul class="list-disc list-inside text-xs mt-1 space-y-0.5 text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Calling Bell Toast Alert -->
    <div id="callingBellToast" class="fixed bottom-6 right-6 z-50 hidden max-w-sm bg-slate-900 border border-slate-700 text-white p-4 rounded-2xl shadow-2xl flex items-start gap-3 transition-all duration-300">
        <div class="w-10 h-10 rounded-xl bg-rose-600 flex items-center justify-center text-white text-lg flex-shrink-0 animate-bounce">
            <i class="fa fa-bell"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-sm text-rose-400 flex items-center gap-1.5">
                <span>নতুন কলিং রিকোয়েস্ট!</span>
            </h4>
            <p id="callingToastName" class="text-xs font-semibold text-slate-200 mt-0.5">গ্রাহক: -</p>
            <p id="callingToastPhone" class="text-xs text-slate-400 font-mono">-</p>
            <div class="mt-2.5 flex items-center gap-2">
                <a href="{{ route('admin.calling_requests.index') }}" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow">
                    কল করুন &rarr;
                </a>
                <button type="button" onclick="document.getElementById('callingBellToast').classList.add('hidden')" class="px-2.5 py-1 text-slate-400 hover:text-white text-xs">
                    বন্ধ করুন
                </button>
            </div>
        </div>
    </div>

    <script>
        // Web Audio API Calling Bell Chime Generator (Realistic Ding-Dong Doorbell)
        function playCallingBellChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();

                const now = ctx.currentTime;
                // Tone 1: E5 (659.25 Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, now);
                gain1.gain.setValueAtTime(0.3, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.9);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.9);

                // Tone 2: C5 (523.25 Hz) after 0.25s
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(523.25, now + 0.25);
                gain2.gain.setValueAtTime(0.35, now + 0.25);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 1.4);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.25);
                osc2.stop(now + 1.4);
            } catch (e) {
                console.log("Audio alert initialized:", e);
            }
        }

        // Live Poll for Incoming Calling Requests every 12 seconds
        let lastPendingCount = {{ \App\Models\CallingRequest::where('status', 'pending')->count() }};
        setInterval(function() {
            fetch("{{ route('admin.calling_requests.poll') }}")
                .then(r => r.json())
                .then(data => {
                    if (data.pending_count > lastPendingCount) {
                        playCallingBellChime();
                        if (data.latest) {
                            document.getElementById('callingToastName').textContent = 'গ্রাহক: ' + data.latest.customer_name;
                            document.getElementById('callingToastPhone').textContent = 'ফোন: ' + data.latest.phone + ' • ' + data.latest.product_name;
                            document.getElementById('callingBellToast').classList.remove('hidden');
                        }
                    }
                    lastPendingCount = data.pending_count;
                })
                .catch(err => {});
        }, 12000);
    </script>
</body>
</html>
