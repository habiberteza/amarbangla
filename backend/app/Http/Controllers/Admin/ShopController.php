<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GlobalAddress;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Shop::query()->with(['categoryRel', 'subCategoryRel', 'user'])->withCount('products');

        // 1. Division Filter (supports single or comma-separated)
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

        // 7. Free Keyword Search (Name, Phone, Email, Address, House Address, etc.)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('house_address', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%")
                  ->orWhere('pourashava', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%");
            });
        }

        $shops = $query->latest()->paginate(25)->withQueryString();

        // Address hierarchy and dropdown datasets from actual shops
        $divisions = Shop::whereNotNull('division')->where('division', '!=', '')->distinct()->orderBy('division')->pluck('division');

        $districtQuery = Shop::whereNotNull('district')->where('district', '!=', '')->distinct()->orderBy('district');
        if ($request->filled('division')) {
            $selDivs = is_array($request->division) ? $request->division : explode(',', $request->division);
            $districtQuery->whereIn('division', array_filter(array_map('trim', $selDivs)));
        }
        $districts = $districtQuery->pluck('district');

        $upazilaQuery = Shop::whereNotNull('upazila')->where('upazila', '!=', '')->distinct()->orderBy('upazila');
        if ($request->filled('district')) {
            $selDists = is_array($request->district) ? $request->district : explode(',', $request->district);
            $upazilaQuery->whereIn('district', array_filter(array_map('trim', $selDists)));
        } elseif ($request->filled('division')) {
            $selDivs = is_array($request->division) ? $request->division : explode(',', $request->division);
            $upazilaQuery->whereIn('division', array_filter(array_map('trim', $selDivs)));
        }
        $upazilas = $upazilaQuery->pluck('upazila');

        $pourashavas = Shop::whereNotNull('pourashava')
            ->where('pourashava', '!=', '')
            ->distinct()
            ->orderBy('pourashava')
            ->pluck('pourashava');

        $rawWards = Shop::whereNotNull('ward')
            ->where('ward', '!=', '')
            ->distinct()
            ->pluck('ward')
            ->toArray();
        natsort($rawWards);
        $wards = array_values($rawWards);

        $hierarchy = (new GlobalAddressController())->getHierarchy();

        return view('admin.shops.index', compact(
            'shops',
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
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->orderBy('name')->get();
        $vendors = User::where('role', 'vendor')->orWhere('role', 'super_admin')->orderBy('name')->get();

        return view('admin.shops.create', compact('hierarchy', 'categories', 'subcategories', 'vendors'));
    }

    public function store(Request $request)
    {
        // Support both "mobile" and "phone"
        if (!$request->filled('phone') && $request->filled('mobile')) {
            $request->merge(['phone' => $request->mobile]);
        }

        // Support both sale_commission and commission_rate
        if (!$request->filled('commission_rate') && $request->filled('sale_commission')) {
            $request->merge(['commission_rate' => $request->sale_commission]);
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'password'        => 'nullable|string|min:4',
            'con_password'    => 'nullable|same:password',
            'phone'           => 'nullable|string|max:30',
            'status'          => 'nullable',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'division'        => 'nullable|string|max:100',
            'district'        => 'nullable|string|max:100',
            'upazila'         => 'nullable|string|max:100',
            'pourashava'      => 'nullable|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'address'         => 'nullable|string',
            'house_address'   => 'nullable|string',
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'category'        => 'nullable|string|max:255',
            'sub_category'    => 'nullable|string|max:255',
            'shop_type'       => 'nullable|string|max:100',
            'priority_type'   => 'nullable|string|max:100',
            'home_feature'    => 'nullable',
            'comment'         => 'nullable|string',
            'balance'         => 'nullable|numeric',
            'logo'            => 'nullable|string',
            'image'           => 'nullable|string',
            'logo_file'       => 'nullable|image|max:1024',
            'profile_image'   => 'nullable|image|max:1024',
        ]);

        // Status normalization (active / inactive / pending / suspended)
        $rawStatus = $request->status ?? 'active';
        if ($rawStatus === '1' || $rawStatus === 1 || $rawStatus === 'active') {
            $status = 'active';
        } elseif ($rawStatus === '0' || $rawStatus === 0 || $rawStatus === 'inactive') {
            $status = 'inactive';
        } else {
            $status = $rawStatus;
        }

        // File uploads for Logo and ProfileImage
        $logoPath = $request->logo ?? null;
        if ($request->hasFile('logo_file')) {
            $destDir = public_path('uploads/shops');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'shop_logo_' . time() . '_' . Str::random(6) . '.' . $request->file('logo_file')->getClientOriginalExtension();
            $request->file('logo_file')->move($destDir, $filename);
            $logoPath = 'uploads/shops/' . $filename;
        }

        $imagePath = $request->image ?? null;
        if ($request->hasFile('profile_image')) {
            $destDir = public_path('uploads/shops');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'shop_profile_' . time() . '_' . Str::random(6) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move($destDir, $filename);
            $imagePath = 'uploads/shops/' . $filename;
        }

        // Resolve or create user account for login credentials
        $user = null;
        if ($request->filled('email')) {
            $existingUser = User::where('email', $request->email)->first();
            if (!$existingUser) {
                $userPhone = $validated['phone'] ?? null;
                if ($userPhone && User::where('phone', $userPhone)->exists()) {
                    $userPhone = null;
                }
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'phone'    => $userPhone,
                    'password' => Hash::make($request->password ?? Str::random(12)),
                    'role'     => 'vendor',
                    'status'   => 'active',
                ]);
            } else {
                $user = $existingUser;
                if ($request->filled('password')) {
                    $user->update(['password' => Hash::make($request->password)]);
                }
            }
        } elseif ($request->filled('user_id')) {
            $user = User::find($request->user_id);
        }

        // Category names fallback
        $categoryName = $request->category;
        if ($request->filled('category_id')) {
            $cat = Category::find($request->category_id);
            if ($cat) $categoryName = $cat->name;
        }
        $subCategoryName = $request->sub_category;
        if ($request->filled('sub_category_id')) {
            $subCat = Category::find($request->sub_category_id);
            if ($subCat) $subCategoryName = $subCat->name;
        }

        $shop = Shop::create([
            'user_id'         => $user ? $user->id : null,
            'name'            => $validated['name'],
            'slug'            => Str::slug($validated['name']) . '-' . Str::random(5),
            'type'            => $validated['shop_type'] ?? 'regular',
            'shop_type'       => $validated['shop_type'] ?? 'regular',
            'priority_type'   => $validated['priority_type'] ?? 'general',
            'category_id'     => $validated['category_id'] ?? null,
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'category'        => $categoryName,
            'sub_category'    => $subCategoryName,
            'logo'            => $logoPath,
            'image'           => $imagePath,
            'phone'           => $validated['phone'] ?? null,
            'email'           => $validated['email'] ?? null,
            'password'        => $request->filled('password') ? Hash::make($request->password) : null,
            'address'         => $validated['address'] ?? null,
            'house_address'   => $validated['house_address'] ?? null,
            'division'        => $validated['division'] ?? null,
            'district'        => $validated['district'] ?? null,
            'upazila'         => $validated['upazila'] ?? null,
            'pourashava'      => $validated['pourashava'] ?? null,
            'ward'            => $validated['ward'] ?? null,
            'balance'         => $validated['balance'] ?? 0.00,
            'commission_rate' => $validated['commission_rate'] ?? 5.00,
            'home_feature'    => $request->boolean('home_feature') || $request->has('home_feature'),
            'is_featured'     => $request->boolean('home_feature') || $request->has('is_featured'),
            'status'          => $status,
            'comment'         => $validated['comment'] ?? null,
        ]);

        $target = $request->is('super_admin/*') ? url('/super_admin/shops') : route('admin.shops.index');
        return redirect($target)->with('success', 'Shop "' . $shop->name . '" created successfully.');
    }

    public function edit($id)
    {
        $shop = $id instanceof Shop ? $id : Shop::findOrFail($id);
        $hierarchy = (new GlobalAddressController())->getHierarchy();
        $categories = Category::whereNull('parent_id')->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->orderBy('name')->get();
        $vendors = User::where('role', 'vendor')->orWhere('role', 'super_admin')->orderBy('name')->get();

        return view('admin.shops.edit', compact('shop', 'hierarchy', 'categories', 'subcategories', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $shop = $id instanceof Shop ? $id : Shop::findOrFail($id);

        if (!$request->filled('phone') && $request->filled('mobile')) {
            $request->merge(['phone' => $request->mobile]);
        }
        if (!$request->filled('commission_rate') && $request->filled('sale_commission')) {
            $request->merge(['commission_rate' => $request->sale_commission]);
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'password'        => 'nullable|string|min:4',
            'con_password'    => 'nullable|same:password',
            'phone'           => 'nullable|string|max:30',
            'status'          => 'nullable',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'division'        => 'nullable|string|max:100',
            'district'        => 'nullable|string|max:100',
            'upazila'         => 'nullable|string|max:100',
            'pourashava'      => 'nullable|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'address'         => 'nullable|string',
            'house_address'   => 'nullable|string',
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'category'        => 'nullable|string|max:255',
            'sub_category'    => 'nullable|string|max:255',
            'shop_type'       => 'nullable|string|max:100',
            'priority_type'   => 'nullable|string|max:100',
            'home_feature'    => 'nullable',
            'comment'         => 'nullable|string',
            'balance'         => 'nullable|numeric',
            'logo'            => 'nullable|string',
            'image'           => 'nullable|string',
            'logo_file'       => 'nullable|image|max:1024',
            'profile_image'   => 'nullable|image|max:1024',
        ]);

        $rawStatus = $request->status;
        if ($rawStatus !== null) {
            if ($rawStatus === '1' || $rawStatus === 1 || $rawStatus === 'active') {
                $shop->status = 'active';
            } elseif ($rawStatus === '0' || $rawStatus === 0 || $rawStatus === 'inactive') {
                $shop->status = 'inactive';
            } else {
                $shop->status = $rawStatus;
            }
        }

        // Handle file uploads
        if ($request->hasFile('logo_file')) {
            $destDir = public_path('uploads/shops');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'shop_logo_' . time() . '_' . Str::random(6) . '.' . $request->file('logo_file')->getClientOriginalExtension();
            $request->file('logo_file')->move($destDir, $filename);
            $shop->logo = 'uploads/shops/' . $filename;
        } elseif ($request->filled('logo')) {
            $shop->logo = $request->logo;
        }

        if ($request->hasFile('profile_image')) {
            $destDir = public_path('uploads/shops');
            if (!File::isDirectory($destDir)) {
                File::makeDirectory($destDir, 0755, true, true);
            }
            $filename = 'shop_profile_' . time() . '_' . Str::random(6) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move($destDir, $filename);
            $shop->image = 'uploads/shops/' . $filename;
        } elseif ($request->filled('image')) {
            $shop->image = $request->image;
        }

        // Update fields
        $shop->name = $validated['name'];
        if ($request->filled('phone')) $shop->phone = $validated['phone'];
        if ($request->filled('email')) $shop->email = $validated['email'];
        if ($request->has('address')) $shop->address = $validated['address'];
        if ($request->has('house_address')) $shop->house_address = $validated['house_address'];
        if ($request->has('comment')) $shop->comment = $validated['comment'];
        if ($request->has('commission_rate')) $shop->commission_rate = $validated['commission_rate'];
        if ($request->has('balance')) $shop->balance = $validated['balance'];

        if ($request->has('division')) $shop->division = $validated['division'];
        if ($request->has('district')) $shop->district = $validated['district'];
        if ($request->has('upazila')) $shop->upazila = $validated['upazila'];
        if ($request->has('pourashava')) $shop->pourashava = $validated['pourashava'];
        if ($request->has('ward')) $shop->ward = $validated['ward'];

        if ($request->has('category_id')) $shop->category_id = $validated['category_id'] ?: null;
        if ($request->has('sub_category_id')) $shop->sub_category_id = $validated['sub_category_id'] ?: null;
        if ($request->filled('category')) $shop->category = $validated['category'];
        if ($request->filled('sub_category')) $shop->sub_category = $validated['sub_category'];
        if ($request->filled('shop_type')) {
            $shop->shop_type = $validated['shop_type'];
            $shop->type = $validated['shop_type'];
        }
        if ($request->filled('priority_type')) $shop->priority_type = $validated['priority_type'];
        if ($request->has('home_feature')) {
            $shop->home_feature = $request->boolean('home_feature') || $request->home_feature == '1';
            $shop->is_featured = $shop->home_feature;
        }

        // Update User Password / Email if specified
        if ($request->filled('password')) {
            $hashed = Hash::make($request->password);
            $shop->password = $hashed;

            if ($shop->user) {
                $shop->user->update([
                    'password' => $hashed,
                    'email'    => $shop->email ?? $shop->user->email,
                ]);
            } elseif ($shop->email) {
                $userPhone = $shop->phone;
                if ($userPhone && User::where('phone', $userPhone)->exists()) {
                    $userPhone = null;
                }
                $newUser = User::firstOrCreate(
                    ['email' => $shop->email],
                    [
                        'name'     => $shop->name,
                        'phone'    => $userPhone,
                        'role'     => 'vendor',
                        'status'   => 'active',
                        'password' => $hashed,
                    ]
                );
                $shop->user_id = $newUser->id;
            }
        }

        $shop->save();

        $target = $request->is('super_admin/*') ? url('/super_admin/shops') : route('admin.shops.index');
        return redirect($target)->with('success', 'Shop "' . $shop->name . '" updated successfully.');
    }

    // 1. Modular Update: Registration Info / Personal
    public function updatePersonalInfo(Request $request, $id = null)
    {
        $shopId = $id ?? $request->id ?? $request->shop_id;
        $shop = Shop::findOrFail($shopId);

        if (!$request->filled('phone') && $request->filled('mobile')) {
            $request->merge(['phone' => $request->mobile]);
        }
        if (!$request->filled('commission_rate') && $request->filled('sale_commission')) {
            $request->merge(['commission_rate' => $request->sale_commission]);
        }

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|max:255',
            'status'          => 'nullable',
            'phone'           => 'nullable|string|max:30',
            'address'         => 'nullable|string',
            'comment'         => 'nullable|string',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $shop->name = $validated['name'];
        $shop->email = $validated['email'] ?? $shop->email;
        $shop->phone = $validated['phone'] ?? $shop->phone;
        $shop->address = $validated['address'] ?? $shop->address;
        $shop->comment = $validated['comment'] ?? $shop->comment;
        $shop->commission_rate = $validated['commission_rate'] ?? $shop->commission_rate;

        if ($request->has('status')) {
            $raw = $request->status;
            $shop->status = ($raw === '1' || $raw === 1 || $raw === 'active') ? 'active' : (($raw === '0' || $raw === 0 || $raw === 'inactive') ? 'inactive' : $raw);
        }

        $shop->save();

        return back()->with('success', 'Personal & Registration Info updated successfully.');
    }

    // 2. Modular Update: Photo (Logo & ProfileImage)
    public function updatePhoto(Request $request, $id = null)
    {
        $shopId = $id ?? $request->id ?? $request->shop_id;
        $shop = Shop::findOrFail($shopId);

        $request->validate([
            'logo_file'     => 'nullable|image|max:1024',
            'profile_image' => 'nullable|image|max:1024',
        ], [
            'logo_file.max'     => 'Logo file size may not exceed 1024KB.',
            'profile_image.max' => 'Profile image size may not exceed 1024KB.',
        ]);

        $destDir = public_path('uploads/shops');
        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true, true);
        }

        if ($request->hasFile('logo_file')) {
            $filename = 'shop_logo_' . time() . '_' . Str::random(6) . '.' . $request->file('logo_file')->getClientOriginalExtension();
            $request->file('logo_file')->move($destDir, $filename);
            $shop->logo = 'uploads/shops/' . $filename;
        }

        if ($request->hasFile('profile_image')) {
            $filename = 'shop_profile_' . time() . '_' . Str::random(6) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move($destDir, $filename);
            $shop->image = 'uploads/shops/' . $filename;
        }

        $shop->save();

        return back()->with('success', 'Shop Logo & Profile Image updated successfully.');
    }

    // 3. Modular Update: User Update (Email, Password, Confirm Password)
    public function updateUser(Request $request, $id = null)
    {
        $shopId = $id ?? $request->id ?? $request->shop_id;
        $shop = Shop::findOrFail($shopId);

        $request->validate([
            'email'        => 'required|email|max:255',
            'password'     => 'nullable|string|min:4',
            'con_password' => 'nullable|same:password',
        ]);

        $shop->email = $request->email;

        if ($request->filled('password')) {
            $hashed = Hash::make($request->password);
            $shop->password = $hashed;

            if ($shop->user) {
                $shop->user->update([
                    'email'    => $request->email,
                    'password' => $hashed,
                ]);
            } else {
                $userPhone = $shop->phone;
                if ($userPhone && User::where('phone', $userPhone)->exists()) {
                    $userPhone = null;
                }
                $user = User::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'name'     => $shop->name,
                        'phone'    => $userPhone,
                        'role'     => 'vendor',
                        'status'   => 'active',
                        'password' => $hashed,
                    ]
                );
                $shop->user_id = $user->id;
            }
        } elseif ($shop->user) {
            $shop->user->update(['email' => $request->email]);
        }

        $shop->save();

        return back()->with('success', 'User login credentials updated successfully.');
    }

    // 4. Modular Update: Address (Division, District, Upazila, Pourashava/Union, Ward, House Address)
    public function updateAddress(Request $request, $id = null)
    {
        $shopId = $id ?? $request->id ?? $request->shop_id;
        $shop = Shop::findOrFail($shopId);

        $validated = $request->validate([
            'division'      => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'upazila'       => 'nullable|string|max:100',
            'pourashava'    => 'nullable|string|max:100',
            'ward'          => 'nullable|string|max:100',
            'house_address' => 'nullable|string',
        ]);

        $shop->division = $validated['division'] ?? null;
        $shop->district = $validated['district'] ?? null;
        $shop->upazila = $validated['upazila'] ?? null;
        $shop->pourashava = $validated['pourashava'] ?? null;
        $shop->ward = $validated['ward'] ?? null;
        $shop->house_address = $validated['house_address'] ?? null;

        $shop->save();

        return back()->with('success', 'Shop address details updated successfully.');
    }

    // 5. Modular Update: Shop Category (Category, SubCategory, Shop type, Priority type, Home feature)
    public function updateCategory(Request $request, $id = null)
    {
        $shopId = $id ?? $request->id ?? $request->shop_id;
        $shop = Shop::findOrFail($shopId);

        $validated = $request->validate([
            'category_id'     => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'category'        => 'nullable|string|max:255',
            'sub_category'    => 'nullable|string|max:255',
            'shop_type'       => 'nullable|string|max:100',
            'priority_type'   => 'nullable|string|max:100',
            'home_feature'    => 'nullable',
        ]);

        $shop->category_id = $validated['category_id'] ?? null;
        $shop->sub_category_id = $validated['sub_category_id'] ?? null;

        if ($request->filled('category_id')) {
            $cat = Category::find($request->category_id);
            if ($cat) $shop->category = $cat->name;
        } elseif ($request->filled('category')) {
            $shop->category = $request->category;
        }

        if ($request->filled('sub_category_id')) {
            $subCat = Category::find($request->sub_category_id);
            if ($subCat) $shop->sub_category = $subCat->name;
        } elseif ($request->filled('sub_category')) {
            $shop->sub_category = $request->sub_category;
        }

        if ($request->filled('shop_type')) {
            $shop->shop_type = $request->shop_type;
            $shop->type = $request->shop_type;
        }

        if ($request->filled('priority_type')) {
            $shop->priority_type = $request->priority_type;
        }

        $shop->home_feature = $request->boolean('home_feature') || $request->home_feature == '1';
        $shop->is_featured = $shop->home_feature;

        $shop->save();

        return back()->with('success', 'Shop categories and features updated successfully.');
    }

    // Toggle Status
    public function toggleStatus(Request $request, $id)
    {
        $shop = $id instanceof Shop ? $id : Shop::findOrFail($id);
        $newStatus = ($shop->status === 'active' || $shop->status === '1') ? 'inactive' : 'active';
        $shop->update(['status' => $newStatus]);

        return back()->with('success', "Shop \"{$shop->name}\" status changed to {$newStatus}.");
    }

    public function updateStatus(Request $request, $id)
    {
        return $this->toggleStatus($request, $id);
    }

    // Destroy Shop
    public function destroy($id)
    {
        $shop = $id instanceof Shop ? $id : Shop::findOrFail($id);
        $shop->delete();

        return redirect()->route('admin.shops.index')->with('success', 'Shop deleted successfully.');
    }

    public function destroyLegacy($id)
    {
        return $this->destroy($id);
    }

    // AJAX Endpoint for Dynamic Subcategories
    public function subcategories(Request $request, $parentId)
    {
        $subcategories = Category::where('parent_id', $parentId)->orderBy('name')->get(['id', 'name']);
        return response()->json($subcategories);
    }

    // Export: CSV, Excel (xl), PDF/Print
    public function export(Request $request, $format = 'csv')
    {
        $query = Shop::query()->with(['categoryRel', 'subCategoryRel', 'user']);

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
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('house_address', 'like', "%{$search}%");
            });
        }

        ini_set('memory_limit', '512M');

        // Print / PDF format view
        if ($format === 'print' || $format === 'pdf') {
            $shops = $query->latest()->limit(500)->get();
            return view('admin.shops.print', compact('shops'));
        }

        $shops = $query->latest()->get();

        // CSV or Excel format
        $ext = ($format === 'excel' || $format === 'xl') ? 'xls' : 'csv';
        $contentType = ($ext === 'xls') ? 'application/vnd.ms-excel; charset=UTF-8' : 'text/csv; charset=UTF-8';
        $filename = 'shops_export_' . date('Y-m-d_His') . '.' . ($ext === 'xls' ? 'csv' : 'csv');

        $headers = [
            'Content-Type'        => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($shops) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM

            fputcsv($handle, [
                'No',
                'Shop Name',
                'Email',
                'Mobile/Phone',
                'Balance (BDT)',
                'Status',
                'Commission Rate (%)',
                'Shop Type',
                'Category',
                'SubCategory',
                'Division',
                'District',
                'Upazila',
                'Pourashava/Union',
                'Ward',
                'House Address',
                'Full Address',
                'Created At'
            ]);

            $index = 1;
            foreach ($shops as $shop) {
                fputcsv($handle, [
                    $index++,
                    $shop->name,
                    $shop->email ?? 'N/A',
                    $shop->phone ?? 'N/A',
                    number_format($shop->balance, 2, '.', ''),
                    ucfirst($shop->status),
                    $shop->commission_rate . '%',
                    $shop->shop_type ?? $shop->type,
                    $shop->category ?? ($shop->categoryRel->name ?? 'N/A'),
                    $shop->sub_category ?? ($shop->subCategoryRel->name ?? 'N/A'),
                    $shop->division ?? '',
                    $shop->district ?? '',
                    $shop->upazila ?? '',
                    $shop->pourashava ?? '',
                    $shop->ward ?? '',
                    $shop->house_address ?? '',
                    $shop->address ?? '',
                    $shop->created_at ? $shop->created_at->format('Y-m-d H:i') : ''
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
