<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    /**
     * Display a listing of shop licenses.
     * URL: /super_admin/license
     */
    public function index(Request $request)
    {
        $query = License::with('shop');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('license_type')) {
            $query->where('license_type', $request->license_type);
        }

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->filled('start_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('start_date', '>=', $request->start_date)
                  ->orWhereDate('issue_date', '>=', $request->start_date);
            });
        }

        if ($request->filled('end_date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('end_date', '<=', $request->end_date)
                  ->orWhereDate('expiry_date', '<=', $request->end_date);
            });
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('license_key', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('shop_name', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhereHas('shop', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Summary counts
        $totalCount    = License::count();
        $approvedCount = License::where('status', 'approved')->count();
        $pendingCount  = License::where('status', 'pending')->count();
        $expiredCount  = License::whereIn('status', ['expired', 'rejected'])->count();

        $licenses = $query->latest('id')->paginate(25)->withQueryString();
        $shops = Shop::orderBy('name')->get();

        return view('admin.licenses.index', compact(
            'licenses',
            'shops',
            'totalCount',
            'approvedCount',
            'pendingCount',
            'expiredCount'
        ));
    }

    /**
     * Show the form for creating a new license.
     * URL: /super_admin/license_create or /super_admin/license/create
     */
    public function create()
    {
        $shops = Shop::orderBy('name')->get();
        // Generate a suggested clean license key: AB-LIC-YYYY-XXXXX
        $defaultKey = 'AB-LIC-' . date('Y') . '-' . strtoupper(Str::random(6));

        return view('admin.licenses.create', compact('shops', 'defaultKey'));
    }

    /**
     * Store a newly created license in storage.
     * URL: POST /super_admin/license or POST /super_admin/license_create
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id'        => 'nullable|exists:shops,sch_id',
            'shop_name'      => 'nullable|string|max:191',
            'vendor_name'    => 'nullable|string|max:191',
            'license_type'   => 'nullable|string|max:100',
            'license_key'    => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'issue_date'     => 'nullable|date',
            'expiry_date'    => 'nullable|date',
            'status'         => 'nullable|string|max:50',
            'notes'          => 'nullable|string',
            'document_file'  => 'nullable|string|max:500',
            'document'       => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
        ]);

        // Key synchronization
        $key = $validated['license_key'] ?? ($validated['license_number'] ?? null);
        if (!$key) {
            $key = 'AB-LIC-' . date('Y') . '-' . strtoupper(Str::random(6));
        }
        $validated['license_key'] = $key;
        $validated['license_number'] = $key;

        // Dates synchronization
        $startDate = $validated['start_date'] ?? ($validated['issue_date'] ?? now()->toDateString());
        $endDate   = $validated['end_date'] ?? ($validated['expiry_date'] ?? null);
        $validated['start_date'] = $startDate;
        $validated['issue_date'] = $startDate;
        $validated['end_date']   = $endDate;
        $validated['expiry_date']= $endDate;

        // Status default
        $status = strtolower($validated['status'] ?? 'approved');
        if ($status === 'active') {
            $status = 'approved';
        }
        if (!in_array($status, ['approved', 'pending', 'rejected', 'expired'])) {
            $status = 'approved';
        }
        $validated['status'] = $status;

        // License type default
        $validated['license_type'] = $validated['license_type'] ?? 'Trade License';

        // Auto-fill shop name & vendor name from selected shop if available
        if (!empty($validated['shop_id'])) {
            $shop = Shop::find($validated['shop_id']);
            if ($shop) {
                if (empty($validated['shop_name'])) {
                    $validated['shop_name'] = $shop->name;
                }
                if (empty($validated['vendor_name'])) {
                    $validated['vendor_name'] = $shop->vendor ? $shop->vendor->name : ($shop->contact_person ?? null);
                }
            }
        }

        // File upload handling
        if ($request->hasFile('document')) {
            $destDir = public_path('uploads/licenses');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $file = $request->file('document');
            $filename = 'lic_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $validated['document_file'] = 'uploads/licenses/' . $filename;
        }

        unset($validated['document']);

        License::create($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/license') : route('admin.licenses.index');
        return redirect($target)->with('success', "License [{$key}] for shop \"{$validated['shop_name']}\" created successfully.");
    }

    /**
     * Show the form for editing the specified license.
     * URL: /super_admin/license_update/{id} or /super_admin/license/{license}/edit
     */
    public function edit($id)
    {
        $license = $id instanceof License ? $id : License::findOrFail($id);
        $shops = Shop::orderBy('name')->get();

        return view('admin.licenses.edit', compact('license', 'shops'));
    }

    /**
     * Update the specified license in storage.
     * URL: PUT/POST /super_admin/license_update/{id} or PUT /super_admin/license/{license}
     */
    public function update(Request $request, $id)
    {
        $license = $id instanceof License ? $id : License::findOrFail($id);

        $validated = $request->validate([
            'shop_id'        => 'nullable|exists:shops,sch_id',
            'shop_name'      => 'nullable|string|max:191',
            'vendor_name'    => 'nullable|string|max:191',
            'license_type'   => 'nullable|string|max:100',
            'license_key'    => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:100',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
            'issue_date'     => 'nullable|date',
            'expiry_date'    => 'nullable|date',
            'status'         => 'nullable|string|max:50',
            'notes'          => 'nullable|string',
            'document_file'  => 'nullable|string|max:500',
            'document'       => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
        ]);

        $key = $validated['license_key'] ?? ($validated['license_number'] ?? $license->license_key);
        $validated['license_key'] = $key;
        $validated['license_number'] = $key;

        $startDate = $validated['start_date'] ?? ($validated['issue_date'] ?? $license->start_date);
        $endDate   = $validated['end_date'] ?? ($validated['expiry_date'] ?? $license->end_date);
        $validated['start_date'] = $startDate;
        $validated['issue_date'] = $startDate;
        $validated['end_date']   = $endDate;
        $validated['expiry_date']= $endDate;

        if (isset($validated['status'])) {
            $status = strtolower($validated['status']);
            if ($status === 'active') $status = 'approved';
            if (in_array($status, ['approved', 'pending', 'rejected', 'expired'])) {
                $validated['status'] = $status;
            }
        }

        if (!empty($validated['shop_id'])) {
            $shop = Shop::find($validated['shop_id']);
            if ($shop && empty($validated['shop_name'])) {
                $validated['shop_name'] = $shop->name;
            }
        }

        if ($request->hasFile('document')) {
            $destDir = public_path('uploads/licenses');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $file = $request->file('document');
            $filename = 'lic_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $validated['document_file'] = 'uploads/licenses/' . $filename;
        }

        unset($validated['document']);

        $license->update($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/license') : route('admin.licenses.index');
        return redirect($target)->with('success', "License [{$license->license_key}] updated successfully.");
    }

    /**
     * Toggle or update verification status.
     */
    public function updateStatus(Request $request, $id)
    {
        $license = $id instanceof License ? $id : License::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,pending,rejected,expired',
        ]);

        $license->update($validated);

        return back()->with('success', "License verification status updated to {$license->status}.");
    }

    /**
     * Remove the specified license from storage.
     */
    public function destroy($id)
    {
        $license = $id instanceof License ? $id : License::findOrFail($id);
        $key = $license->license_key;
        $license->delete();

        return redirect()->route('admin.licenses.index')->with('success', "License [{$key}] deleted successfully.");
    }

    public function destroyLegacy($id)
    {
        return $this->destroy($id);
    }

    /**
     * Export licenses: copy, csv, xl (Excel), pdf, print
     */
    public function export(Request $request, $format = 'csv')
    {
        $query = License::with('shop');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('license_type')) {
            $query->where('license_type', $request->license_type);
        }

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('license_key', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('shop_name', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        $licenses = $query->latest('id')->get();

        // Print / PDF format view
        if ($format === 'print' || $format === 'pdf') {
            return view('admin.licenses.print', compact('licenses', 'format'));
        }

        // CSV or Excel (xl) format
        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'licenses_export_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($licenses) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            // Headers exactly matching user criteria: No, Shop Name, License Key, Start Date, End Date, Status, License Type, Notes
            fputcsv($handle, [
                'No',
                'Shop Name',
                'License Key',
                'Start Date',
                'End Date',
                'Status',
                'License Type',
                'Vendor Name',
                'Document',
                'Notes'
            ]);

            $index = 1;
            foreach ($licenses as $lic) {
                $shopName = $lic->shop_name ?: ($lic->shop ? $lic->shop->name : 'N/A');
                $licKey = $lic->license_key ?: $lic->license_number;
                $startDate = $lic->start_date ? $lic->start_date->format('Y-m-d') : ($lic->issue_date ? $lic->issue_date->format('Y-m-d') : 'N/A');
                $endDate = $lic->end_date ? $lic->end_date->format('Y-m-d') : ($lic->expiry_date ? $lic->expiry_date->format('Y-m-d') : 'Lifetime / Open');

                fputcsv($handle, [
                    $index++,
                    $shopName,
                    $licKey,
                    $startDate,
                    $endDate,
                    ucfirst($lic->status),
                    $lic->license_type,
                    $lic->vendor_name ?: 'N/A',
                    $lic->document_file ?: 'N/A',
                    $lic->notes ?: ''
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Print license report or single license certificate
     */
    public function print(Request $request, $id = null)
    {
        if ($id) {
            $licenses = License::with('shop')->where('id', $id)->get();
        } else {
            $licenses = License::with('shop')->orderBy('id', 'desc')->get();
        }

        return view('admin.licenses.print', compact('licenses'));
    }
}
