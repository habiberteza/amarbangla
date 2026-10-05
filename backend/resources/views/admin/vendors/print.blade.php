<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amar Bangla - Seller Management System Report</title>
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
            <a href="{{ route('admin.vendors.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                &larr; Back to Seller Management System
            </a>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                    <i class="fa fa-print"></i>
                    <span>Print / Save as PDF</span>
                </button>
            </div>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
            <div>
                <h1 class="text-xl font-extrabold text-gray-900 tracking-wide">AMAR BANGLA</h1>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-0.5">Seller Management System &bull; Registered Sellers Directory</p>
                <p class="text-[11px] text-gray-400 mt-1">Generated: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
            <div class="text-right">
                <div class="inline-block px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-bold text-emerald-800">
                    Total Sellers: {{ $users->count() }}
                </div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full text-left text-xs border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 font-bold uppercase border-b border-gray-200">
                <tr>
                    <th class="p-2.5 border-r border-gray-200 w-12 text-center">No</th>
                    <th class="p-2.5 border-r border-gray-200">Name</th>
                    <th class="p-2.5 border-r border-gray-200">Phone</th>
                    <th class="p-2.5 border-r border-gray-200">Area / Address</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">Shops</th>
                    <th class="p-2.5 border-r border-gray-200 text-center">Status</th>
                    <th class="p-2.5 text-center">Registered Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border-r border-gray-200 text-center font-bold font-mono">{{ $loop->iteration }}</td>
                    <td class="p-2 border-r border-gray-200 font-bold text-gray-900">
                        {{ $user->name }}
                        @if($user->agent)
                            <div class="text-[10px] text-indigo-600 font-normal">Agent: {{ $user->agent->name }}</div>
                        @endif
                    </td>
                    <td class="p-2 border-r border-gray-200 font-mono">
                        {{ $user->phone ?? 'N/A' }}
                    </td>
                    <td class="p-2 border-r border-gray-200">
                        @php
                            $loc = array_filter([$user->ward ? "Ward {$user->ward}" : null, $user->pourashava, $user->upazila, $user->district, $user->division]);
                        @endphp
                        <div>{{ !empty($loc) ? implode(', ', $loc) : 'All Bangladesh' }}</div>
                        @if($user->address)
                            <div class="text-[10px] text-gray-400">{{ $user->address }}</div>
                        @endif
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center font-bold">
                        {{ $user->shops_count ?? 0 }}
                    </td>
                    <td class="p-2 border-r border-gray-200 text-center">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $user->status ?? 'active' }}
                        </span>
                    </td>
                    <td class="p-2 text-center text-[11px] text-gray-500">
                        {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-400">No sellers available to display.</td>
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

    @if(($format ?? '') === 'pdf')
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
