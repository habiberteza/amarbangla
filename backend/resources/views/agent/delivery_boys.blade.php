<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ডেলিভারি ম্যান তালিকা - এজেন্ট পার্টনার</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <header class="bg-indigo-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">আমার বাংলা - এজেন্ট পোর্টাল</h1>
                    <p class="text-xs text-indigo-300">লোকাল ডেলিভারি বয় ও রাইডার মনিটরিং</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('agent.dashboard') }}" class="text-xs bg-indigo-800 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg border border-indigo-700">
                    &larr; ড্যাশবোর্ডে ফিরুন
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-base">আপনার আওতাধীন ডেলিভারি পার্টনারবৃন্দ</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">রাইডারের নাম</th>
                            <th class="px-5 py-3">ফোন নম্বর</th>
                            <th class="px-5 py-3">এরিয়া / উপজেলা</th>
                            <th class="px-5 py-3">যানবাহনের ধরন</th>
                            <th class="px-5 py-3">ক্যাশ ইন হ্যান্ড</th>
                            <th class="px-5 py-3">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riders as $r)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-3.5 font-bold text-slate-900">{{ $r->name }}</td>
                            <td class="px-5 py-3.5 font-mono text-blue-600 font-bold"><a href="tel:{{ $r->phone }}">{{ $r->phone }}</a></td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $r->upazila ?? $r->district }}</td>
                            <td class="px-5 py-3.5 capitalize">{{ $r->vehicle_type ?? 'Motorcycle' }}</td>
                            <td class="px-5 py-3.5 font-mono font-bold text-emerald-600">৳{{ number_format($r->cash_in_hand, 2) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $r->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">কোনো ডেলিভারি বয় পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
