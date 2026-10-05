<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallingRequest;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallingRequestApiController extends Controller
{
    /**
     * Submit instant calling/demand request (ফর্দ বা পণ্য রিকোয়েস্ট)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone'         => ['required', 'regex:/^(\+?8801|01)[3-9]\d{8}$/'],
            'notes'         => 'required|string|max:2000', // Customer's shopping list / fardo
            'product_name'  => 'nullable|string|max:150',
            'product_id'    => 'nullable|exists:products,id',
            'shop_id'       => 'nullable|exists:shops,id',
            'division'      => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'upazila'       => 'nullable|string|max:100',
            'pourashava'    => 'nullable|string|max:100',
            'ward'          => 'nullable|string|max:100',
        ]);

        // If specific shop not selected, find local shop in the customer's ward/pourashava
        if (empty($validated['shop_id']) && !empty($validated['upazila'])) {
            $shopQuery = Shop::where('status', 'active');
            if (!empty($validated['ward'])) {
                $matched = (clone $shopQuery)->where('ward', $validated['ward'])->first();
                if ($matched) $validated['shop_id'] = $matched->id;
            }
            if (empty($validated['shop_id']) && !empty($validated['pourashava'])) {
                $matched = (clone $shopQuery)->where('pourashava', $validated['pourashava'])->first();
                if ($matched) $validated['shop_id'] = $matched->id;
            }
            if (empty($validated['shop_id'])) {
                $matched = (clone $shopQuery)->where('upazila', $validated['upazila'])->first();
                if ($matched) $validated['shop_id'] = $matched->id;
            }
        }

        $callingReq = CallingRequest::create([
            'customer_name' => $validated['customer_name'],
            'phone'         => $validated['phone'],
            'notes'         => $validated['notes'],
            'product_name'  => $validated['product_name'] ?? 'ফর্দ / Instant Demand',
            'product_id'    => $validated['product_id'] ?? null,
            'shop_id'       => $validated['shop_id'] ?? null,
            'status'        => 'pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'আপনার কলিং রিকোয়েস্ট সফলভাবে গৃহীত হয়েছে! দ্রুততম সময়ে স্থানীয় শপ/এজেন্ট আপনার সাথে যোগাযোগ করবে।',
            'data'    => $callingReq,
        ], 201);
    }
}
