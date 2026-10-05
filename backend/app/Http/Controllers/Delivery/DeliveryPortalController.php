<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryPortalController extends Controller
{
    private function getRider(): ?DeliveryBoy
    {
        $user = Auth::user();
        if (!$user) return null;
        return DeliveryBoy::where('user_id', $user->id)
            ->orWhere('phone', $user->phone)
            ->orWhere('email', $user->email)
            ->first();
    }

    public function dashboard()
    {
        $user = Auth::user();
        $rider = $this->getRider();

        $district = $rider?->district ?? $user->district ?? 'Dhaka';
        $upazila  = $rider?->upazila ?? $user->upazila;

        // Neighborhood running orders
        $runningOrders = Order::whereIn('order_status', ['confirmed', 'processing', 'out_for_delivery'])
            ->where(function ($q) use ($district, $upazila) {
                if ($upazila) $q->where('upazila', $upazila);
                if ($district) $q->orWhere('district', $district);
            })
            ->with(['items.shop', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $completedOrdersCount = Order::where('order_status', 'delivered')->count();
        $cashInHand = $rider?->cash_in_hand ?? 0.00;
        $balance = $rider?->balance ?? 0.00;

        return view('delivery.dashboard', compact(
            'user',
            'rider',
            'district',
            'upazila',
            'runningOrders',
            'completedOrdersCount',
            'cashInHand',
            'balance'
        ));
    }

    public function acceptOrder(Order $order)
    {
        $rider = $this->getRider();
        $order->update([
            'order_status'    => 'out_for_delivery',
            'delivery_boy_id' => $rider?->id,
        ]);

        return back()->with('success', "অর্ডার #{$order->order_number} সফলভাবে পিকআপের জন্য গ্রহণ করা হয়েছে!");
    }

    public function completeOrder(Order $order)
    {
        $rider = $this->getRider();
        $order->update([
            'order_status'   => 'delivered',
            'payment_status' => 'paid',
        ]);

        if ($rider) {
            $deliveryFee = $order->shipping_fee ?? 60.00;
            $rider->increment('balance', $deliveryFee);
            if ($order->payment_method === 'cod') {
                $rider->increment('cash_in_hand', $order->total_amount);
            }
        }

        return back()->with('success', "অর্ডার #{$order->order_number} সফলভাবে ডেলিভারি সম্পন্ন হয়েছে!");
    }
}
