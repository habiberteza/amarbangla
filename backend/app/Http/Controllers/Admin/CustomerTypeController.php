<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerTypeController extends Controller
{
    public function index()
    {
        $types = CustomerType::withCount('customers')->orderBy('cus_type_id', 'desc')->get();
        return view('admin.customer_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'description'         => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['discount_percentage'] = $validated['discount_percentage'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        CustomerType::create($validated);

        return back()->with('success', 'Customer Type created successfully.');
    }

    public function update(Request $request, CustomerType $customerType)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'description'         => 'nullable|string|max:1000',
        ]);

        $validated['discount_percentage'] = $validated['discount_percentage'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $customerType->update($validated);

        return back()->with('success', 'Customer Type updated successfully.');
    }

    public function destroy(CustomerType $customerType)
    {
        if ($customerType->customers()->count() > 0) {
            return back()->with('error', 'Cannot delete this Customer Type because customers are assigned to it.');
        }

        $customerType->delete();

        return back()->with('success', 'Customer Type deleted successfully.');
    }
}
