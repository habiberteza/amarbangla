<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শপ সেটিংস ও ডেলিভারি মডেল - শপ অ্যাডমিন</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    <header class="bg-emerald-900 text-white shadow-md">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center font-bold text-lg shadow">AB</span>
                <div>
                    <h1 class="font-bold text-lg leading-tight">{{ $shop->name }}</h1>
                    <p class="text-xs text-emerald-300">শপ ও ডেলিভারি মডেল কনফিগারেশন</p>
                </div>
            </div>
            <a href="{{ route('vendor.dashboard') }}" class="text-xs bg-emerald-800 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg border border-emerald-700">
                &larr; ড্যাশবোর্ড
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">শপের ডেলিভারি মডেল ও ইনফরমেশন</h2>

            <form action="{{ route('vendor.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">দোকানের নাম *</label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">যোগাযোগের ফোন নম্বর *</label>
                        <input type="text" name="phone" value="{{ old('phone', $shop->phone) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">শপের ডেলিভারি ধরন (Shop Type) *</label>
                        <select name="shop_type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            <option value="local" {{ ($shop->shop_type ?? 'local') === 'local' ? 'selected' : '' }}>
                                লোকাল শপ (১০ থেকে ৩০ মিনিট সুপার-ফাস্ট ডেলিভারি)
                            </option>
                            <option value="regular" {{ ($shop->shop_type ?? '') === 'regular' ? 'selected' : '' }}>
                                রেগুলার শপ (১ থেকে ৩ দিন স্ট্যান্ডার্ড ডেলিভারি)
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">দোকানের বিস্তারিত ঠিকানা</label>
                    <input type="text" name="address" value="{{ old('address', $shop->address) }}" placeholder="দোকানের ঠিকানা..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">শপ ডেসক্রিপশন</label>
                    <textarea name="description" rows="3" class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">{{ old('description', $shop->description) }}</textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow">
                        সেটিংস সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
