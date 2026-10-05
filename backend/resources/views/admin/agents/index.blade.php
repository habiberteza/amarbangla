@extends('admin.layout')

@section('title', 'Agents Management - Amar Bangla Super Admin')
@section('page-title', 'Territory & Union Field Agents (super_admin/agent)')

@section('content')
<div class="space-y-5">

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

    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.agents.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa fa-search text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search agent name, phone, district..."
                       class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <select name="status" onchange="this.form.submit()" class="py-2 px-3 bg-gray-50 border border-gray-300 rounded-lg text-xs text-gray-700 focus:ring-2 focus:ring-blue-600">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.agents.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.agents.create') }}" class="w-full md:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow transition flex items-center justify-center gap-2">
            <i class="fa fa-plus"></i>
            <span>Register Field Agent</span>
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5">Agent Details</th>
                        <th class="px-5 py-3.5">Contact</th>
                        <th class="px-5 py-3.5">Assigned Territory</th>
                        <th class="px-5 py-3.5">Commission Rate</th>
                        <th class="px-5 py-3.5">Account Balance</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="font-bold text-gray-900 text-sm">{{ $agent->name }}</div>
                            @if($agent->nid_number)
                                <div class="text-[11px] text-gray-400">NID: {{ $agent->nid_number }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <a href="tel:{{ $agent->phone }}" class="text-xs font-semibold text-blue-600 hover:underline flex items-center gap-1 font-mono">
                                <i class="fa fa-phone text-[10px]"></i>
                                <span>{{ $agent->phone }}</span>
                            </a>
                            @if($agent->email)
                                <div class="text-[11px] text-gray-400">{{ $agent->email }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-semibold text-gray-800">{{ $agent->upazila ?? $agent->district }}</div>
                            <div class="text-[11px] text-gray-400">{{ $agent->district }}, {{ $agent->division }} ({{ $agent->union_ward ?? 'Main' }})</div>
                        </td>
                        <td class="px-5 py-4 font-bold text-emerald-600">
                            {{ $agent->commission_rate }}%
                        </td>
                        <td class="px-5 py-4 font-bold text-gray-900">
                            ৳{{ number_format($agent->balance, 2) }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="capitalize text-[10px] font-bold px-2.5 py-1 rounded
                                {{ $agent->status === 'active' ? 'bg-emerald-100 text-emerald-800' :
                                   ($agent->status === 'suspended' ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ $agent->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.agents.edit', $agent) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i class="fa fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST"
                                      onsubmit="return confirm('Delete agent {{ $agent->name }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                        <i class="fa fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">No agents registered.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agents->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $agents->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
