<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amar Bangla - Shop License Report</title>
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
            <a href="{{ route('admin.licenses.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                &larr; Back to License Management
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
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-0.5">Shop Licenses & Trade Permits Registry</p>
                <p class="text-[11px] text-gray-400 mt-1">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-xl text-xs font-bold text-blue-800">
                    Total Records: {{ $licenses->count() }}
                </div>
            </div>
        </div>

        <!-- Table: No | Shop Name | License Key | Start Date | End Date | Status | License Type -->
        <table class="w-full text-left text-xs border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase border-b border-gray-200">
                <tr>
                    <th class="p-2.5 border-r border-gray-200 w-12 text-center">No</th>
                    <th class="p-2.5 border-r border-gray-200">Shop Name</th>
                    <th class="p-2.5 border-r border-gray-200">License Key</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">Start Date</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">End Date</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">Status</th>
                    <th class="p-2.5">License Type</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($licenses as $lic)
                @php
                    $shopName = $lic->shop_name ?: ($lic->shop ? $lic->shop->name : 'Unassigned');
                    $licKey = $lic->license_key ?: ($lic->license_number ?: 'N/A');
                    $startDate = $lic->start_date ? \Illuminate\Support\Carbon::parse($lic->start_date)->format('M d, Y') : ($lic->issue_date ? \Illuminate\Support\Carbon::parse($lic->issue_date)->format('M d, Y') : '—');
                    $endDate = $lic->end_date ? \Illuminate\Support\Carbon::parse($lic->end_date)->format('M d, Y') : ($lic->expiry_date ? \Illuminate\Support\Carbon::parse($lic->expiry_date)->format('M d, Y') : 'Lifetime / Open');
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-r border-gray-200 text-center font-bold font-mono">{{ $loop->iteration }}</td>
                    <td class="p-2 border-r border-gray-200 font-bold text-gray-900">
                        {{ $shopName }}
                        @if($lic->vendor_name)
                            <div class="text-[10px] text-gray-400 font-normal">{{ $lic->vendor_name }}</div>
                        @endif
                    </td>
                    <td class="p-2 border-r border-gray-200 font-mono font-bold text-blue-700">
                        {{ $licKey }}
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center font-medium">
                        {{ $startDate }}
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center font-medium">
                        {{ $endDate }}
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center uppercase font-bold text-[10px]">
                        <span class="{{ $lic->status === 'approved' ? 'text-emerald-700' : ($lic->status === 'pending' ? 'text-amber-700' : 'text-rose-700') }}">
                            {{ $lic->status }}
                        </span>
                    </td>
                    <td class="p-2 text-gray-700 font-medium">
                        {{ $lic->license_type ?: 'Trade License' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">No shop licenses available to export.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-200 flex items-center justify-between text-xs text-gray-400">
            <span>Amar Bangla Super Admin Portal &bull; Official License System Export</span>
            <span>Page 1 of 1</span>
        </div>
    </div>

    @if(($format ?? '') === 'print')
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
    @endif
</body>
</html>
