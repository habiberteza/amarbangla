<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>উইথড্রয়াল ও কমিশন লেজার - সেলার প্যানেল</title>
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
                    <p class="text-xs text-amber-300">আর্থিক হিসাব ও উইথড্রয়াল লেজার</p>
                </div>
            </div>
            <a href="{{ route('seller.dashboard') }}" class="text-xs bg-amber-800 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg border border-amber-700">
                &larr; ড্যাশবোর্ড
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-base">আর্থিক লেনদেন ইতিহাস</h3>
                <span class="text-xs font-semibold text-emerald-700">বর্তমান ব্যালেন্স: ৳{{ number_format($seller->balance ?? 0, 2) }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">তারিখ</th>
                            <th class="px-5 py-3">বিবরণ / ট্রানজেকশন</th>
                            <th class="px-5 py-3">ধরনের হিসাব</th>
                            <th class="px-5 py-3">জমা / ক্রেডিট</th>
                            <th class="px-5 py-3">উত্তোলন / ডেবিট</th>
                            <th class="px-5 py-3">অবশিষ্ট ব্যালেন্স</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 text-slate-500">{{ $t->created_at->format('d M, Y') }}</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $t->description ?? 'কমিশন ক্রেডিট' }}</td>
                            <td class="px-5 py-3.5 capitalize font-medium">{{ $t->type ?? 'Commission' }}</td>
                            <td class="px-5 py-3.5 font-bold text-emerald-600 font-mono">৳{{ number_format($t->credit ?? 0, 2) }}</td>
                            <td class="px-5 py-3.5 font-bold text-rose-600 font-mono">৳{{ number_format($t->debit ?? 0, 2) }}</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900 font-mono">৳{{ number_format($t->balance ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">কোনো লেনদেন রেকর্ড নেই।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </main>
</body>
</html>
