<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\Admin\GlobalAddressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Build the filtered query for customers across personal, status, and geographic criteria
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = Customer::with(['customerType', 'user', 'globalAddress'])->withCount('invoices')->select('customers.*');

        // Left join global_address to allow searching/filtering across both customer fields and global_address fields
        $query->leftJoin('global_address', 'global_address.global_address_id', '=', 'customers.global_address_id');

        // 1. Division Filter (supports name or numeric ID)
        if ($request->filled('division')) {
            $rawDivisions = is_array($request->division) ? $request->division : explode(',', $request->division);
            $rawDivisions = array_filter(array_map('trim', $rawDivisions));

            $divMatches = [];
            foreach ($rawDivisions as $divVal) {
                $divMatches[] = $divVal;
                for ($i = 1; $i <= 8; $i++) {
                    if (strcasecmp(divisionname($i), $divVal) === 0) {
                        $divMatches[] = (string)$i;
                        break;
                    }
                }
                if (is_numeric($divVal)) {
                    $dName = divisionname((int)$divVal);
                    if ($dName) $divMatches[] = $dName;
                }
            }
            $divMatches = array_unique($divMatches);

            if (!empty($divMatches)) {
                $query->where(function($q) use ($divMatches) {
                    $q->whereIn('customers.division', $divMatches)
                      ->orWhereIn('global_address.division', $divMatches);
                });
            }
        }

        // 2. District Filter (supports name or numeric ID)
        if ($request->filled('district')) {
            $rawDistricts = is_array($request->district) ? $request->district : explode(',', $request->district);
            $rawDistricts = array_filter(array_map('trim', $rawDistricts));

            $distMatches = [];
            foreach ($rawDistricts as $distVal) {
                $distMatches[] = $distVal;
                for ($i = 1; $i <= 64; $i++) {
                    if (strcasecmp(districtname($i), $distVal) === 0) {
                        $distMatches[] = (string)$i;
                        break;
                    }
                }
                if (is_numeric($distVal)) {
                    $dName = districtname((int)$distVal);
                    if ($dName) $distMatches[] = $dName;
                }
            }
            $distMatches = array_unique($distMatches);

            if (!empty($distMatches)) {
                $query->where(function($q) use ($distMatches) {
                    $q->whereIn('customers.district', $distMatches)
                      ->orWhereIn('global_address.district', $distMatches)
                      ->orWhereIn('global_address.zila', $distMatches);
                });
            }
        }

        // 3. Upazila Filter (supports name or numeric ID)
        if ($request->filled('upazila')) {
            $rawUpazilas = is_array($request->upazila) ? $request->upazila : explode(',', $request->upazila);
            $rawUpazilas = array_filter(array_map('trim', $rawUpazilas));

            $upzMatches = [];
            foreach ($rawUpazilas as $upzVal) {
                $upzMatches[] = $upzVal;
                for ($i = 1; $i <= 600; $i++) {
                    $uName = upazilaname($i);
                    if ($uName && strcasecmp($uName, $upzVal) === 0) {
                        $upzMatches[] = (string)$i;
                    }
                }
                if (is_numeric($upzVal)) {
                    $uName = upazilaname((int)$upzVal);
                    if ($uName) $upzMatches[] = $uName;
                }
            }
            $upzMatches = array_unique($upzMatches);

            if (!empty($upzMatches)) {
                $query->where(function($q) use ($upzMatches) {
                    $q->whereIn('customers.upazila', $upzMatches)
                      ->orWhereIn('global_address.upazila', $upzMatches);
                });
            }
        }

        // 4. Pourashava/Union/City Corporation Filter
        if ($request->filled('pourashava')) {
            $rawPours = is_array($request->pourashava) ? $request->pourashava : explode(',', $request->pourashava);
            $rawPours = array_filter(array_map('trim', $rawPours));

            $poursMatches = [];
            foreach ($rawPours as $p) {
                $poursMatches[] = $p;
                if ($p === 'City Corporation' || $p === '0') {
                    $poursMatches[] = 'City Corporation';
                    $poursMatches[] = '0';
                } elseif ($p === 'Pourashava' || $p === '1') {
                    $poursMatches[] = 'Pourashava';
                    $poursMatches[] = '1';
                } elseif ($p === 'Union' || $p === '2') {
                    $poursMatches[] = 'Union';
                    $poursMatches[] = '2';
                }
            }
            $poursMatches = array_unique($poursMatches);

            if (!empty($poursMatches)) {
                $query->where(function($q) use ($poursMatches) {
                    $q->whereIn('customers.pourashava', $poursMatches)
                      ->orWhereIn('global_address.pourashava', $poursMatches);
                });
            }
        }

        // 5. Ward Filter
        if ($request->filled('ward')) {
            $rawWards = is_array($request->ward) ? $request->ward : explode(',', $request->ward);
            $rawWards = array_filter(array_map('trim', $rawWards));

            $wardMatches = [];
            foreach ($rawWards as $w) {
                $wardMatches[] = $w;
                $wClean = preg_replace('/[^0-9]/', '', $w);
                if ($wClean) {
                    $wardMatches[] = $wClean;
                    $wardMatches[] = 'Ward ' . $wClean;
                    $wardMatches[] = 'ward ' . $wClean;
                }
            }
            $wardMatches = array_unique($wardMatches);

            if (!empty($wardMatches)) {
                $query->where(function($q) use ($wardMatches) {
                    $q->whereIn('customers.ward', $wardMatches)
                      ->orWhereIn('global_address.ward', $wardMatches);
                });
            }
        }

        // Customer Type Filter
        if ($request->filled('customer_type_id')) {
            $query->where(function($q) use ($request) {
                $q->where('customers.customer_type_id', $request->customer_type_id)
                  ->orWhere('customers.cus_type_id', $request->customer_type_id);
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('customers.status', $request->status);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('customers.name', 'like', "%{$search}%")
                  ->orWhere('customers.customer_name', 'like', "%{$search}%")
                  ->orWhere('customers.phone', 'like', "%{$search}%")
                  ->orWhere('customers.mobile', 'like', "%{$search}%")
                  ->orWhere('customers.email', 'like', "%{$search}%")
                  ->orWhere('customers.address', 'like', "%{$search}%")
                  ->orWhere('customers.district', 'like', "%{$search}%")
                  ->orWhere('customers.customer_id', $search);
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        $perPage = (int) ($request->get('per_page') ?: 25);
        if ($perPage < 10) $perPage = 10;
        if ($perPage > 200) $perPage = 200;

        $customers = $query->with(['globalAddress', 'customerType'])
                           ->latest('customers.customer_id')
                           ->paginate($perPage)
                           ->withQueryString();
        $types = CustomerType::whereNull('deleted')->orderBy('type_name')->get();
        $hierarchy = (new GlobalAddressController())->getHierarchy();

        return view('admin.customers.index', compact('customers', 'types', 'hierarchy'));
    }

    /**
     * Export filtered customers to CSV, Excel, or Print
     */
    public function export(Request $request)
    {
        $format = strtolower($request->get('format', 'csv'));
        $query = $this->buildFilteredQuery($request);

        ini_set('memory_limit', '512M');

        // Print / PDF format view
        if ($format === 'print' || $format === 'pdf') {
            $customers = $query->with(['globalAddress', 'customerType'])->latest('customers.customer_id')->limit(500)->get();
            return view('admin.customers.print', compact('customers'));
        }

        $customers = $query->with(['globalAddress', 'customerType'])->latest('customers.customer_id')->get();

        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'customers_export_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($customers) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($handle, [
                'ID',
                'Name',
                'Phone',
                'Email',
                'Customer Type',
                'Division',
                'District',
                'Upazila',
                'Pourashava/Union',
                'Ward',
                'Address',
                'Balance (BDT)',
                'Status',
                'Orders Count',
                'Created At'
            ]);

            foreach ($customers as $c) {
                fputcsv($handle, [
                    $c->customer_id,
                    $c->name,
                    $c->phone ?? 'N/A',
                    $c->email ?? 'N/A',
                    $c->customerType->name ?? 'General',
                    $c->resolved_division ?? '',
                    $c->resolved_district ?? '',
                    $c->resolved_upazila ?? '',
                    $c->resolved_pourashava ?? '',
                    $c->resolved_ward ?? '',
                    $c->address ?? '',
                    number_format($c->balance, 2, '.', ''),
                    ucfirst($c->status),
                    $c->total_orders_count,
                    $c->created_at ? $c->created_at->format('Y-m-d H:i') : ''
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function create()
    {
        $types = CustomerType::whereNull('deleted')->orderBy('type_name')->get();
        return view('admin.customers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20|unique:customers,phone',
            'email'            => 'nullable|email|max:255',
            'customer_type_id' => 'nullable|exists:customer_types,id',
            'address'          => 'nullable|string',
            'division'         => 'nullable|string|max:100',
            'district'         => 'nullable|string|max:100',
            'upazila'          => 'nullable|string|max:100',
            'status'           => 'required|in:active,inactive,suspended',
            'notes'            => 'nullable|string|max:1000',
            'create_user'      => 'nullable|boolean',
            'password'         => 'nullable|string|min:6',
        ]);

        $userId = null;
        if ($request->boolean('create_user') && $request->filled('password')) {
            $user = User::create([
                'name'     => $validated['name'],
                'phone'    => $validated['phone'],
                'email'    => $validated['email'] ?? ($validated['phone'] . '@amarbangla.local'),
                'password' => Hash::make($request->password),
                'role'     => 'customer',
                'status'   => $validated['status'] === 'active' ? 'active' : 'suspended',
            ]);
            $userId = $user->id;
        }

        $validated['user_id'] = $userId;
        unset($validated['create_user'], $validated['password']);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer profile created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['customerType', 'user']);
        $orders = Order::where('customer_phone', $customer->phone)
            ->orWhere(function ($q) use ($customer) {
                if ($customer->user_id) {
                    $q->where('user_id', $customer->user_id);
                }
            })
            ->with(['items'])
            ->latest()
            ->paginate(10);

        $totalOrders = Order::where('customer_phone', $customer->phone)->count();
        $totalSpent = Order::where('customer_phone', $customer->phone)->where('payment_status', 'paid')->sum('total_amount');

        return view('admin.customers.show', compact('customer', 'orders', 'totalOrders', 'totalSpent'));
    }

    /**
     * View customer order list (all orders placed by customer)
     */
    public function orders($customer)
    {
        $customerModel = is_numeric($customer)
            ? (Customer::where('customer_id', $customer)->orWhere('user_id', $customer)->first() ?: Customer::findOrFail($customer))
            : ($customer instanceof Customer ? $customer : Customer::findOrFail($customer));
        $customerModel->load(['customerType', 'user', 'globalAddress']);

        $customerId = $customerModel->customer_id ?? $customerModel->id;

        // Query all invoices for this customer
        $query = Invoice::where(function($q) use ($customerModel, $customerId) {
            $q->where('customer_id', $customerId);
            if ($customerModel->user_id) {
                $q->orWhere('customer_id', $customerModel->user_id);
            }
            if ($customerModel->phone || $customerModel->mobile) {
                $phones = array_filter([
                    $customerModel->phone,
                    $customerModel->mobile,
                    '0' . ltrim((string)$customerModel->mobile, '0'),
                    ltrim((string)$customerModel->phone, '0')
                ]);
                $q->orWhereIn('invoice_id', function($sub) use ($phones) {
                    $sub->select('invoice_id')->from('orders')
                        ->whereIn('customer_phone', $phones)
                        ->whereNotNull('invoice_id');
                });
            }
        })->latest('invoice_id');

        $totalInvoicesCount = (clone $query)->count();
        $totalInvoicesAmount = (float) (clone $query)->sum('final_amount');
        $totalInvoicesDue = (float) (clone $query)->sum('due');
        $deliveredCount = (clone $query)->whereIn('status', [3, '3', 'complete', 'delivered'])->count();

        $invoices = $query->paginate(20)->withQueryString();

        $customer = $customerModel;

        return view('admin.customers.orders', compact(
            'customer',
            'invoices',
            'totalInvoicesCount',
            'totalInvoicesAmount',
            'totalInvoicesDue',
            'deliveredCount'
        ));
    }

    public function edit($customer)
    {
        $customer = $customer instanceof Customer ? $customer : Customer::findOrFail($customer);
        $types = CustomerType::whereNull('deleted')->orderBy('type_name')->get();
        return view('admin.customers.edit', compact('customer', 'types'));
    }

    public function update(Request $request, $customer)
    {
        $customer = $customer instanceof Customer ? $customer : Customer::findOrFail($customer);

        $name = $request->input('name', $request->input('customer_name', $customer->name));
        $phone = $request->input('phone', $request->input('mobile', $customer->phone));

        $validated = $request->validate([
            'name'             => 'nullable|string|max:255',
            'customer_name'    => 'nullable|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'mobile'           => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'customer_type_id' => 'nullable',
            'cus_type_id'      => 'nullable',
            'father_name'      => 'nullable|string|max:55',
            'mother_name'      => 'nullable|string|max:55',
            'age'              => 'nullable|numeric',
            'nid'              => 'nullable|string|max:155',
            'division'         => 'nullable|string|max:100',
            'district'         => 'nullable|string|max:100',
            'upazila'          => 'nullable|string|max:100',
            'pourashava'       => 'nullable|string|max:100',
            'ward'             => 'nullable|string|max:100',
            'address'          => 'nullable|string',
            'balance'          => 'nullable|numeric',
            'status'           => 'nullable|string|max:50',
            'notes'            => 'nullable|string|max:1000',
            'password'         => 'nullable|string|min:6',
            'con_password'     => 'nullable|string',
            'pic'              => 'nullable|file|mimes:jpeg,jpg,png,gif|max:1024',
        ]);

        if ($name) {
            $customer->name = $name;
            $customer->customer_name = $name;
        }

        if ($phone) {
            $customer->phone = $phone;
        }

        if ($request->filled('email')) {
            $customer->email = $request->email;
        }

        $typeId = $request->input('cus_type_id', $request->input('customer_type_id'));
        if ($typeId !== null) {
            $customer->cus_type_id = $typeId;
            $customer->customer_type_id = $typeId;
        }

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
            $customer->pass = $request->password;
        }

        if ($request->has('father_name')) $customer->father_name = $request->father_name;
        if ($request->has('mother_name')) $customer->mother_name = $request->mother_name;
        if ($request->has('age')) $customer->age = $request->age;
        if ($request->has('nid')) $customer->nid = $request->nid;

        if ($request->has('division')) $customer->division = $request->division;
        if ($request->has('district')) $customer->district = $request->district;
        if ($request->has('upazila')) $customer->upazila = $request->upazila;
        if ($request->has('pourashava')) $customer->pourashava = $request->pourashava;
        if ($request->has('ward')) $customer->ward = $request->ward;
        if ($request->has('address')) $customer->address = $request->address;

        if ($request->has('balance')) $customer->balance = $request->balance;
        if ($request->has('status')) $customer->status = $request->status;
        if ($request->has('notes')) $customer->notes = $request->notes;

        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $filename = 'customer_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customer_image'), $filename);
            $customer->pic = $filename;
        }

        $customer->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Customer profile updated successfully.']);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Customer profile updated successfully.');
    }

    // Tab 1: Registration Info Update
    public function updateCredentials(Request $request, $id = null)
    {
        $customerId = $id ?? $request->customer_id ?? $request->id;
        $customer = $customerId instanceof Customer ? $customerId : Customer::findOrFail($customerId);

        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'name'          => 'nullable|string|max:255',
            'mobile'        => 'nullable|string|max:20',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'nullable|string|min:6',
            'con_password'  => 'nullable|same:password',
            'cus_type_id'   => 'nullable',
        ]);

        $name = $request->input('customer_name', $request->input('name'));
        if ($name) {
            $customer->name = $name;
            $customer->customer_name = $name;
        }

        $mobile = $request->input('mobile', $request->input('phone'));
        if ($mobile) {
            $customer->phone = $mobile;
        }

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
            $customer->pass = $request->password;
        }

        $typeId = $request->input('cus_type_id', $request->input('customer_type_id'));
        if ($typeId !== null) {
            $customer->cus_type_id = $typeId;
            $customer->customer_type_id = $typeId;
        }

        $customer->save();

        return back()->with('success', 'Registration info updated successfully.')->with('active_tab', 'custom-tabs-four-home');
    }

    // Tab 2: Personal Info Update
    public function updatePersonalInfo(Request $request, $id = null)
    {
        $customerId = $id ?? $request->customer_id ?? $request->id;
        $customer = $customerId instanceof Customer ? $customerId : Customer::findOrFail($customerId);

        $request->validate([
            'father_name' => 'nullable|string|max:55',
            'mother_name' => 'nullable|string|max:55',
            'age'         => 'nullable|numeric',
            'nid'         => 'nullable|string|max:155',
        ]);

        $customer->father_name = $request->father_name;
        $customer->mother_name = $request->mother_name;
        $customer->age         = $request->age;
        $customer->nid         = $request->nid;
        $customer->save();

        return back()->with('success', 'Personal information updated successfully.')->with('active_tab', 'custom-tabs-four-profile');
    }

    // Tab 3: Address Update
    public function updateAddress(Request $request, $id = null)
    {
        $customerId = $id ?? $request->customer_id ?? $request->id;
        $customer = $customerId instanceof Customer ? $customerId : Customer::findOrFail($customerId);

        $request->validate([
            'division'   => 'nullable|string|max:100',
            'district'   => 'nullable|string|max:100',
            'upazila'    => 'nullable|string|max:100',
            'pourashava' => 'nullable|string|max:100',
            'ward'       => 'nullable|string|max:100',
            'address'    => 'nullable|string',
        ]);

        $customer->division   = $request->division;
        $customer->district   = $request->district;
        $customer->upazila    = $request->upazila;
        $customer->pourashava = $request->pourashava;
        $customer->ward       = $request->ward;
        $customer->address    = $request->address;
        $customer->save();

        return back()->with('success', 'Address information updated successfully.')->with('active_tab', 'custom-tabs-four-settings');
    }

    // Tab 4: Photo Update
    public function updatePhoto(Request $request, $id = null)
    {
        $customerId = $id ?? $request->customer_id ?? $request->id;
        $customer = $customerId instanceof Customer ? $customerId : Customer::findOrFail($customerId);

        $request->validate([
            'pic' => 'required|image|mimes:jpeg,jpg,png,gif|max:1024',
        ]);

        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $filename = 'customer_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customer_image'), $filename);
            $customer->pic = $filename;
            $customer->save();
        }

        return back()->with('success', 'Photo updated successfully.')->with('active_tab', 'custom-tabs-four-messages');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
