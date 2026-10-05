<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductColor;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
    public function index()
    {
        $colors = ProductColor::latest()->get();
        return view('admin.product_colors.index', compact('colors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ProductColor::create($validated);

        return back()->with('success', 'Product Color added successfully.');
    }

    public function edit($productColor)
    {
        $productColor = $productColor instanceof ProductColor ? $productColor : ProductColor::findOrFail($productColor);
        return view('admin.product_colors.edit', compact('productColor'));
    }

    public function update(Request $request, $productColor)
    {
        $productColor = $productColor instanceof ProductColor ? $productColor : ProductColor::findOrFail($productColor);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $productColor->update($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/product_color') : route('admin.product_colors.index');
        return redirect($target)->with('success', 'Product Color updated successfully.');
    }

    public function destroy($productColor)
    {
        $productColor = $productColor instanceof ProductColor ? $productColor : ProductColor::findOrFail($productColor);
        $productColor->delete();
        return back()->with('success', 'Product Color deleted successfully.');
    }
}
