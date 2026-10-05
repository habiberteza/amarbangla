@extends('admin.layout')

@section('title', 'Customer Types - Amar Bangla Super Admin')
@section('page-title', 'Customer Types Management (super_admin/customer_type)')

@section('content')
<div class="space-y-6">

    <!-- Flash message alerts -->
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

    @if(session('error'))
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa fa-circle-exclamation text-rose-600 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-sm">
            <i class="fa fa-times"></i>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Create Form -->
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm h-fit">
            <h3 class="font-bold text-gray-900 text-sm mb-4 flex items-center gap-2">
                <i class="fa fa-plus-circle text-blue-600"></i>
                <span>Add Customer Type</span>
            </h3>

            <form action="{{ route('admin.customer_types.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Type Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g. Wholesale, VIP, Corporate"
                           class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('name')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Discount Percentage (%)</label>
                    <div class="relative">
                        <input type="number" step="0.01" min="0" max="100" name="discount_percentage" value="0.00"
                               class="w-full py-2 px-3 pr-8 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <span class="absolute right-3 top-2 text-xs text-gray-400 font-bold">%</span>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Default discount automatically applied to this customer tier.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Brief note about benefits or qualification criteria..."
                              class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="text-xs font-semibold text-gray-700 cursor-pointer">Active Status</label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center justify-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Create Customer Type</span>
                </button>
            </form>
        </div>

        <!-- Right: Table List -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Configured Customer Types</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Manage customer pricing categories and wholesale classifications</p>
                </div>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold text-xs rounded-lg">
                    {{ $types->count() }} Tiers
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3.5">Type Name</th>
                            <th class="px-5 py-3.5">Discount Rate</th>
                            <th class="px-5 py-3.5">Assigned Customers</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($types as $type)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-4">
                                <div class="font-bold text-gray-900 text-sm">{{ $type->name }}</div>
                                @if($type->description)
                                    <div class="text-[11px] text-gray-400 max-w-xs mt-0.5">{{ $type->description }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-emerald-600 text-xs px-2 py-0.5 bg-emerald-50 rounded-md">
                                    {{ $type->discount_percentage }}% Off
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.customers.index', ['customer_type_id' => $type->id]) }}" class="inline-flex items-center gap-1 font-semibold text-blue-600 hover:underline">
                                    <i class="fa fa-users text-[10px]"></i>
                                    <span>{{ $type->customers_count }} customers</span>
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $type->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $type->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick="openEditModal({{ json_encode($type) }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i class="fa fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('admin.customer_types.destroy', $type) }}" method="POST"
                                          onsubmit="return confirm('Delete customer type {{ $type->name }}?');" class="inline">
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
                            <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                No customer types created yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-sm">Edit Customer Type</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-700 text-sm">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Type Name</label>
                <input type="text" name="name" id="modal_name" required
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Discount Percentage (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="discount_percentage" id="modal_discount"
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" id="modal_description" rows="3"
                          class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="modal_active" value="1"
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="modal_active" class="text-xs font-semibold text-gray-700 cursor-pointer">Active Status</label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(type) {
        document.getElementById('editForm').action = '/super_admin/customer_type/' + type.id;
        document.getElementById('modal_name').value = type.name;
        document.getElementById('modal_discount').value = type.discount_percentage;
        document.getElementById('modal_description').value = type.description || '';
        document.getElementById('modal_active').checked = Boolean(type.is_active);
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
