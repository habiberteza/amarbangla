<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শপ/ভেন্ডর অ্যাডমিন প্যানেল - Amar Bangla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <!-- Top Nav -->
    <header class="bg-emerald-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">{{ $shop->name ?? 'আমার শপ' }}</h1>
                    <p class="text-xs text-emerald-300">শপ প্যানেল • {{ $shop->upazila ?? $shop->district }} ({{ $shop->shop_type ?? 'লোকাল শপ' }})</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('vendor.products.create') }}" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg shadow flex items-center gap-1.5">
                    <i class="fa fa-plus"></i>নতুন পণ্য যোগ করুন
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg shadow font-medium">
                        লগআউট
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Subnav -->
    <nav class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 flex gap-6 text-sm font-semibold">
            <a href="{{ route('vendor.dashboard') }}" class="py-3 border-b-2 border-emerald-600 text-emerald-700 flex items-center gap-2">
                <i class="fa fa-chart-line"></i>ড্যাশবোর্ড
            </a>
            <a href="{{ route('vendor.products') }}" class="py-3 text-slate-600 hover:text-emerald-700 flex items-center gap-2">
                <i class="fa fa-box-open"></i>পণ্য ও স্টক ({{ $totalProducts }})
            </a>
            <a href="{{ route('vendor.settings') }}" class="py-3 text-slate-600 hover:text-emerald-700 flex items-center gap-2">
                <i class="fa fa-sliders"></i>ডেলিভারি চার্জ ও সেটিংস
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa fa-boxes-stacked"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">মোট পণ্য সংখ্যা</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalProducts) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa fa-cart-shopping"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">মোট অর্ডার আইটেম</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalOrders) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa fa-bangladeshi-taka-sign"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">মোট অর্জিত সেলস</p>
                    <p class="text-2xl font-bold text-emerald-600">৳{{ number_format($totalSales, 2) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa fa-receipt"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">প্রদেয় কমিশন ({{ $shop->commission_rate ?? 5 }}%)</p>
                    <p class="text-2xl font-bold text-amber-600">৳{{ number_format($commissionPayable, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Store Orders -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-base">সাম্প্রতিক অর্ডারসমূহ</h3>
                <span class="text-xs text-slate-500">স্থানীয় ডেলিভারি সময়: ১০-৩০ মিনিট (লোকাল শপ)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">অর্ডার আইডি</th>
                            <th class="px-5 py-3">পণ্যের নাম</th>
                            <th class="px-5 py-3">মূল্য</th>
                            <th class="px-5 py-3">পরিমাণ</th>
                            <th class="px-5 py-3">মোট টাকা</th>
                            <th class="px-5 py-3">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentOrders as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-bold text-blue-600 font-mono">#{{ $item->order->order_number ?? $item->order_id }}</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $item->product_name }}</td>
                            <td class="px-5 py-3.5 font-mono">৳{{ number_format($item->price, 2) }}</td>
                            <td class="px-5 py-3.5 font-bold">{{ $item->quantity }}</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-700">৳{{ number_format($item->subtotal, 2) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 text-blue-800 capitalize">
                                    {{ $item->status ?? 'pending' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">এখনো কোনো নতুন অর্ডার আসেনি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
