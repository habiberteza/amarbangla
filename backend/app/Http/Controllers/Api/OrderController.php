<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'division' => 'nullable|string',
            'district' => 'nullable|string',
            'upazila' => 'nullable|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,bkash,nagad,rocket,bank',
            'transaction_id' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_info' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = (float) ($product->discount_price ?? $product->price);
                $qty = (int) $item['quantity'];
                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'shop_id' => $product->shop_id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'variant_info' => $item['variant_info'] ?? null,
                    'price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                    'status' => 'pending',
                ];
            }

            // Calculate shipping fee based on location
            $isInsideDhaka = strtolower($validated['district'] ?? '') === 'dhaka';
            $shippingFee = $isInsideDhaka
                ? (float) Setting::get('delivery_charge_inside_dhaka', 60.00)
                : (float) Setting::get('delivery_charge_outside_dhaka', 120.00);

            $totalAmount = $subtotal + $shippingFee;

            $userId = $request->user()?->id;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'division' => $validated['division'] ?? null,
                'district' => $validated['district'] ?? null,
                'upazila' => $validated['upazila'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'discount' => 0.00,
                'shipping_fee' => $shippingFee,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'unpaid' : 'paid',
                'order_status' => 'pending',
                'transaction_id' => $validated['transaction_id'] ?? null,
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully!',
                'data' => $order->load(['items.product', 'items.shop']),
            ], 201);
        });
    }

    public function show(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product', 'items.shop'])
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    public function userOrders(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $orders = Order::where('user_id', $user->id)
            ->with(['items.product', 'items.shop'])
            ->orderByDesc('id')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }
}
