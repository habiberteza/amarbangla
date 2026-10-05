@extends('admin.layout')

@section('title', 'Calling Requests - Amar Bangla Super Admin')
@section('page-title', 'Customer Phone Order & Callback Requests (super_admin/calling_request)')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-check text-emerald-600 text-sm"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <!-- Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-semibold">
        @php
            $currentStatus = request('status', '');
            $tabs = [
                ''          => ['label' => 'All Requests', 'count' => $statusCounts['all'] ?? 0],
                'pending'   => ['label' => 'Pending Review', 'count' => $statusCounts['pending'] ?? 0],
                'called'    => ['label' => 'Called Customer', 'count' => $statusCounts['called'] ?? 0],
                'completed' => ['label' => 'Completed / Converted', 'count' => $statusCounts['completed'] ?? 0],
                'cancelled' => ['label' => 'Cancelled', 'count' => $statusCounts['cancelled'] ?? 0],
            ];
        @endphp

        @foreach($tabs as $key => $tab)
            @php
                $isActive = $currentStatus === $key;
                $url = route('admin.calling_requests.index', array_merge(request()->except(['status', 'page']), $key !== '' ? ['status' => $key] : []));
            @endphp
            <a href="{{ $url }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl transition whitespace-nowrap shadow-sm border
               {{ $isActive ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200' }}">
                <span>{{ $tab['label'] }}</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-700' }}">
                    {{ $tab['count'] }}
                </span>
            </a>
        @endforeach
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.calling_requests.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-80">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa fa-search text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer, phone, product..."
                       class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>
            <button type="submit" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow">
                Search
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Customer</th>
                        <th class="px-5 py-3.5">Phone (Call)</th>
                        <th class="px-5 py-3.5">Product Inquiry</th>
                        <th class="px-5 py-3.5">Customer Message</th>
                        <th class="px-5 py-3.5">Admin Action & Notes</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 font-bold text-gray-900">{{ $req->customer_name }}</td>
                        <td class="px-5 py-4">
                            <a href="tel:{{ $req->phone }}" class="text-blue-600 hover:underline font-mono font-bold flex items-center gap-1.5">
                                <i class="fa fa-phone text-xs"></i>
                                <span>{{ $req->phone }}</span>
                            </a>
                            <span class="text-[10px] text-gray-400 block mt-0.5">{{ $req->created_at?->diffForHumans() ?? 'Recently' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($req->product && $req->product->exists)
                                <a href="{{ route('admin.products.edit', $req->product) }}" class="font-semibold text-blue-600 hover:underline">
                                    {{ $req->product->name }}
                                </a>
                            @else
                                <span class="text-gray-700 font-medium">{{ $req->product_name ?? 'General Callback' }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-600 max-w-xs">{{ $req->notes ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-500 text-[11px] max-w-xs">{{ $req->admin_notes ?? 'No notes' }}</td>
                        <td class="px-5 py-4">
                            <span class="capitalize text-[10px] font-bold px-2.5 py-1 rounded
                                {{ $req->status === 'completed' ? 'bg-emerald-100 text-emerald-800' :
                                   ($req->status === 'called' ? 'bg-blue-100 text-blue-800' :
                                   ($req->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800')) }}">
                                {{ $req->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button" onclick="openStatusModal({{ json_encode($req) }})"
                                        class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition">
                                    Update
                                </button>
                                <form action="{{ route('admin.calling_requests.destroy', $req) }}" method="POST"
                                      onsubmit="return confirm('Delete this request?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-400 hover:text-rose-700 transition" title="Delete">
                                        <i class="fa fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">No calling requests recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-sm">Update Calling Request</h3>
            <button type="button" onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-700 text-sm">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form id="statusForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                <select name="status" id="modal_status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="pending">Pending</option>
                    <option value="called">Called Customer</option>
                    <option value="completed">Completed / Order Placed</option>
                    <option value="cancelled">Cancelled / Unreachable</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Admin Notes / Outcome</label>
                <textarea name="admin_notes" id="modal_notes" rows="3" placeholder="Customer spoke with agent, confirmed order..."
                          class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow">
                    Save Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openStatusModal(req) {
        document.getElementById('statusForm').action = '/super_admin/calling_request/' + req.id + '/status';
        document.getElementById('modal_status').value = req.status;
        document.getElementById('modal_notes').value = req.admin_notes || '';
        document.getElementById('statusModal').classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }
</script>
@endsection
