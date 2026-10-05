<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amar Bangla - Shop Management Export Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11px; }
            @page { margin: 12mm; size: landscape; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 p-6">
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        <!-- Action Buttons -->
        <div class="no-print flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
            <a href="{{ route('admin.shops.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                &larr; Back to Shop Management
            </a>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                    <i class="fa fa-print"></i>
                    <span>Print / Save as PDF</span>
                </button>
            </div>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
            <div>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-wide">AMAR BANGLA</h1>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-0.5">Shop Outlets Registry & Status Summary</p>
                <p class="text-[11px] text-gray-400 mt-1">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-xl text-xs font-bold text-blue-800">
                    Total Records: {{ $shops->count() }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left text-xs border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase border-b border-gray-200">
                <tr>
                    <th class="p-2.5 border-r border-gray-200 w-10 text-center">No</th>
                    <th class="p-2.5 border-r border-gray-200">Shop Name</th>
                    <th class="p-2.5 border-r border-gray-200">Contact (Mobile/Email)</th>
                    <th class="p-2.5 border-r border-gray-200 text-right">Balance</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">Commission</th>
                    <th class="p-2.5 border-r border-gray-200">Region / Address</th>
                    <th class="p-2.5 border-r border-gray-200">Category</th>
                    <th class="p-2.5 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($shops as $shop)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-r border-gray-200 text-center font-bold font-mono">{{ $loop->iteration }}</td>
                    <td class="p-2 border-r border-gray-200 font-bold text-gray-900">
                        {{ $shop->name }}
                        @if($shop->home_feature || $shop->is_featured)
                            <span class="text-[10px] text-amber-700 font-semibold">[Featured]</span>
                        @endif
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        <div>{{ $shop->phone ?? $shop->mobile ?? '-' }}</div>
                        <div class="text-[10px] text-gray-500">{{ $shop->email ?? '-' }}</div>
                    </td>
                    <td class="p-2 border-r border-gray-200 text-right font-mono font-bold">
                        ৳ {{ number_format($shop->balance, 2) }}
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center font-bold">
                        {{ $shop->commission_rate }}%
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        <div>{{ implode(', ', array_filter([$shop->upazila, $shop->district, $shop->division])) ?: 'All Bangladesh' }}</div>
                        @if($shop->house_address || $shop->address)
                            <div class="text-[10px] text-gray-500">{{ $shop->house_address ?? $shop->address }}</div>
                        @endif
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        {{ $shop->category ?? ($shop->categoryRel->name ?? 'N/A') }}
                    </td>
                    <td class="p-2 text-center uppercase font-bold text-[10px]">
                        {{ $shop->status }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-400">No shops available to export.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-200 flex items-center justify-between text-xs text-gray-400">
            <span>Amar Bangla Super Admin Portal &bull; Official System Export</span>
            <span>Page 1 of 1</span>
        </div>
    </div>
</body>
</html>
