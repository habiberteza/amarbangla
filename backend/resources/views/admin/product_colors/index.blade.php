@extends('admin.layout')

@section('title', 'Product Colors - Amar Bangla Super Admin')
@section('page-title', 'Product Color Management (super_admin/product_color)')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Add Color Form -->
        <div class="lg:col-span-1">
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm sticky top-24">
                <h3 class="text-sm font-bold text-gray-800 mb-1 flex items-center gap-2">
                    <i class="fa fa-palette text-blue-600"></i>
                    <span>Add New Color</span>
                </h3>
                <p class="text-xs text-gray-500 mb-4">Define product variants and palette swatches.</p>

                <form action="{{ route('admin.product_color.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Color Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. Navy Blue, Crimson, Gold"
                               class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Color Hex Code <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="color" id="picker_add" value="#3B82F6" oninput="document.getElementById('code_add').value = this.value"
                                   class="w-10 h-10 p-0.5 rounded-xl border border-gray-200 cursor-pointer bg-white">
                            <input type="text" id="code_add" name="code" value="#3B82F6" required placeholder="#3B82F6" oninput="document.getElementById('picker_add').value = this.value"
                                   class="flex-1 py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-800 uppercase focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>
                    </div>

                    <div class="pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-semibold text-gray-700">Active variant</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa fa-plus"></i>
                        <span>Save Color</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Colors List Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Configured Swatches</h4>
                        <p class="text-[11px] text-gray-500">{{ $colors->count() }} colors registered in system</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Swatch</th>
                                <th class="py-3 px-4">Color Name</th>
                                <th class="py-3 px-4">Hex Code</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($colors as $color)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg border border-gray-300 shadow-inner inline-block"
                                              style="background-color: {{ $color->code }};"></span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-bold text-gray-800">
                                    {{ $color->name }}
                                </td>
                                <td class="py-3 px-4 font-mono font-semibold text-gray-600">
                                    {{ strtoupper($color->code) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($color->is_active)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right space-x-1">
                                    <button type="button"
                                            onclick="openEditColorModal({{ json_encode($color) }})"
                                            class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition"
                                            title="Edit Color">
                                        <i class="fa fa-pen text-[11px]"></i>
                                    </button>

                                    <form action="{{ route('admin.product_color.destroy', $color->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this color?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 hover:bg-rose-50 text-gray-600 hover:text-rose-600 transition" title="Delete Color">
                                            <i class="fa fa-trash-alt text-[11px]"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">
                                    No colors configured yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Edit Color Modal -->
<div id="editColorModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-gray-200">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-800">Edit Product Color</h3>
            <button onclick="document.getElementById('editColorModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <form id="editColorForm" method="POST" class="space-y-4 mt-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Color Name <span class="text-rose-500">*</span></label>
                <input type="text" id="edit_name" name="name" required
                       class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Color Hex Code <span class="text-rose-500">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="color" id="picker_edit" oninput="document.getElementById('edit_code').value = this.value"
                           class="w-10 h-10 p-0.5 rounded-xl border border-gray-200 cursor-pointer bg-white">
                    <input type="text" id="edit_code" name="code" required oninput="document.getElementById('picker_edit').value = this.value"
                           class="flex-1 py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-800 uppercase focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-semibold text-gray-700">Active variant</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('editColorModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                    Update Color
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditColorModal(color) {
    document.getElementById('editColorForm').action = '/super_admin/product_color/' + color.id;
    document.getElementById('edit_name').value = color.name;
    document.getElementById('edit_code').value = color.code;
    document.getElementById('picker_edit').value = color.code;
    document.getElementById('edit_is_active').checked = !!color.is_active;

    document.getElementById('editColorModal').classList.remove('hidden');
}
</script>
@endsection
