<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পণ্য ও স্টক ম্যানেজমেন্ট - শপ অ্যাডমিন</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <header class="bg-emerald-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">{{ $shop->name }}</h1>
                    <p class="text-xs text-emerald-300">পণ্য ও স্টক তালিকা</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('vendor.products.create') }}" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg shadow flex items-center gap-1.5">
                    <i class="fa fa-plus"></i>নতুন পণ্য যোগ করুন
                </a>
                <a href="{{ route('vendor.dashboard') }}" class="text-xs bg-emerald-800 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg border border-emerald-700">
                    &larr; ড্যাশবোর্ড
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-base">আপনার শপের পণ্য তালিকা</h3>
                <span class="text-xs text-slate-500">মোট পণ্য: {{ $products->total() }} টি</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">ছবি ও নাম</th>
                            <th class="px-5 py-3">ক্যাটাগরি</th>
                            <th class="px-5 py-3">বিক্রয়মূল্য</th>
                            <th class="px-5 py-3">স্টক পরিমাণ</th>
                            <th class="px-5 py-3">এক্সপ্রেস ডেলিভারি</th>
                            <th class="px-5 py-3">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($products as $p)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 flex items-center gap-3">
                                <img src="{{ $p->main_image ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=100' }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                <div>
                                    <span class="font-bold text-slate-900 block">{{ $p->name }}</span>
                                    <span class="text-[10px] text-slate-400">SKU: {{ $p->item_code ?? ('ABP-' . $p->id) }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $p->category->name ?? '-' }}</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-700 font-mono">৳{{ number_format($p->effective_price, 2) }}</td>
                            <td class="px-5 py-3.5 font-bold {{ $p->stock_quantity <= 5 ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $p->stock_quantity }} টি
                            </td>
                            <td class="px-5 py-3.5">
                                @if($p->is_express)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">১০-৩০ মিনিট</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">রেগুলার</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $p->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $p->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">কোনো পণ্য তালিকাভুক্ত নেই।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </main>
</body>
</html>
