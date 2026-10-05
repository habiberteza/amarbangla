<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\GlobalAddress;
use App\Models\Ledger;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    /**
     * Display seller listing with geographic filters, keyword search, and export options.
     * URL: /super_admin/vendors and /super_admin/sellers
     */
    public function index(Request $request)
    {
        $query = User::query()->withCount('shops')->with('agent');

        // By default, filter to vendors/sellers unless a specific role is requested
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } else {
            $query->whereIn('role', ['vendor', 'seller']);
        }

        // 1. Division Filter
        if ($request->filled('division')) {
            $divisions = is_array($request->division) ? $request->division : explode(',', $request->division);
            $divisions = array_filter(array_map('trim', $divisions));
            if (!empty($divisions)) {
                $query->whereIn('division', $divisions);
            }
        }

        // 2. District Filter
        if ($request->filled('district')) {
            $districts = is_array($request->district) ? $request->district : explode(',', $request->district);
            $districts = array_filter(array_map('trim', $districts));
            if (!empty($districts)) {
                $query->whereIn('district', $districts);
            }
        }

        // 3. Upazila Filter
        if ($request->filled('upazila')) {
            $upazilas = is_array($request->upazila) ? $request->upazila : explode(',', $request->upazila);
            $upazilas = array_filter(array_map('trim', $upazilas));
            if (!empty($upazilas)) {
                $query->whereIn('upazila', $upazilas);
            }
        }

        // 4. Pourashava/Union Filter
        if ($request->filled('pourashava')) {
            $pourashavas = is_array($request->pourashava) ? $request->pourashava : explode(',', $request->pourashava);
            $pourashavas = array_filter(array_map('trim', $pourashavas));
            if (!empty($pourashavas)) {
                $query->whereIn('pourashava', $pourashavas);
            }
        }

        // 5. Ward Filter
        if ($request->filled('ward')) {
            $wards = is_array($request->ward) ? $request->ward : explode(',', $request->ward);
            $wards = array_filter(array_map('trim', $wards));
            if (!empty($wards)) {
                $query->whereIn('ward', $wards);
            }
        }

        // 6. Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 7. Free Keyword Search (Name, Phone, Email, Address, Father Name, Mother Name)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%")
                  ->orWhere('pourashava', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('user_id')->paginate(25)->withQueryString();

        // Address hierarchy datasets for cascading dropdowns
        $divisions = GlobalAddress::select('division')->distinct()->orderBy('division')->pluck('division');

        $districtQuery = GlobalAddress::select('district')->distinct()->orderBy('district');
        if ($request->filled('division')) {
            $selDivs = is_array($request->division) ? $request->division : explode(',', $request->division);
            $districtQuery->whereIn('division', array_filter(array_map('trim', $selDivs)));
        }
        $districts = $districtQuery->pluck('district');

        $upazilaQuery = GlobalAddress::select('upazila')->distinct()->orderBy('upazila');
        if ($request->filled('district')) {
            $selDists = is_array($request->district) ? $request->district : explode(',', $request->district);
            $upazilaQuery->whereIn('district', array_filter(array_map('trim', $selDists)));
        }
        $upazilas = $upazilaQuery->pluck('upazila');

        $pourashavaQuery = GlobalAddress::whereNotNull('pourashava')->where('pourashava', '!=', '')
            ->select('pourashava')->distinct()->orderBy('pourashava');
        if ($request->filled('upazila')) {
            $selUps = is_array($request->upazila) ? $request->upazila : explode(',', $request->upazila);
            $pourashavaQuery->whereIn('upazila', array_filter(array_map('trim', $selUps)));
        }
        $pourashavas = $pourashavaQuery->pluck('pourashava');

        $wardQuery = GlobalAddress::whereNotNull('ward')->where('ward', '!=', '')
            ->select('ward')->distinct()->orderBy('ward');
        if ($request->filled('pourashava')) {
            $selPours = is_array($request->pourashava) ? $request->pourashava : explode(',', $request->pourashava);
            $wardQuery->whereIn('pourashava', array_filter(array_map('trim', $selPours)));
        }
        $wards = $wardQuery->pluck('ward');

        // Summary Statistics
        $baseRoleQuery = User::whereIn('role', ['vendor', 'seller']);
        $totalCount     = (clone $baseRoleQuery)->count();
        $activeCount    = (clone $baseRoleQuery)->where('status', 'active')->count();
        $suspendedCount = (clone $baseRoleQuery)->whereIn('status', ['suspended', 'inactive'])->count();
        $newThisMonth   = (clone $baseRoleQuery)->where('created_at', '>=', now()->startOfMonth())->count();

        return view('admin.vendors.index', compact(
            'users',
            'divisions',
            'districts',
            'upazilas',
            'pourashavas',
            'wards',
            'totalCount',
            'activeCount',
            'suspendedCount',
            'newThisMonth'
        ));
    }

    /**
     * Show seller create page.
     * URL: /super_admin/sellers_create and /super_admin/vendors/create
     */
    public function create()
    {
        return view('admin.vendors.create');
    }

    /**
     * Store new seller account.
     * URL: POST /super_admin/sellers_create and POST /super_admin/vendors
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'phone'                 => 'required|string|max:20',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
            'status'                => 'nullable|string|max:50',
            'email'                 => 'nullable|email|max:191',
        ]);

        // Phone normalization
        $phone = trim($validated['phone']);

        // Check if phone is already registered
        if (User::where('phone', $phone)->exists()) {
            return back()->withInput()->withErrors(['phone' => 'This phone number is already registered.']);
        }

        // Generate email if not provided
        $email = $request->email ? trim($request->email) : ($phone . '@seller.amarbangla.com');
        if (User::where('email', $email)->exists()) {
            $email = $phone . '_' . time() . '@seller.amarbangla.com';
        }

        // Status normalization
        $rawStatus = strtolower($request->status ?? 'active');
        $status = in_array($rawStatus, ['active', 'inactive', 'suspended', 'pending']) ? $rawStatus : 'active';

        $user = User::create([
            'name'     => $validated['name'],
            'phone'    => $phone,
            'email'    => $email,
            'password' => Hash::make($validated['password']),
            'role'     => 'vendor',
            'status'   => $status,
        ]);

        if ($request->is('super_admin/*')) {
            return redirect('/super_admin/vendors')->with('success', "Seller account \"{$user->name}\" created successfully.");
        }

        return redirect()->route('admin.vendors.index')->with('success', "Seller account \"{$user->name}\" created successfully.");
    }

    /**
     * Show seller edit page with 4 multi-sections (Registration Info, Personal, Address, Photo).
     * URL: /super_admin/sellers_update/{id} and /super_admin/vendors/{vendor}/edit
     */
    public function edit($id)
    {
        $vendor = $id instanceof User ? $id : User::findOrFail($id);
        $agents = Agent::orderBy('name')->get();
        $divisions = GlobalAddress::select('division')->distinct()->orderBy('division')->pluck('division');

        return view('admin.vendors.edit', compact('vendor', 'agents', 'divisions'));
    }

    /**
     * Update seller account across all sections.
     * URL: PUT/POST /super_admin/sellers_update/{id} and PUT /super_admin/vendors/{vendor}
     */
    public function update(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'nullable|string|max:255',
            'mobile'      => 'nullable|string|max:20',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:191|unique:users,email,' . $vendor->id,
            'password'    => 'nullable|string|min:6|confirmed',
            'father_name' => 'nullable|string|max:191',
            'mother_name' => 'nullable|string|max:191',
            'age'         => 'nullable|integer',
            'agent_id'    => 'nullable|exists:agents,id',
            'division'    => 'nullable|string|max:100',
            'district'    => 'nullable|string|max:100',
            'upazila'     => 'nullable|string|max:100',
            'pourashava'  => 'nullable|string|max:100',
            'ward'        => 'nullable|string|max:100',
            'status'      => 'nullable|string|max:50',
            'image'       => 'nullable|image|max:1024',
            'image_file'  => 'nullable|image|max:1024',
        ]);

        if (!empty($validated['name'])) {
            $vendor->name = $validated['name'];
        }

        $phone = $validated['mobile'] ?? ($validated['phone'] ?? null);
        if ($phone) {
            $vendor->phone = $phone;
        }

        if (!empty($validated['email'])) {
            $vendor->email = $validated['email'];
        }

        if (!empty($validated['password'])) {
            $vendor->password = Hash::make($validated['password']);
        }

        if (array_key_exists('father_name', $validated)) {
            $vendor->father_name = $validated['father_name'];
        }
        if (array_key_exists('mother_name', $validated)) {
            $vendor->mother_name = $validated['mother_name'];
        }
        if (array_key_exists('age', $validated)) {
            $vendor->age = $validated['age'];
        }
        if (array_key_exists('agent_id', $validated)) {
            $vendor->agent_id = $validated['agent_id'];
        }

        if (array_key_exists('division', $validated)) {
            $vendor->division = $validated['division'];
        }
        if (array_key_exists('district', $validated)) {
            $vendor->district = $validated['district'];
        }
        if (array_key_exists('upazila', $validated)) {
            $vendor->upazila = $validated['upazila'];
        }
        if (array_key_exists('pourashava', $validated)) {
            $vendor->pourashava = $validated['pourashava'];
        }
        if (array_key_exists('ward', $validated)) {
            $vendor->ward = $validated['ward'];
        }

        if (!empty($validated['status'])) {
            $vendor->status = $validated['status'];
        }

        // Image upload handling
        $imgFile = $request->file('image') ?? $request->file('image_file');
        if ($imgFile) {
            $destDir = public_path('uploads/sellers');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'seller_' . time() . '_' . Str::random(6) . '.' . $imgFile->getClientOriginalExtension();
            $imgFile->move($destDir, $filename);
            $vendor->image  = 'uploads/sellers/' . $filename;
            $vendor->avatar = 'uploads/sellers/' . $filename;
        }

        $vendor->save();

        return redirect()->route('admin.vendors.index')->with('success', "Seller \"{$vendor->name}\" updated successfully.");
    }

    // 1. Modular Tab: Registration Credentials Update
    public function updateCredentials(Request $request, $id = null)
    {
        $sellerId = $id ?? $request->id;
        $vendor = $sellerId instanceof User ? $sellerId : User::findOrFail($sellerId);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'mobile'   => 'nullable|string|max:20',
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $vendor->name = $validated['name'];
        $phone = $validated['mobile'] ?? ($validated['phone'] ?? null);
        if ($phone) {
            $vendor->phone = $phone;
        }
        if (!empty($validated['password'])) {
            $vendor->password = Hash::make($validated['password']);
        }
        $vendor->save();

        return back()->with('success', 'Registration credentials updated successfully.');
    }

    // 2. Modular Tab: Personal Information Update
    public function updatePersonalInfo(Request $request, $id = null)
    {
        $sellerId = $id ?? $request->id;
        $vendor = $sellerId instanceof User ? $sellerId : User::findOrFail($sellerId);

        $validated = $request->validate([
            'father_name' => 'nullable|string|max:191',
            'mother_name' => 'nullable|string|max:191',
            'age'         => 'nullable|integer',
            'agent_id'    => 'nullable|exists:agents,id',
        ]);

        $vendor->father_name = $validated['father_name'] ?? null;
        $vendor->mother_name = $validated['mother_name'] ?? null;
        $vendor->age         = $validated['age'] ?? null;
        $vendor->agent_id    = $validated['agent_id'] ?? null;
        $vendor->save();

        return back()->with('success', 'Personal information updated successfully.');
    }

    // 3. Modular Tab: Address Information Update
    public function updateAddress(Request $request, $id = null)
    {
        $sellerId = $id ?? $request->id;
        $vendor = $sellerId instanceof User ? $sellerId : User::findOrFail($sellerId);

        $validated = $request->validate([
            'division'   => 'nullable|string|max:100',
            'district'   => 'nullable|string|max:100',
            'upazila'    => 'nullable|string|max:100',
            'pourashava' => 'nullable|string|max:100',
            'ward'       => 'nullable|string|max:100',
        ]);

        $vendor->division   = $validated['division'] ?? null;
        $vendor->district   = $validated['district'] ?? null;
        $vendor->upazila    = $validated['upazila'] ?? null;
        $vendor->pourashava = $validated['pourashava'] ?? null;
        $vendor->ward       = $validated['ward'] ?? null;
        $vendor->save();

        return back()->with('success', 'Address information updated successfully.');
    }

    // 4. Modular Tab: Photo Update
    public function updatePhoto(Request $request, $id = null)
    {
        $sellerId = $id ?? $request->id;
        $vendor = $sellerId instanceof User ? $sellerId : User::findOrFail($sellerId);

        $request->validate([
            'image' => 'required|image|max:1024',
        ]);

        $destDir = public_path('uploads/sellers');
        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true, true);
        }
        $filename = 'seller_' . time() . '_' . Str::random(6) . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($destDir, $filename);

        $vendor->image  = 'uploads/sellers/' . $filename;
        $vendor->avatar = 'uploads/sellers/' . $filename;
        $vendor->save();

        return back()->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Toggle status between active and suspended.
     */
    public function toggleStatus(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::findOrFail($id);
        $newStatus = ($vendor->status === 'active') ? 'suspended' : 'active';
        $vendor->update(['status' => $newStatus]);

        return back()->with('success', "Seller \"{$vendor->name}\" status changed to {$newStatus}.");
    }

    /**
     * Remove seller account.
     */
    public function destroy($id)
    {
        $vendor = $id instanceof User ? $id : User::findOrFail($id);
        if ($vendor->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $vendor->name;
        $vendor->delete();

        return redirect()->route('admin.vendors.index')->with('success', "Seller \"{$name}\" deleted successfully.");
    }

    public function destroyLegacy($id)
    {
        return $this->destroy($id);
    }

    /**
     * Export sellers to CSV, Excel, PDF, or Print.
     */
    public function export(Request $request, $format = 'csv')
    {
        $query = User::query()->withCount('shops')->with('agent');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        } else {
            $query->whereIn('role', ['vendor', 'seller']);
        }

        if ($request->filled('division')) {
            $divisions = is_array($request->division) ? $request->division : explode(',', $request->division);
            $query->whereIn('division', array_filter(array_map('trim', $divisions)));
        }
        if ($request->filled('district')) {
            $districts = is_array($request->district) ? $request->district : explode(',', $request->district);
            $query->whereIn('district', array_filter(array_map('trim', $districts)));
        }
        if ($request->filled('upazila')) {
            $upazilas = is_array($request->upazila) ? $request->upazila : explode(',', $request->upazila);
            $query->whereIn('upazila', array_filter(array_map('trim', $upazilas)));
        }
        if ($request->filled('pourashava')) {
            $pourashavas = is_array($request->pourashava) ? $request->pourashava : explode(',', $request->pourashava);
            $query->whereIn('pourashava', array_filter(array_map('trim', $pourashavas)));
        }
        if ($request->filled('ward')) {
            $wards = is_array($request->ward) ? $request->ward : explode(',', $request->ward);
            $query->whereIn('ward', array_filter(array_map('trim', $wards)));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('user_id')->get();

        // Print / PDF format view
        if ($format === 'print' || $format === 'pdf') {
            return view('admin.vendors.print', compact('users', 'format'));
        }

        // CSV or Excel format
        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'sellers_export_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            // Columns matching: No, Name, Phone, Status, Division, District, Upazila, Pourashava, Ward
            fputcsv($handle, [
                'No',
                'Name',
                'Phone',
                'Email',
                'Status',
                'Division',
                'District',
                'Upazila',
                'Pourashava/Union',
                'Ward',
                'Shops Count',
                'Joined At'
            ]);

            $index = 1;
            foreach ($users as $user) {
                fputcsv($handle, [
                    $index++,
                    $user->name,
                    $user->phone ?? 'N/A',
                    $user->email ?? 'N/A',
                    ucfirst($user->status ?? 'active'),
                    $user->division ?? 'N/A',
                    $user->district ?? 'N/A',
                    $user->upazila ?? 'N/A',
                    $user->pourashava ?? 'N/A',
                    $user->ward ?? 'N/A',
                    $user->shops_count ?? 0,
                    $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function orders(Request $request, $id)
    {
        return $this->sellerOrders($request, $id);
    }

    /**
     * View all orders for a specific seller.
     * URL: /super_admin/sellers_order/{id} and /super_admin/vendors/{vendor}/orders
     */
    public function sellerOrders(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::with('shops')->findOrFail($id);
        $shopIds = $vendor->shops->pluck('id')->toArray();

        $query = Order::query()->with(['items.shop', 'user']);

        // Orders belonging to this seller's shops OR user_id = $vendor->id
        $query->where(function ($q) use ($shopIds, $vendor) {
            if (!empty($shopIds)) {
                $q->whereHas('items', function ($itemQ) use ($shopIds) {
                    $itemQ->whereIn('shop_id', $shopIds);
                });
            }
            $q->orWhere('user_id', $vendor->id);
        });

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest('id')->paginate(15)->withQueryString();

        // Compute Seller Order Metrics
        $baseOrderQuery = Order::where(function ($q) use ($shopIds, $vendor) {
            if (!empty($shopIds)) {
                $q->whereHas('items', function ($itemQ) use ($shopIds) {
                    $itemQ->whereIn('shop_id', $shopIds);
                });
            }
            $q->orWhere('user_id', $vendor->id);
        });

        $totalOrders     = (clone $baseOrderQuery)->count();
        $totalAmount     = (clone $baseOrderQuery)->sum('total_amount');
        $deliveredOrders = (clone $baseOrderQuery)->where('order_status', 'delivered')->count();
        $pendingOrders   = (clone $baseOrderQuery)->where('order_status', 'pending')->count();

        return view('admin.vendors.orders', compact(
            'vendor',
            'orders',
            'totalOrders',
            'totalAmount',
            'deliveredOrders',
            'pendingOrders'
        ));
    }

    /**
     * Export seller orders to CSV, Excel, PDF, or Print.
     */
    public function exportSellerOrders(Request $request, $id, $format = 'csv')
    {
        $vendor = $id instanceof User ? $id : User::with('shops')->findOrFail($id);
        $shopIds = $vendor->shops->pluck('id')->toArray();

        $query = Order::query()->with(['items.shop', 'user']);
        $query->where(function ($q) use ($shopIds, $vendor) {
            if (!empty($shopIds)) {
                $q->whereHas('items', function ($itemQ) use ($shopIds) {
                    $itemQ->whereIn('shop_id', $shopIds);
                });
            }
            $q->orWhere('user_id', $vendor->id);
        });

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest('id')->get();

        if ($format === 'print' || $format === 'pdf') {
            return view('admin.vendors.orders_print', compact('vendor', 'orders', 'format'));
        }

        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'seller_' . $vendor->id . '_orders_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($orders, $vendor) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'Order Number',
                'Date',
                'Customer Name',
                'Customer Phone',
                'Total Amount',
                'Payment Method',
                'Payment Status',
                'Order Status'
            ]);

            $index = 1;
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $index++,
                    $order->order_number,
                    $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A',
                    $order->customer_name,
                    $order->customer_phone,
                    $order->total_amount,
                    strtoupper($order->payment_method),
                    ucfirst($order->payment_status),
                    ucfirst($order->order_status),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function commission(Request $request, $id)
    {
        return $this->sellerCommission($request, $id);
    }

    /**
     * View commission settings and commission earnings for a specific seller.
     * URL: /super_admin/sellers_commission/{id} and /super_admin/vendors/{vendor}/commission
     */
    public function sellerCommission(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::with('shops')->findOrFail($id);
        $globalCommission = Setting::get('platform_commission', '5.00');

        $shopIds = $vendor->shops->pluck('id')->toArray();

        // Orders with items from this seller's shops to calculate commission breakdown
        $ordersQuery = Order::whereHas('items', function ($q) use ($shopIds) {
            $q->whereIn('shop_id', $shopIds);
        })->with(['items.shop']);

        if ($request->filled('date_from')) {
            $ordersQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $ordersQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $ordersQuery->latest('id')->paginate(15)->withQueryString();

        $totalSales = Order::whereHas('items', function ($q) use ($shopIds) {
            $q->whereIn('shop_id', $shopIds);
        })->where('order_status', '!=', 'cancelled')->sum('total_amount');

        $rate = $vendor->commission_rate ?? $globalCommission;
        $totalCommission = round(($totalSales * $rate) / 100, 2);
        $sellerEarnings  = $totalSales - $totalCommission;

        return view('admin.vendors.commission', compact(
            'vendor',
            'globalCommission',
            'orders',
            'totalSales',
            'totalCommission',
            'sellerEarnings'
        ));
    }

    public function updateCommission(Request $request, $id)
    {
        return $this->updateSellerCommission($request, $id);
    }

    /**
     * Update seller commission rate and optionally apply to all seller outlets.
     * URL: POST /super_admin/sellers_commission/{id}
     */
    public function updateSellerCommission(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::findOrFail($id);

        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'commission_type' => 'nullable|string|in:percentage,fixed',
            'apply_to_shops'  => 'nullable|boolean',
        ]);

        $vendor->commission_rate = $validated['commission_rate'];
        if (!empty($validated['commission_type'])) {
            $vendor->commission_type = $validated['commission_type'];
        }
        $vendor->save();

        if ($request->boolean('apply_to_shops')) {
            $vendor->shops()->update(['commission_rate' => $validated['commission_rate']]);
        }

        return back()->with('success', "Commission settings for seller \"{$vendor->name}\" updated successfully.");
    }

    public function ledger(Request $request, $id)
    {
        return $this->sellerLedger($request, $id);
    }

    /**
     * View financial ledger & statement for a specific seller.
     * URL: /super_admin/sellers_ledger/{id} and /super_admin/vendors/{vendor}/ledger
     */
    public function sellerLedger(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::with('shops')->findOrFail($id);

        $shopNames = $vendor->shops->pluck('name')->toArray();

        $query = Ledger::query()->where(function ($q) use ($vendor, $shopNames) {
            $q->where(function ($sub) use ($vendor) {
                $sub->where('party_type', 'vendor')
                    ->where(function ($p) use ($vendor) {
                        $p->where('reference_id', (string)$vendor->id)
                          ->orWhere('party_name', $vendor->name);
                    });
            });
            if (!empty($shopNames)) {
                $q->orWhere(function ($sub) use ($shopNames) {
                    $sub->where('party_type', 'shop')->whereIn('party_name', $shopNames);
                });
            }
        });

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('txn_number', 'like', "%{$search}%")
                  ->orWhere('reference_id', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $ledgers = $query->latest('id')->paginate(20)->withQueryString();

        $baseLedgerQuery = clone $query;
        $totalCredit = (clone $baseLedgerQuery)->where('type', 'credit')->sum('amount');
        $totalDebit  = (clone $baseLedgerQuery)->where('type', 'debit')->sum('amount');
        $netBalance  = $vendor->balance ?? ($totalCredit - $totalDebit);

        return view('admin.vendors.ledger', compact(
            'vendor',
            'ledgers',
            'totalCredit',
            'totalDebit',
            'netBalance'
        ));
    }

    /**
     * Store new manual ledger transaction entry for a seller.
     * URL: POST /super_admin/sellers_ledger/{id}
     */
    public function storeSellerLedger(Request $request, $id)
    {
        $vendor = $id instanceof User ? $id : User::findOrFail($id);

        $validated = $request->validate([
            'type'           => 'required|in:credit,debit',
            'category'       => 'required|string|max:100',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:100',
            'reference_id'   => 'nullable|string|max:100',
            'note'           => 'nullable|string',
        ]);

        $validated['txn_number'] = 'TXN-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $validated['party_type'] = 'vendor';
        $validated['party_name'] = $vendor->name;
        if (empty($validated['reference_id'])) {
            $validated['reference_id'] = (string)$vendor->id;
        }

        $currentBalance = (float)($vendor->balance ?? 0.00);
        if ($validated['type'] === 'credit') {
            $newBalance = $currentBalance + (float)$validated['amount'];
        } else {
            $newBalance = $currentBalance - (float)$validated['amount'];
        }

        $validated['balance_after'] = $newBalance;

        Ledger::create($validated);

        $vendor->balance = $newBalance;
        $vendor->save();

        return back()->with('success', "Ledger transaction of ৳ {$validated['amount']} ({$validated['type']}) recorded for {$vendor->name}.");
    }

    /**
     * Export seller ledger statement to CSV, Excel, PDF, or Print.
     */
    public function exportSellerLedger(Request $request, $id, $format = 'csv')
    {
        $vendor = $id instanceof User ? $id : User::with('shops')->findOrFail($id);
        $shopNames = $vendor->shops->pluck('name')->toArray();

        $query = Ledger::query()->where(function ($q) use ($vendor, $shopNames) {
            $q->where(function ($sub) use ($vendor) {
                $sub->where('party_type', 'vendor')
                    ->where(function ($p) use ($vendor) {
                        $p->where('reference_id', (string)$vendor->id)
                          ->orWhere('party_name', $vendor->name);
                    });
            });
            if (!empty($shopNames)) {
                $q->orWhere(function ($sub) use ($shopNames) {
                    $sub->where('party_type', 'shop')->whereIn('party_name', $shopNames);
                });
            }
        });

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('txn_number', 'like', "%{$search}%")
                  ->orWhere('reference_id', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $ledgers = $query->latest('id')->get();

        if ($format === 'print' || $format === 'pdf') {
            return view('admin.vendors.ledger_print', compact('vendor', 'ledgers', 'format'));
        }

        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'seller_' . $vendor->id . '_ledger_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($ledgers, $vendor) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'Txn Number',
                'Date',
                'Type',
                'Category',
                'Amount',
                'Balance After',
                'Payment Method',
                'Reference ID',
                'Note'
            ]);

            $index = 1;
            foreach ($ledgers as $entry) {
                fputcsv($handle, [
                    $index++,
                    $entry->txn_number,
                    $entry->created_at ? $entry->created_at->format('Y-m-d H:i') : 'N/A',
                    strtoupper($entry->type),
                    ucfirst(str_replace('_', ' ', $entry->category)),
                    $entry->amount,
                    $entry->balance_after,
                    $entry->payment_method ?? 'N/A',
                    $entry->reference_id ?? 'N/A',
                    $entry->note ?? ''
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
