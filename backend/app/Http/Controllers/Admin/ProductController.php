<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['shop', 'category']);

        // 1. Shop Filter
        if ($request->filled('shop_id')) {
            $query->where('sch_id', $request->shop_id);
        }

        // 2. Category Filter
        if ($request->filled('category_id')) {
            $query->where('prod_cat_id', $request->category_id);
        }

        // 3. Status Filter
        if ($request->filled('status')) {
            if ($request->status === 'active' || $request->status === '1') {
                $query->where('status', '1');
            } elseif ($request->status === 'inactive' || $request->status === '0') {
                $query->where('status', '0');
            }
        }

        // 4. Free Keyword Search (Name, Description)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest('prod_id')->paginate(25)->withQueryString();

        // Dropdowns for filtering
        $shops = Shop::where('status', '1')->orWhere('status', 'active')->orderBy('name')->get(['sch_id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        // Stats KPIs
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', '1')->count();
        $outOfStock = Product::where('quantity', '<=', 0)->count();
        $totalInventoryValue = Product::selectRaw('SUM(selling_price * quantity) as total_val')->value('total_val') ?? 0;

        return view('admin.products.index', compact(
            'products',
            'shops',
            'categories',
            'totalProducts',
            'activeProducts',
            'outOfStock',
            'totalInventoryValue'
        ));
    }

    public function create()
    {
        $shops = Shop::where('status', '1')->orWhere('status', 'active')->orderBy('name')->get(['sch_id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        return view('admin.products.create', compact('shops', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id'           => 'required|exists:shops,id',
            'category_id'       => 'nullable|exists:categories,id',
            'name'              => 'required|string|max:255',
            'item_code'         => 'nullable|string|max:100',
            'brand'             => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'fabrication'       => 'nullable|string',
            'gsm'               => 'nullable|string',
            'gender'            => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'discount_price'    => 'nullable|numeric|min:0',
            'stock_quantity'    => 'required|integer|min:0',
            'main_image'        => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . rand(1000, 9999);
        $validated['is_express'] = $request->has('is_express');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $product = Product::create($validated);

        if ($request->filled('main_image')) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $validated['main_image'],
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function edit($product)
    {
        $product = $product instanceof Product ? $product : Product::findOrFail($product);
        $shops = Shop::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(Request $request, $product)
    {
        $product = $product instanceof Product ? $product : Product::findOrFail($product);

        $validated = $request->validate([
            'shop_id'           => 'required|exists:shops,id',
            'category_id'       => 'nullable|exists:categories,id',
            'name'              => 'required|string|max:255',
            'item_code'         => 'nullable|string|max:100',
            'brand'             => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'fabrication'       => 'nullable|string',
            'gsm'               => 'nullable|string',
            'gender'            => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'discount_price'    => 'nullable|numeric|min:0',
            'stock_quantity'    => 'required|integer|min:0',
            'main_image'        => 'nullable|string',
            'photo'             => 'nullable|image|max:3072',
            'pic'               => 'nullable|image|max:3072',
        ]);

        $validated['is_express'] = $request->has('is_express');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // File upload handling
        $file = $request->file('photo') ?? $request->file('pic');
        if ($file) {
            $destDir = public_path('uploads/products');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'product_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $validated['main_image'] = 'uploads/products/' . $filename;
        }

        $product->update($validated);

        if (!empty($validated['main_image'])) {
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                ['image_url' => $validated['main_image'], 'sort_order' => 1]
            );
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function toggleStatus(Product $product)
    {
        $newStatus = !$product->is_active;
        $product->update(['is_active' => $newStatus]);
        $statusLabel = $newStatus ? 'activated' : 'deactivated';
        return back()->with('success', "Product \"{$product->name}\" was {$statusLabel}.");
    }

    public function updateStatus(Product $product)
    {
        return $this->toggleStatus($product);
    }

    public function destroy(Product $product)
    {
        $product->images()->delete();
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    // Multi-format export: CSV, Excel (xl), PDF/Print
    public function export(Request $request, $format = 'csv')
    {
        $query = Product::with(['shop', 'category']);

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' || $request->status === '1');
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        ini_set('memory_limit', '512M');

        if ($format === 'print' || $format === 'pdf') {
            $products = $query->latest()->limit(500)->get();
            return view('admin.products.print', compact('products'));
        }

        $products = $query->latest()->get();

        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'products_export_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($products) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($handle, [
                'No',
                'Product Name',
                'SKU / Item Code',
                'Shop / Outlet',
                'Category',
                'Brand',
                'Price (BDT)',
                'Discount Price (BDT)',
                'Stock Quantity',
                'Status',
                'Express Delivery',
                'Featured',
                'Main Image URL',
                'Created At'
            ]);

            $index = 1;
            foreach ($products as $p) {
                fputcsv($handle, [
                    $index++,
                    $p->name,
                    $p->item_code ?? 'N/A',
                    $p->shop->name ?? 'N/A',
                    $p->category->name ?? 'General',
                    $p->brand ?? 'N/A',
                    number_format($p->price, 2, '.', ''),
                    $p->discount_price ? number_format($p->discount_price, 2, '.', '') : 'N/A',
                    $p->stock_quantity,
                    $p->is_active ? 'Active' : 'Inactive',
                    $p->is_express ? 'Yes' : 'No',
                    $p->is_featured ? 'Yes' : 'No',
                    $p->main_image ?? '',
                    $p->created_at ? $p->created_at->format('Y-m-d H:i') : ''
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
