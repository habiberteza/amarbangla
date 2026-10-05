<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>প্রোডাক্ট প্রমোট ও অ্যাফিলিয়েট - সেলার প্যানেল</title>
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
                    <p class="text-xs text-amber-300">প্রোডাক্ট প্রমোশন ক্যাটালগ</p>
                </div>
            </div>
            <a href="{{ route('seller.dashboard') }}" class="text-xs bg-amber-800 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg border border-amber-700">
                &larr; ড্যাশবোর্ড
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <h2 class="font-bold text-slate-900 text-base">প্রমোট করার জন্য পণ্য তালিকা</h2>
            <form action="{{ route('seller.products') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="পণ্য খুঁজুন..."
                       class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs w-64 focus:outline-none focus:ring-2 focus:ring-amber-600">
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-xs font-semibold rounded-lg shadow">খুঁজুন</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($products as $p)
            <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="h-36 bg-slate-100 rounded-xl overflow-hidden mb-3">
                        <img src="{{ $p->main_image ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=500&q=80' }}" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-bold text-xs text-slate-900 line-clamp-1">{{ $p->name }}</h4>
                    <p class="text-[11px] text-slate-500">{{ $p->shop->name ?? 'Amar Bangla Store' }}</p>
                    <p class="text-sm font-bold text-emerald-600 mt-1">৳{{ number_format($p->effective_price, 2) }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <button onclick="navigator.clipboard.writeText('{{ url('/product/' . $p->slug . '?ref=' . $seller->id) }}'); alert('অ্যাফিলিয়েট লিঙ্ক ক্লিপবোর্ডে কপি হয়েছে!');"
                            class="w-full py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow flex items-center justify-center gap-1.5 transition">
                        <i class="fa fa-share-nodes"></i>ইউনিক লিঙ্ক কপি
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-4 p-12 text-center text-slate-400">কোনো পণ্য পাওয়া যায়নি।</div>
            @endforelse
        </div>

        @if($products->hasPages())
        <div class="p-4 bg-white rounded-xl border border-slate-200">
            {{ $products->links() }}
        </div>
        @endif
    </main>
</body>
</html>
