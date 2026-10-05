<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amar Bangla - Customer Directory Export Report</title>
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
            <a href="{{ route('admin.customers.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                &larr; Back to Customer Directory
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
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-0.5">Customer Profiles & Geographic Directory</p>
                <p class="text-[11px] text-gray-400 mt-1">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-xl text-xs font-bold text-blue-800">
                    Total Records: {{ $customers->count() }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left text-xs border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase border-b border-gray-200">
                <tr>
                    <th class="p-2.5 border-r border-gray-200 w-10 text-center">No</th>
                    <th class="p-2.5 border-r border-gray-200">Customer Name</th>
                    <th class="p-2.5 border-r border-gray-200">Contact Details</th>
                    <th class="p-2.5 border-r border-gray-200">Type</th>
                    <th class="p-2.5 border-r border-gray-200 text-right">Balance</th>
                    <th class="p-2.5 border-r border-gray-200">Geographic Region & Address</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">Orders</th>
                    <th class="p-2.5 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-r border-gray-200 text-center font-bold font-mono">{{ $loop->iteration }}</td>
                    <td class="p-2 border-r border-gray-200 font-bold text-gray-900">
                        {{ $customer->name }}
                        <div class="text-[10px] text-gray-400 font-normal">ID: #{{ $customer->id }}</div>
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        <div class="font-medium">{{ $customer->phone ?? 'N/A' }}</div>
                        <div class="text-[10px] text-gray-500">{{ $customer->email ?? '-' }}</div>
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        <span class="font-bold">{{ $customer->customerType->name ?? 'General' }}</span>
                    </td>
                    <td class="p-2 border-r border-gray-200 text-right font-mono font-bold">
                        ৳ {{ number_format($customer->balance, 2) }}
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        <div>
                            {{ implode(', ', array_filter([$customer->resolved_upazila, $customer->resolved_district, $customer->resolved_division])) ?: 'N/A' }}
                        </div>
                        <div class="text-[10px] text-gray-500">
                            @if($customer->resolved_pourashava) [{{ $customer->resolved_pourashava }}] @endif
                            @if($customer->resolved_ward) [Ward: {{ $customer->resolved_ward }}] @endif
                            {{ $customer->address ?? ($customer->globalAddress->street_address ?? '') }}
                        </div>
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center font-bold">
                        {{ $customer->total_orders_count }}
                    </td>
                    <td class="p-2 text-center uppercase font-bold text-[10px]">
                        {{ $customer->status }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-400">No customers available to export.</td>
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
