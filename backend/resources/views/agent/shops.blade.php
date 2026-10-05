<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>এলাকার শপ তালিকা - এজেন্ট পার্টনার</title>
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
                    <p class="text-xs text-indigo-300">এলাকার শপ ম্যানেজমেন্ট ({{ $district }})</p>
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
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <h2 class="font-bold text-slate-900 text-base">আপনার আওতাধীন শপসমূহ ({{ $shops->total() }} টি)</h2>
            <form action="{{ route('agent.shops') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="শপ বা ফোন খুঁজুন..."
                       class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs w-64 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg shadow">খুঁজুন</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="px-5 py-3">দোকানের নাম</th>
                            <th class="px-5 py-3">ঠিকানা / এরিয়া</th>
                            <th class="px-5 py-3">মোবাইল</th>
                            <th class="px-5 py-3">ধরন</th>
                            <th class="px-5 py-3">কমিশন</th>
                            <th class="px-5 py-3">স্ট্যাটাস</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($shops as $s)
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
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">কোনো শপ পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($shops->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $shops->links() }}
            </div>
            @endif
        </div>
    </main>
</body>
</html>
