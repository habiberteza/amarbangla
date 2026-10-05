<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])->withCount('products')->orderBy('sort_order')->paginate(20);
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        Category::create($validated);

        return back()->with('success', 'Category created successfully');
    }

    public function edit($category)
    {
        $category = $category instanceof Category ? $category : Category::findOrFail($category);
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, $category)
    {
        $category = $category instanceof Category ? $category : Category::findOrFail($category);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'icon'       => 'nullable|string|max:50',
            'image'      => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image_file')) {
            $destDir = public_path('uploads/product_image');
            if (!\Illuminate\Support\Facades\File::isDirectory($destDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($destDir, 0755, true, true);
            }
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $validated['image'] = 'uploads/product_image/' . $filename;
        }

        unset($validated['image_file']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $category->update($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/demo_category') : route('admin.categories.index');
        return redirect($target)->with('success', 'Category updated successfully');
    }

    public function destroy($category)
    {
        $category = $category instanceof Category ? $category : Category::findOrFail($category);
        $category->delete();
        return back()->with('success', 'Category deleted successfully');
    }
}
