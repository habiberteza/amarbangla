<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Safely verify password against Bcrypt, SHA1, MD5, or plain text
     */
    protected function verifyPassword(string $plainPassword, ?string $hashedPassword, ?string $plainPass = null): bool
    {
        if (empty($hashedPassword) && empty($plainPass)) {
            return false;
        }

        // 1. Plain text comparison if pass column exists
        if (!empty($plainPass) && $plainPassword === $plainPass) {
            return true;
        }

        if (empty($hashedPassword)) {
            return false;
        }

        // 2. Legacy SHA1 (40 hex chars)
        if (strlen($hashedPassword) === 40 && ctype_xdigit($hashedPassword)) {
            if (sha1($plainPassword) === strtolower($hashedPassword)) {
                return true;
            }
        }

        // 3. Legacy MD5 (32 hex chars)
        if (strlen($hashedPassword) === 32 && ctype_xdigit($hashedPassword)) {
            if (md5($plainPassword) === strtolower($hashedPassword)) {
                return true;
            }
        }

        // 4. Standard Bcrypt / Argon2 / Laravel Hash
        if (str_starts_with($hashedPassword, '$2') || str_starts_with($hashedPassword, '$argon')) {
            try {
                if (Hash::check($plainPassword, $hashedPassword)) {
                    return true;
                }
            } catch (\Throwable $e) {
                // Ignore algorithm check error
            }
        }

        // 5. Fallback string comparisons
        if (sha1($plainPassword) === $hashedPassword || md5($plainPassword) === $hashedPassword) {
            return true;
        }

        return false;
    }

    /**
     * Customer & User Registration
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:25',
            'email'    => 'nullable|string|email|max:191',
        ]);

        $rawPhone = trim((string) $request->input('phone', ''));
        $rawEmail = trim((string) $request->input('email', ''));

        if (empty($rawPhone) && empty($rawEmail)) {
            return response()->json([
                'status' => 'error',
                'message' => 'মোবাইল নম্বর অথবা ইমেইল যেকোনো একটি দেওয়া আবশ্যক (Either phone number or email is required)',
            ], 422);
        }

        // Standardize Bangladeshi phone number (e.g. 017XXXXXXXX)
        $standardPhone = null;
        $intMobile = null;
        if (!empty($rawPhone)) {
            $digits = preg_replace('/[^0-9]/', '', $rawPhone);
            if (strlen($digits) < 10) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'সঠিক মোবাইল নম্বর প্রদান করুন (Please enter a valid phone number)',
                ], 422);
            }
            $standardPhone = '0' . ltrim(substr($digits, -10), '0');
            $intMobile = (int) substr($digits, -10);
        }

        // Check if phone already registered in users or customers
        if ($standardPhone) {
            $userPhoneExists = User::where('phone', $standardPhone)
                ->orWhere('mobile', $intMobile)
                ->exists();

            $customerPhoneExists = Customer::where('phone', $standardPhone)
                ->orWhere('mobile', $intMobile)
                ->exists();

            if ($userPhoneExists || $customerPhoneExists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'এই মোবাইল নম্বরটি দিয়ে ইতিমধ্যে একটি অ্যাকাউন্ট রয়েছে। দয়া করে লগইন করুন। (This phone number is already registered. Please login.)',
                ], 422);
            }
        }

        // Check if email already registered
        if (!empty($rawEmail)) {
            $emailExists = User::where('email', $rawEmail)->exists()
                || Customer::where('email', $rawEmail)->exists();

            if ($emailExists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'এই ইমেইলটি দিয়ে ইতিমধ্যে অ্যাকাউন্ট রয়েছে। (This email is already registered.)',
                ], 422);
            }
        }

        $finalEmail = !empty($rawEmail) ? $rawEmail : ($standardPhone . '@amarbangla.local');

        // Create User account (primary for Sanctum API auth)
        $user = User::create([
            'name'       => $request->name,
            'email'      => $finalEmail,
            'phone'      => $standardPhone,
            'mobile'     => $intMobile,
            'role'       => 'customer',
            'role_id'    => 1,
            'status'     => 'active',
            'password'   => Hash::make($request->password),
            'address'    => $request->address ?? '',
            'division'   => $request->division ?? null,
            'district'   => $request->district ?? null,
            'upazila'    => $request->upazila ?? null,
            'pourashava' => $request->pourashava ?? null,
            'ward'       => $request->ward ?? null,
        ]);

        // Sync Customer profile (for storefront orders and admin customer lists)
        $customer = Customer::create([
            'user_id'          => $user->user_id,
            'customer_name'    => $request->name,
            'name'             => $request->name,
            'phone'            => $standardPhone,
            'mobile'           => $intMobile,
            'email'            => $finalEmail,
            'password'         => sha1($request->password),
            'balance'          => 0.00,
            'address'          => $request->address ?? '',
            'division'         => $request->division ?? null,
            'district'         => $request->district ?? null,
            'upazila'          => $request->upazila ?? null,
            'status'           => 'active',
            'customer_type_id' => 1,
            'cus_type_id'      => 1,
        ]);

        $user->customer_id = $customer->customer_id;
        $user = $this->formatUserResponse($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে (Registration successful)',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Customer & User Login (Supports phone 01714070770, email, and legacy password hashes)
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $rawLogin = trim((string) $request->input('email', $request->input('phone', '')));
        $password = (string) $request->input('password', '');

        // Extract and normalize phone numbers if applicable
        $digits = preg_replace('/[^0-9]/', '', $rawLogin);
        $standardPhone = null;
        $intMobile = null;

        if (strlen($digits) >= 10) {
            $last10 = substr($digits, -10);
            $standardPhone = '0' . $last10;
            $intMobile = (int) $last10;
        } elseif (is_numeric($rawLogin) && strlen($rawLogin) > 5) {
            $intMobile = (int) $rawLogin;
            $standardPhone = '0' . ltrim($rawLogin, '0');
        }

        // 1. Search in users table first
        $user = User::where(function ($q) use ($rawLogin, $standardPhone, $intMobile) {
            $q->where('email', $rawLogin)
              ->orWhere('phone', $rawLogin);

            if ($standardPhone) {
                $q->orWhere('phone', $standardPhone);
            }
            if ($intMobile) {
                $q->orWhere('mobile', $intMobile);
            }
        })->first();

        // 2. If not found in users, check customers table
        if (! $user) {
            $customer = Customer::where(function ($q) use ($rawLogin, $standardPhone, $intMobile) {
                if ($standardPhone) {
                    $q->where('phone', $standardPhone);
                }
                if ($intMobile) {
                    $q->orWhere('mobile', $intMobile);
                }
                $q->orWhere('email', $rawLogin);
            })->first();

            if ($customer) {
                $passwordMatches = $this->verifyPassword($password, $customer->password, $customer->pass);

                if ($passwordMatches) {
                    if ($customer->user_id) {
                        $user = User::find($customer->user_id);
                    }

                    if (! $user) {
                        $userPhone = $customer->phone ?: $standardPhone;
                        $userMobile = $customer->mobile ?: $intMobile;
                        $userEmail = $customer->email ?: ($userPhone ? ($userPhone . '@amarbangla.local') : ('customer_' . $customer->customer_id . '@amarbangla.local'));

                        $user = User::where('phone', $userPhone)
                            ->orWhere('mobile', $userMobile)
                            ->orWhere('email', $userEmail)
                            ->first();

                        if (! $user) {
                            $user = User::create([
                                'name'       => $customer->customer_name ?: ($customer->name ?: 'Customer'),
                                'email'      => $userEmail,
                                'phone'      => $userPhone,
                                'mobile'     => $userMobile,
                                'role'       => 'customer',
                                'role_id'    => 1,
                                'status'     => 'active',
                                'password'   => Hash::make($password),
                                'address'    => $customer->address,
                                'balance'    => $customer->balance ?? 0,
                                'division'   => $customer->division,
                                'district'   => $customer->district,
                                'upazila'    => $customer->upazila,
                            ]);
                        }
                    }

                    // Link customer to user
                    $customer->user_id = $user->user_id;
                    $customer->save();
                }
            }
        }

        // 3. If still no user found, return 401
        if (! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'ইমেইল/মোবাইল নম্বর অথবা পাসওয়ার্ড সঠিক নয় (Invalid email/phone or password)',
            ], 401);
        }

        // 4. Verify password for the user
        $validPassword = $this->verifyPassword($password, $user->password, $user->pass);

        if (! $validPassword) {
            return response()->json([
                'status' => 'error',
                'message' => 'ইমেইল/মোবাইল নম্বর অথবা পাসওয়ার্ড সঠিক নয় (Invalid email/phone or password)',
            ], 401);
        }

        // 5. Seamlessly upgrade legacy password hash to bcrypt if it wasn't bcrypt
        if (! (str_starts_with((string)$user->password, '$2') || str_starts_with((string)$user->password, '$argon'))) {
            $user->password = Hash::make($password);
            $user->save();
        }

        // 6. Check account status
        if (in_array(strtolower((string) $user->status), ['0', 'inactive', 'suspended', 'blocked', 'banned'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'আপনার অ্যাকাউন্টটি স্থগিত করা হয়েছে (Your account has been deactivated)',
            ], 403);
        }

        $user = $this->formatUserResponse($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'সফলভাবে লগইন হয়েছে (Login successful)',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user = $this->formatUserResponse($user);
            $user->load(['shops', 'orders']);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    /**
     * Enrich user model with customer profile and resolved geographical address names
     */
    protected function formatUserResponse(User $user): User
    {
        $customer = Customer::where('user_id', $user->user_id)
            ->orWhere(function ($q) use ($user) {
                if ($user->phone) $q->where('phone', $user->phone);
                if ($user->mobile) $q->orWhere('mobile', $user->mobile);
            })->first();

        $div = $user->division ?: ($customer->division ?? null);
        $dst = $user->district ?: ($customer->district ?? null);
        $upz = $user->upazila ?: ($customer->upazila ?? null);
        $poura = $user->pourashava ?: ($customer->pourashava ?? null);
        $ward = $user->ward ?: ($customer->ward ?? null);
        $gaId = $user->global_address_id ?: ($customer->global_address_id ?? null);

        // If global_address_id exists, fetch and resolve from global_addresses table
        if ($gaId) {
            $ga = DB::table('global_address')->where('global_address_id', $gaId)->first();
            if ($ga) {
                if (!$div && $ga->division) $div = $ga->division;
                if (!$dst && ($ga->district || $ga->zila)) $dst = $ga->district ?: $ga->zila;
                if (!$upz && $ga->upazila) $upz = $ga->upazila;
                if (!$poura && $ga->pourashava) $poura = $ga->pourashava;
                if (!$ward && $ga->ward) $ward = $ga->ward;
            }
        }

        // Convert numeric IDs to text names if needed
        $resolvedDivision = is_numeric($div) ? divisionname($div) : $div;
        $resolvedDistrict = is_numeric($dst) ? districtname($dst) : $dst;
        $resolvedUpazila = is_numeric($upz) ? upazilaname($upz) : $upz;
        $resolvedPourashava = pourashavaUnionName($poura);

        $user->division = $resolvedDivision ?: $div;
        $user->district = $resolvedDistrict ?: $dst;
        $user->upazila = $resolvedUpazila ?: $upz;
        $user->pourashava = $resolvedPourashava ?: $poura;
        $user->ward = $ward;
        $user->global_address_id = $gaId;

        if ($customer) {
            $user->customer_id = $customer->customer_id;
            if (!isset($user->balance) || $user->balance == 0) {
                $user->balance = $customer->balance;
            }
        }

        return $user;
    }
}
