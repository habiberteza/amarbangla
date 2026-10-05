<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ShopCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ShopCategory::whereNull('deleted')->with('parent');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('shop_cat_id', $search);
            });
        }

        if ($request->filled('show_home')) {
            $query->where('show_home', $request->show_home);
        }

        $categories = $query->orderBy('shop_cat_id', 'asc')->paginate(20)->withQueryString();

        $parentCategories = ShopCategory::whereNull('deleted')
            ->where(function ($q) {
                $q->whereNull('parent_cat_id')->orWhere('parent_cat_id', 0);
            })
            ->orderBy('name')
            ->get();

        return view('admin.shop_categories.index', compact('categories', 'parentCategories'));
    }

    public function create()
    {
        $parentCategories = ShopCategory::whereNull('deleted')
            ->where(function ($q) {
                $q->whereNull('parent_cat_id')->orWhere('parent_cat_id', 0);
            })
            ->orderBy('name')
            ->get();

        return view('admin.shop_categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'parent_cat_id' => 'nullable|integer',
            'title'         => 'nullable|string|max:255',
            'show_home'     => 'nullable',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = [
            'name'          => $validated['name'],
            'parent_cat_id' => !empty($validated['parent_cat_id']) ? (int)$validated['parent_cat_id'] : 0,
            'title'         => $validated['title'] ?? null,
            'show_home'     => ($request->input('show_home') == '1' || $request->has('show_home_checkbox')) ? '1' : '0',
            'createdBy'     => auth()->id() ?? 1,
            'createdDtm'    => now(),
        ];

        if ($request->hasFile('image')) {
            $destDir = public_path('uploads/schools');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $file = $request->file('image');
            $filename = 'shop_cat_' . time() . '_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $data['image'] = $filename;
        }

        ShopCategory::create($data);

        return redirect()->route('admin.shop_categories.index')
            ->with('success', 'Shop category created successfully.');
    }

    public function edit($id)
    {
        $shopCategory = ShopCategory::whereNull('deleted')->where('shop_cat_id', $id)->firstOrFail();

        $parentCategories = ShopCategory::whereNull('deleted')
            ->where(function ($q) {
                $q->whereNull('parent_cat_id')->orWhere('parent_cat_id', 0);
            })
            ->where('shop_cat_id', '!=', $id)
            ->orderBy('name')
            ->get();

        return view('admin.shop_categories.edit', compact('shopCategory', 'parentCategories'));
    }

    public function update(Request $request, $id)
    {
        $shopCategory = ShopCategory::whereNull('deleted')->where('shop_cat_id', $id)->firstOrFail();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'parent_cat_id' => 'nullable|integer',
            'title'         => 'nullable|string|max:255',
            'show_home'     => 'nullable',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = [
            'name'          => $validated['name'],
            'parent_cat_id' => !empty($validated['parent_cat_id']) ? (int)$validated['parent_cat_id'] : 0,
            'title'         => $validated['title'] ?? null,
            'show_home'     => ($request->input('show_home') == '1' || $request->has('show_home_checkbox')) ? '1' : '0',
            'updatedBy'     => auth()->id() ?? 1,
            'updatedDtm'    => now(),
        ];

        if ($request->hasFile('image')) {
            $destDir = public_path('uploads/schools');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }

            // Remove old image if exists
            if (!empty($shopCategory->image) && File::exists($destDir . '/' . $shopCategory->image)) {
                File::delete($destDir . '/' . $shopCategory->image);
            }

            $file = $request->file('image');
            $filename = 'shop_cat_' . time() . '_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $data['image'] = $filename;
        }

        $shopCategory->update($data);

        return redirect()->route('admin.shop_categories.index')
            ->with('success', "Shop category '{$shopCategory->name}' updated successfully.");
    }

    /**
     * Legacy CI4 update action for Tab 1: Registration Info
     */
    public function updateLegacy(Request $request)
    {
        $id = $request->input('shop_cat_id');
        $shopCategory = ShopCategory::where('shop_cat_id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shopCategory->update([
            'name'          => $request->input('name'),
            'parent_cat_id' => !empty($request->input('parent_cat_id')) ? (int)$request->input('parent_cat_id') : 0,
            'updatedBy'     => auth()->id() ?? 1,
            'updatedDtm'    => now(),
        ]);

        return redirect()->back()->with('success', 'Data update successfully (Registration Info)');
    }

    /**
     * Legacy CI4 update action for Tab 2: Others (Show Home, Title, Image)
     */
    public function othersActionLegacy(Request $request)
    {
        $id = $request->input('shop_cat_id');
        $shopCategory = ShopCategory::where('shop_cat_id', $id)->firstOrFail();

        $request->validate([
            'show_home' => 'required',
            'title'     => 'nullable|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = [
            'show_home'  => $request->input('show_home') == '1' ? '1' : '0',
            'title'      => $request->input('title'),
            'updatedBy'  => auth()->id() ?? 1,
            'updatedDtm' => now(),
        ];

        if ($request->hasFile('image')) {
            $destDir = public_path('uploads/schools');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }

            if (!empty($shopCategory->image) && File::exists($destDir . '/' . $shopCategory->image)) {
                File::delete($destDir . '/' . $shopCategory->image);
            }

            $file = $request->file('image');
            $filename = 'shop_cat_' . time() . '_' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $data['image'] = $filename;
        }

        $shopCategory->update($data);

        return redirect()->back()->with('success', 'Data update successfully (Others Info)');
    }

    public function destroy($id)
    {
        $shopCategory = ShopCategory::where('shop_cat_id', $id)->firstOrFail();
        $shopCategory->update([
            'deleted'     => auth()->id() ?? 1,
            'deletedRole' => 1,
        ]);

        return back()->with('success', 'Shop category deleted successfully.');
    }
}
