<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>সেলার ও অ্যাফিলিয়েট পার্টনার প্যানেল - Amar Bangla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <header class="bg-amber-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">আমার বাংলা - সেলার ও অ্যাফিলিয়েট</h1>
                    <p class="text-xs text-amber-300">অ্যাফিলিয়েট কোড: #AFF-{{ $seller->id }} • কমিশন হার: {{ $commissionRate }}%</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold bg-amber-800/80 px-3 py-1.5 rounded-full border border-amber-700">
                    <i class="fa fa-wallet mr-1.5 text-emerald-400"></i>ব্যালেন্স: ৳{{ number_format($balance, 2) }}
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
        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="fa fa-wallet"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">চলতি বাকি ব্যালেন্স (উইথড্রয়ালযোগ্য)</p>
                    <p class="text-2xl font-bold text-emerald-600">৳{{ number_format($balance, 2) }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa fa-percent"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">নির্ধারিত কমিশন রেট</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $commissionRate }}%</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa fa-link"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">রেফারেল ট্র্যাকিং প্যারামিটার</p>
                    <p class="text-sm font-mono font-bold text-amber-700">?ref={{ $seller->id }}</p>
                </div>
            </div>
        </div>

        <!-- Products to Promote & Share -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="font-bold text-slate-900 text-base">প্রমোট করার জন্য লোকাল ও রেগুলার পণ্যসমূহ</h3>
                <span class="text-xs text-slate-500">ইউনিক অ্যাফিলিয়েট লিঙ্ক কপি করে শেয়ার করুন</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($products as $p)
                <div class="border border-slate-200 rounded-xl p-3 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="h-32 bg-slate-100 rounded-lg overflow-hidden mb-2">
                            <img src="{{ $p->main_image ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80' }}" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 line-clamp-1">{{ $p->name }}</h4>
                        <p class="text-[11px] text-slate-500">{{ $p->shop->name ?? 'Amar Bangla' }}</p>
                        <p class="text-sm font-bold text-emerald-600 mt-1">৳{{ number_format($p->effective_price, 2) }}</p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <button onclick="navigator.clipboard.writeText('{{ url('/product/' . $p->slug . '?ref=' . $seller->id) }}'); alert('অ্যাফিলিয়েট লিঙ্ক কপি হয়েছে!');"
                                class="w-full py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-lg shadow flex items-center justify-center gap-1.5">
                            <i class="fa fa-share-nodes"></i>লিঙ্ক কপি করুন
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</body>
</html>
