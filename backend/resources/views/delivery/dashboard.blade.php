<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ডেলিভারি পার্টনার / রাইডার প্যানেল - Amar Bangla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <header class="bg-cyan-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-cyan-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">আমার বাংলা - ডেলিভারি পার্টনার</h1>
                    <p class="text-xs text-cyan-300">এরিয়া: {{ $upazila ?? $district }} • লোকাল ডেলিভারি স্পিড: ১০-৩০ মিনিট</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold bg-cyan-800/80 px-3 py-1.5 rounded-full border border-cyan-700">
                    <i class="fa fa-hand-holding-dollar mr-1.5 text-emerald-400"></i>ক্যাশ ইন হ্যান্ড: ৳{{ number_format($cashInHand, 2) }}
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

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa fa-motorcycle"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">এলাকায় চলমান রানিং অর্ডার</p>
                    <p class="text-2xl font-bold text-slate-900">{{ count($runningOrders) }} টি</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">সফল ডেলিভারি সংখ্যা</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $completedOrdersCount }} টি</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa fa-coins"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">মোট ডেলিভারি ফি আর্নিং</p>
                    <p class="text-2xl font-bold text-amber-600">৳{{ number_format($balance, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Neighborhood Running Orders -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-base">আপনার এলাকার রানিং ডেলিভারি অর্ডার ({{ $district }})</h3>
                <span class="text-xs text-slate-500">গুগল ম্যাপে লোকেশন দেখে এক্সেপ্ট করুন</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($runningOrders as $ord)
                <div class="p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 hover:bg-slate-50 transition">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-slate-900 font-mono">#{{ $ord->order_number }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 capitalize">{{ $ord->order_status }}</span>
                        </div>
                        <p class="text-xs text-slate-600"><i class="fa fa-user mr-1 text-slate-400"></i>{{ $ord->customer_name }} • <a href="tel:{{ $ord->customer_phone }}" class="text-blue-600 font-mono font-bold">{{ $ord->customer_phone }}</a></p>
                        <p class="text-xs text-slate-500"><i class="fa fa-location-dot mr-1 text-rose-500"></i>{{ $ord->shipping_address }}, {{ $ord->upazila ?? $ord->district }}</p>
                        <p class="text-xs font-semibold text-emerald-700">টোটাল বিল: ৳{{ number_format($ord->total_amount, 2) }} ({{ strtoupper($ord->payment_method) }}) • ডেলিভারি ফি: ৳{{ number_format($ord->shipping_fee, 2) }}</p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <a href="https://maps.google.com/?q={{ urlencode($ord->shipping_address . ', ' . ($ord->upazila ?? $ord->district)) }}" target="_blank"
                           class="px-3 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 transition">
                            <i class="fa fa-diamond-turn-right text-blue-600"></i>ম্যাপ নেভিগেশন
                        </a>

                        @if($ord->order_status !== 'out_for_delivery')
                        <form action="{{ route('delivery.orders.accept', $ord) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow flex items-center gap-1.5">
                                <i class="fa fa-check"></i>পিকআপ এক্সেপ্ট করুন
                            </button>
                        </form>
                        @else
                        <form action="{{ route('delivery.orders.complete', $ord) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow flex items-center gap-1.5">
                                <i class="fa fa-circle-check"></i>ডেলিভারি সম্পন্ন করুন
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-slate-400">এই মুহূর্তে কোনো পেন্ডিং লোকাল ডেলিভারি নেই।</div>
                @endforelse
            </div>
        </div>
    </main>
</body>
</html>
