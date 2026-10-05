@extends('admin.layout')

@section('title', 'Categories - Amar Bangla Super Admin')
@section('page-title', 'Category & Sub-Category Hierarchy')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Category Form (1 col) -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-bold text-gray-800 text-sm mb-4">Add New Category</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Category Name *</label>
                <input type="text" name="name" required placeholder="e.g. FOOD & GROCERY"
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Parent Category</label>
                <select name="parent_id" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="">None (Top-Level Category)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Leave blank to make it a primary header menu category.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Icon Name (FontAwesome or Lucide)</label>
                <input type="text" name="icon" placeholder="e.g. utensils, smartphone, shirt"
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Banner / Image URL</label>
                <input type="text" name="image" placeholder="https://..."
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="0"
                       class="w-full px-3.5 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
            </div>

            <div class="flex items-center gap-4 pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-medium text-gray-700">Featured</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-xs font-medium text-gray-700">Active</span>
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-lg shadow transition">
                Create Category
            </button>
        </form>
    </div>

    <!-- Category Hierarchy List (2 cols) -->
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-sm">All Categories ({{ $categories->total() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-500 uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Parent</th>
                        <th class="px-5 py-3">Slug</th>
                        <th class="px-5 py-3">Products</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5 font-bold text-gray-900 flex items-center gap-2">
                            @if($cat->parent_id)
                                <span class="text-gray-300 ml-2">&bull;</span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            @endif
                            <span>{{ $cat->name }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500">
                            {{ $cat->parent->name ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5 font-mono text-[11px] text-gray-400">
                            {{ $cat->slug }}
                        </td>
                        <td class="px-5 py-3.5 font-semibold text-gray-700">
                            {{ $cat->products_count }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $cat->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $cat->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-800">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
