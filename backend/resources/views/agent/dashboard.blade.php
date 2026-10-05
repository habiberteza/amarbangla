<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>এজেন্ট পার্টনার প্যানেল - Amar Bangla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <!-- Top Nav -->
    <header class="bg-indigo-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">আমার বাংলা - এজেন্ট পোর্টাল</h1>
                    <p class="text-xs text-indigo-300">দায়িত্বপ্রাপ্ত এলাকা: {{ $upazila ?? $district }} ({{ $district }})</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold bg-indigo-800/80 px-3 py-1.5 rounded-full border border-indigo-700">
                    <i class="fa fa-user-check mr-1.5 text-emerald-400"></i>{{ $user->name }}
                </span>
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
            <a href="{{ route('agent.dashboard') }}" class="py-3 border-b-2 border-indigo-600 text-indigo-600 flex items-center gap-2">
                <i class="fa fa-chart-pie"></i>ড্যাশবোর্ড
            </a>
            <a href="{{ route('agent.shops') }}" class="py-3 text-slate-600 hover:text-indigo-600 flex items-center gap-2">
                <i class="fa fa-store"></i>এলাকার শপসমূহ ({{ $totalShops }})
            </a>
            <a href="{{ route('agent.delivery_boys') }}" class="py-3 text-slate-600 hover:text-indigo-600 flex items-center gap-2">
                <i class="fa fa-motorcycle"></i>ডেলিভারি ম্যান ({{ $deliveryBoys }})
            </a>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa fa-store"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">মোট নথিভুক্ত শপ</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalShops) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">সক্রিয় (Active) শপ</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($activeShops) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa fa-clock"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">অনবোর্ডিং অপেক্ষমান</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($pendingShops) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa fa-motorcycle"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">সংশ্লিষ্ট ডেলিভারি পার্টনার</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($deliveryBoys) }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Area Shops -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-base">আপনার এরিয়ার শপ তালিকা ({{ $district }})</h3>
                <a href="{{ route('agent.shops') }}" class="text-xs text-indigo-600 font-bold hover:underline">সবগুলো দেখুন &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">দোকানের নাম</th>
                            <th class="px-5 py-3">ঠিকানা / এরিয়া</th>
                            <th class="px-5 py-3">ফোন নম্বর</th>
                            <th class="px-5 py-3">ধরন</th>
                            <th class="px-5 py-3">কমিশন রেট</th>
                            <th class="px-5 py-3">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentShops as $s)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $s->name }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $s->upazila ?? $s->district }}, {{ $s->division }}</td>
                            <td class="px-5 py-3.5 font-mono text-slate-700">{{ $s->phone ?? '-' }}</td>
                            <td class="px-5 py-3.5 capitalize font-medium text-slate-700">{{ $s->type ?? 'Local' }}</td>
                            <td class="px-5 py-3.5 font-semibold text-emerald-600">{{ $s->commission_rate }}%</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $s->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $s->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">এই এরিয়ায় কোনো শপ নেই।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
