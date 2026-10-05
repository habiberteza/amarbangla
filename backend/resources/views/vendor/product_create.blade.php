<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন পণ্য যোগ করুন - শপ অ্যাডমিন</title>
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
                    <p class="text-xs text-emerald-300">নতুন পণ্য এন্ট্রি ফরম</p>
                </div>
            </div>
            <a href="{{ route('vendor.products') }}" class="text-xs bg-emerald-800 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg border border-emerald-700">
                &larr; পণ্য তালিকায় ফিরুন
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            <h2 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100">পণ্যের বিবরণ পূরণ করুন</h2>

            <form action="{{ route('vendor.products.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">পণ্যের নাম *</label>
                    <input type="text" name="name" required placeholder="যেমন: তাজা মিনিকেট চাল ২৫ কেজি বস্তা"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ক্যাটাগরি *</label>
                        <select name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                            @foreach($categories as $cat)
                                <optgroup label="{{ $cat->name }}">
                                    @foreach($cat->children as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">স্টক পরিমাণ (Quantity) *</label>
                        <input type="number" name="stock_quantity" value="10" required min="0"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">মূল্য (টাকা) *</label>
                        <input type="number" name="price" step="0.01" required placeholder="100.00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ডিসকাউন্ট মূল্য (যদি থাকে)</label>
                        <input type="number" name="discount_price" step="0.01" placeholder="90.00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none">
                    </div>
                </div>

                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3">
                    <input type="checkbox" name="is_express" id="is_express" value="1" class="w-4 h-4 text-emerald-600 rounded">
                    <label for="is_express" class="text-xs font-semibold text-amber-900 cursor-pointer">
                        সুপার-ফাস্ট লোকাল এক্সপ্রেস ডেলিভারি পণ্য (১০ থেকে ৩০ মিনিটের মধ্যে ডেলিভারিযোগ্য)
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">সংক্ষিপ্ত বিবরণ</label>
                    <textarea name="description" rows="3" placeholder="পণ্যের গুণাগুণ ও বৈশিষ্ট্য লিখুন..."
                              class="w-full p-3 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-600 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('vendor.products') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">বাতিল</a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow">পণ্য সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
