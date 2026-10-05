<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\DeliveryBoy;
use App\Models\GlobalAddress;
use App\Models\Ledger;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DeliveryBoyController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryBoy::query()->with('agent');

        // 1. Division Filter (supports single or multiple / comma-separated)
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

        // 7. Vehicle Type Filter
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // 8. Free Keyword Search (Name, Phone, Email, Area, NID)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nid_number', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%")
                  ->orWhere('pourashava', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%");
            });
        }

        $riders = $query->latest()->paginate(20)->withQueryString();

        // Retrieve dropdown values
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
        } elseif ($request->filled('division')) {
            $selDivs = is_array($request->division) ? $request->division : explode(',', $request->division);
            $upazilaQuery->whereIn('division', array_filter(array_map('trim', $selDivs)));
        }
        $upazilas = $upazilaQuery->pluck('upazila');

        $pourashavas = GlobalAddress::select('pourashava')->distinct()
            ->whereNotNull('pourashava')
            ->where('pourashava', '!=', '')
            ->orderBy('pourashava')
            ->pluck('pourashava');

        $rawWards = GlobalAddress::select('ward')->distinct()
            ->whereNotNull('ward')
            ->where('ward', '!=', '')
            ->pluck('ward')
            ->toArray();
        natsort($rawWards);
        $wards = array_values($rawWards);

        $hierarchy = (new GlobalAddressController())->getHierarchy();

        return view('admin.delivery_boys.index', compact(
            'riders',
            'divisions',
            'districts',
            'upazilas',
            'pourashavas',
            'wards',
            'hierarchy'
        ));
    }

    public function create()
    {
        $hierarchy = (new GlobalAddressController())->getHierarchy();
        $agents = Agent::orderBy('name')->get();

        return view('admin.delivery_boys.create', compact('hierarchy', 'agents'));
    }

    public function store(Request $request)
    {
        // Support both "mobile" (live form) and "phone"
        if (!$request->filled('phone') && $request->filled('mobile')) {
            $request->merge(['phone' => $request->mobile]);
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20|unique:delivery_boys,phone',
            'password'     => 'required|string|min:4',
            'con_password' => 'nullable|same:password',
            'father_name'  => 'nullable|string|max:255',
            'mother_name'  => 'nullable|string|max:255',
            'age'          => 'nullable|integer|min:14|max:100',
            'agent_id'     => 'nullable|exists:agents,id',
            'email'        => 'nullable|email|max:255',
            'nid_number'   => 'nullable|string|max:50',
            'vehicle_type' => 'nullable|in:bicycle,motorcycle,van,walking',
            'division'     => 'nullable|string|max:100',
            'district'     => 'nullable|string|max:100',
            'upazila'      => 'nullable|string|max:100',
            'pourashava'   => 'nullable|string|max:100',
            'ward'         => 'nullable|string|max:100',
            'address'      => 'nullable|string',
            'balance'      => 'nullable|numeric',
            'cash_in_hand' => 'nullable|numeric|min:0',
            'status'       => 'nullable',
            'pic'          => 'nullable|image|max:3072',
            'image'        => 'nullable|image|max:3072',
        ]);

        // Normalize status
        $rawStatus = $request->status;
        if ($rawStatus === '1' || $rawStatus === 1 || $rawStatus === 'active') {
            $status = 'active';
        } elseif ($rawStatus === '0' || $rawStatus === 0 || $rawStatus === 'inactive') {
            $status = 'inactive';
        } else {
            $status = 'active';
        }

        // Handle image upload
        $imagePath = null;
        $imageFile = $request->file('pic') ?? $request->file('image');
        if ($imageFile) {
            $destDir = public_path('uploads/delivery_boys');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'delivery_boy_' . time() . '_' . Str::random(6) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($destDir, $filename);
            $imagePath = 'uploads/delivery_boys/' . $filename;
        }

        // Optional User creation for authentication
        $user = null;
        if (!empty($validated['phone'])) {
            $existingUser = User::where('phone', $validated['phone'])->first();
            if (!$existingUser) {
                $user = User::create([
                    'name'     => $validated['name'],
                    'phone'    => $validated['phone'],
                    'email'    => $validated['email'] ?? ($validated['phone'] . '@delivery.amarbangla.com.bd'),
                    'password' => Hash::make($validated['password']),
                    'role'     => 'delivery_boy',
                ]);
            } else {
                $user = $existingUser;
            }
        }

        DeliveryBoy::create([
            'user_id'      => $user ? $user->id : null,
            'agent_id'     => $request->filled('agent_id') ? $request->agent_id : null,
            'name'         => $validated['name'],
            'father_name'  => $validated['father_name'] ?? null,
            'mother_name'  => $validated['mother_name'] ?? null,
            'age'          => $validated['age'] ?? null,
            'phone'        => $validated['phone'],
            'password'     => Hash::make($validated['password']),
            'email'        => $validated['email'] ?? null,
            'nid_number'   => $validated['nid_number'] ?? null,
            'image'        => $imagePath,
            'vehicle_type' => $validated['vehicle_type'] ?? 'motorcycle',
            'division'     => $validated['division'] ?? null,
            'district'     => $validated['district'] ?? null,
            'upazila'      => $validated['upazila'] ?? null,
            'pourashava'   => $validated['pourashava'] ?? null,
            'ward'         => $validated['ward'] ?? null,
            'address'      => $validated['address'] ?? null,
            'cash_in_hand' => $validated['cash_in_hand'] ?? 0,
            'balance'      => $validated['balance'] ?? 0,
            'status'       => $status,
        ]);

        return redirect()->route('admin.delivery_boys.index')->with('success', 'Delivery Boy registered successfully.');
    }

    public function edit($id)
    {
        $deliveryBoy = DeliveryBoy::findOrFail($id);
        $hierarchy = (new GlobalAddressController())->getHierarchy();
        $agents = Agent::orderBy('name')->get();

        return view('admin.delivery_boys.edit', compact('deliveryBoy', 'hierarchy', 'agents'));
    }

    public function update(Request $request, $id)
    {
        $deliveryBoy = DeliveryBoy::findOrFail($id);

        if (!$request->filled('phone') && $request->filled('mobile')) {
            $request->merge(['phone' => $request->mobile]);
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20|unique:delivery_boys,phone,' . $deliveryBoy->id,
            'password'     => 'nullable|string|min:4',
            'con_password' => 'nullable|same:password',
            'father_name'  => 'nullable|string|max:255',
            'mother_name'  => 'nullable|string|max:255',
            'age'          => 'nullable|integer|min:14|max:100',
            'agent_id'     => 'nullable|exists:agents,id',
            'email'        => 'nullable|email|max:255',
            'nid_number'   => 'nullable|string|max:50',
            'vehicle_type' => 'nullable|in:bicycle,motorcycle,van,walking',
            'division'     => 'nullable|string|max:100',
            'district'     => 'nullable|string|max:100',
            'upazila'      => 'nullable|string|max:100',
            'pourashava'   => 'nullable|string|max:100',
            'ward'         => 'nullable|string|max:100',
            'address'      => 'nullable|string',
            'balance'      => 'nullable|numeric',
            'cash_in_hand' => 'nullable|numeric|min:0',
            'status'       => 'nullable',
            'pic'          => 'nullable|image|max:3072',
            'image'        => 'nullable|image|max:3072',
        ]);

        $data = [
            'name'         => $validated['name'],
            'father_name'  => $validated['father_name'] ?? $deliveryBoy->father_name,
            'mother_name'  => $validated['mother_name'] ?? $deliveryBoy->mother_name,
            'age'          => $validated['age'] ?? $deliveryBoy->age,
            'phone'        => $validated['phone'],
            'email'        => $validated['email'] ?? $deliveryBoy->email,
            'nid_number'   => $validated['nid_number'] ?? $deliveryBoy->nid_number,
            'vehicle_type' => $validated['vehicle_type'] ?? $deliveryBoy->vehicle_type,
            'division'     => $validated['division'] ?? $deliveryBoy->division,
            'district'     => $validated['district'] ?? $deliveryBoy->district,
            'upazila'      => $validated['upazila'] ?? $deliveryBoy->upazila,
            'pourashava'   => $validated['pourashava'] ?? $deliveryBoy->pourashava,
            'ward'         => $validated['ward'] ?? $deliveryBoy->ward,
            'address'      => $validated['address'] ?? $deliveryBoy->address,
        ];

        if ($request->has('agent_id')) {
            $data['agent_id'] = $request->agent_id ?: null;
        }

        if ($request->has('balance')) {
            $data['balance'] = $request->balance ?? 0;
        }

        if ($request->has('cash_in_hand')) {
            $data['cash_in_hand'] = $request->cash_in_hand ?? 0;
        }

        if ($request->has('status')) {
            $rawStatus = $request->status;
            $data['status'] = ($rawStatus === '1' || $rawStatus === 1 || $rawStatus === 'active') ? 'active' : 'inactive';
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
            if ($deliveryBoy->user) {
                $deliveryBoy->user->update(['password' => Hash::make($validated['password'])]);
            }
        }

        $imageFile = $request->file('pic') ?? $request->file('image');
        if ($imageFile) {
            $destDir = public_path('uploads/delivery_boys');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'delivery_boy_' . time() . '_' . Str::random(6) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($destDir, $filename);
            $data['image'] = 'uploads/delivery_boys/' . $filename;
        }

        $deliveryBoy->update($data);

        return redirect()->route('admin.delivery_boys.index')->with('success', 'Delivery Boy updated successfully.');
    }

    // Section 1: Update Account Credentials (POST /super_admin/delivery_boy_update_action/{id?})
    public function updateCredentials(Request $request, $id = null)
    {
        $riderId = $id ?? $request->id ?? $request->delivery_boy_id;
        $deliveryBoy = DeliveryBoy::findOrFail($riderId);

        if (!$request->filled('phone') && $request->filled('mobile')) {
            $request->merge(['phone' => $request->mobile]);
        }

        $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:20|unique:delivery_boys,phone,' . $deliveryBoy->id,
            'password'     => 'nullable|string|min:4',
            'con_password' => 'nullable|same:password',
        ]);

        $deliveryBoy->name = $request->name;
        $deliveryBoy->phone = $request->phone;

        if ($request->filled('password')) {
            $deliveryBoy->password = Hash::make($request->password);
            if ($deliveryBoy->user) {
                $deliveryBoy->user->update([
                    'name'     => $request->name,
                    'phone'    => $request->phone,
                    'password' => Hash::make($request->password),
                ]);
            }
        } elseif ($deliveryBoy->user) {
            $deliveryBoy->user->update([
                'name'  => $request->name,
                'phone' => $request->phone,
            ]);
        }

        $deliveryBoy->save();

        return redirect()->back()->with('success', 'Account credentials updated successfully.');
    }

    // Section 2: Update Personal Info (POST /super_admin/delivery_boy_personal_update/{id?})
    public function updatePersonalInfo(Request $request, $id = null)
    {
        $riderId = $id ?? $request->id ?? $request->delivery_boy_id;
        $deliveryBoy = DeliveryBoy::findOrFail($riderId);

        $request->validate([
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'age'         => 'nullable|integer|min:14|max:100',
            'agent_id'    => 'nullable|exists:agents,id',
            'status'      => 'nullable',
        ]);

        $deliveryBoy->father_name = $request->father_name;
        $deliveryBoy->mother_name = $request->mother_name;
        $deliveryBoy->age = $request->age;
        $deliveryBoy->agent_id = $request->agent_id ?: null;

        if ($request->has('status')) {
            $deliveryBoy->status = ($request->status == 1 || $request->status === 'active') ? 'active' : 'inactive';
        }

        $deliveryBoy->save();

        return redirect()->back()->with('success', 'Personal information updated successfully.');
    }

    // Section 3: Update Delivery Area (POST /super_admin/delivery_boy_address_update/{id?})
    public function updateAddress(Request $request, $id = null)
    {
        $riderId = $id ?? $request->id ?? $request->delivery_boy_id;
        $deliveryBoy = DeliveryBoy::findOrFail($riderId);

        $request->validate([
            'division'   => 'nullable|string|max:100',
            'district'   => 'nullable|string|max:100',
            'upazila'    => 'nullable|string|max:100',
            'pourashava' => 'nullable|string|max:100',
            'ward'       => 'nullable|string|max:100',
            'address'    => 'nullable|string',
        ]);

        $deliveryBoy->division   = $request->division;
        $deliveryBoy->district   = $request->district;
        $deliveryBoy->upazila    = $request->upazila;
        $deliveryBoy->pourashava = $request->pourashava;
        $deliveryBoy->ward       = $request->ward;
        if ($request->filled('address')) {
            $deliveryBoy->address = $request->address;
        }

        $deliveryBoy->save();

        return redirect()->back()->with('success', 'Delivery zone updated successfully.');
    }

    // Section 4: Update Profile Photo (POST /super_admin/delivery_boy_photo_update/{id?})
    public function updatePhoto(Request $request, $id = null)
    {
        $riderId = $id ?? $request->id ?? $request->delivery_boy_id;
        $deliveryBoy = DeliveryBoy::findOrFail($riderId);

        $request->validate([
            'pic'   => 'nullable|image|max:3072',
            'image' => 'nullable|image|max:3072',
        ]);

        $imageFile = $request->file('pic') ?? $request->file('image');
        if ($imageFile) {
            $destDir = public_path('uploads/delivery_boys');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'delivery_boy_' . time() . '_' . Str::random(6) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($destDir, $filename);
            $deliveryBoy->image = 'uploads/delivery_boys/' . $filename;
            $deliveryBoy->save();
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }

    public function destroy($id)
    {
        $deliveryBoy = DeliveryBoy::findOrFail($id);
        $name = $deliveryBoy->name;
        $deliveryBoy->delete();

        return redirect()->route('admin.delivery_boys.index')->with('success', "Delivery Boy '{$name}' deleted successfully.");
    }

    public function destroyLegacy($id)
    {
        return $this->destroy($id);
    }

    // =========================================================================
    // Action Pages: Orders, Commissions, Ledger
    // =========================================================================

    public function orders($id)
    {
        $deliveryBoy = DeliveryBoy::with('agent')->findOrFail($id);

        $assignedOrders = Order::where('delivery_boy_id', $deliveryBoy->id)
            ->latest()
            ->paginate(15);

        // Also fetch candidate pending/processing orders from matching district/upazila or unassigned
        $availableOrders = Order::whereNull('delivery_boy_id')
            ->whereIn('order_status', ['confirmed', 'processing', 'pending'])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.delivery_boys.orders', compact('deliveryBoy', 'assignedOrders', 'availableOrders'));
    }

    public function assignOrder(Request $request, $id)
    {
        $deliveryBoy = DeliveryBoy::findOrFail($id);
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->delivery_boy_id = $deliveryBoy->id;
        if ($order->order_status === 'confirmed' || $order->order_status === 'pending') {
            $order->order_status = 'processing';
        }
        $order->save();

        return redirect()->back()->with('success', "Order #{$order->order_number} assigned to {$deliveryBoy->name}.");
    }

    public function commissions($id)
    {
        $deliveryBoy = DeliveryBoy::with('agent')->findOrFail($id);

        // Fetch delivered orders
        $deliveredOrders = Order::where('delivery_boy_id', $deliveryBoy->id)
            ->where('order_status', 'delivered')
            ->latest()
            ->paginate(15);

        $totalDeliveredCount = Order::where('delivery_boy_id', $deliveryBoy->id)
            ->where('order_status', 'delivered')
            ->count();

        // Standard commission rate per delivery (e.g. ৳ 40 or from global address delivery charge)
        $ratePerDelivery = 40.00;
        $totalEarned = $totalDeliveredCount * $ratePerDelivery;

        return view('admin.delivery_boys.commissions', compact(
            'deliveryBoy',
            'deliveredOrders',
            'totalDeliveredCount',
            'ratePerDelivery',
            'totalEarned'
        ));
    }

    public function ledger($id)
    {
        $deliveryBoy = DeliveryBoy::with('agent')->findOrFail($id);

        $transactions = Ledger::where(function ($q) use ($deliveryBoy) {
            $q->where('party_name', $deliveryBoy->name)
              ->orWhere('reference_id', $deliveryBoy->phone)
              ->orWhere('reference_id', (string)$deliveryBoy->id);
        })
        ->latest()
        ->paginate(20);

        return view('admin.delivery_boys.ledger', compact('deliveryBoy', 'transactions'));
    }

    public function addTransaction(Request $request, $id)
    {
        $deliveryBoy = DeliveryBoy::findOrFail($id);

        $validated = $request->validate([
            'type'           => 'required|in:credit,debit',
            'amount'         => 'required|numeric|min:0.01',
            'category'       => 'required|string|max:50',
            'payment_method' => 'nullable|string|max:50',
            'note'           => 'nullable|string|max:255',
        ]);

        $amount = (float)$validated['amount'];
        $currentBalance = (float)$deliveryBoy->balance;

        if ($validated['type'] === 'credit') {
            $newBalance = $currentBalance + $amount;
        } else {
            $newBalance = $currentBalance - $amount;
        }

        $deliveryBoy->balance = $newBalance;
        $deliveryBoy->save();

        Ledger::create([
            'txn_number'     => 'TXN-DB-' . strtoupper(Str::random(8)),
            'type'           => $validated['type'],
            'category'       => $validated['category'],
            'amount'         => $amount,
            'balance_after'  => $newBalance,
            'party_type'     => 'delivery_boy',
            'party_name'     => $deliveryBoy->name,
            'reference_id'   => $deliveryBoy->phone,
            'payment_method' => $validated['payment_method'] ?? 'Cash',
            'note'           => $validated['note'] ?? 'Manual delivery boy balance adjustment',
        ]);

        return redirect()->back()->with('success', "Transaction recorded. New Balance: ৳ " . number_format($newBalance, 2));
    }
}
