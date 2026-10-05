<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\DeliveryBoy;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentPortalController extends Controller
{
    private function getAgent(): ?Agent
    {
        $user = Auth::user();
        if (!$user) return null;
        return Agent::where('user_id', $user->id)
            ->orWhere('phone', $user->phone)
            ->orWhere('email', $user->email)
            ->first();
    }

    public function dashboard()
    {
        $user = Auth::user();
        $agent = $this->getAgent();

        $district = $agent?->district ?? $user->district ?? 'Dhaka';
        $upazila  = $agent?->upazila ?? $user->upazila;

        $shopQuery = Shop::query();
        if ($upazila) {
            $shopQuery->where('upazila', $upazila);
        } elseif ($district) {
            $shopQuery->where('district', $district);
        }

        $totalShops = (clone $shopQuery)->count();
        $activeShops = (clone $shopQuery)->where('status', 'active')->count();
        $pendingShops = (clone $shopQuery)->where('status', 'pending')->count();

        $deliveryBoys = DeliveryBoy::where(function ($q) use ($agent, $district, $upazila) {
            if ($agent) $q->where('agent_id', $agent->id);
            if ($upazila) $q->orWhere('upazila', $upazila);
            if ($district) $q->orWhere('district', $district);
        })->count();

        $recentShops = (clone $shopQuery)->latest()->take(5)->get();

        return view('agent.dashboard', compact(
            'user',
            'agent',
            'district',
            'upazila',
            'totalShops',
            'activeShops',
            'pendingShops',
            'deliveryBoys',
            'recentShops'
        ));
    }

    public function shops(Request $request)
    {
        $agent = $this->getAgent();
        $user = Auth::user();
        $district = $agent?->district ?? $user->district;
        $upazila  = $agent?->upazila ?? $user->upazila;

        $query = Shop::query();
        if ($upazila) {
            $query->where('upazila', $upazila);
        } elseif ($district) {
            $query->where('district', $district);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $shops = $query->latest()->paginate(20)->withQueryString();

        return view('agent.shops', compact('shops', 'agent', 'district', 'upazila'));
    }

    public function deliveryBoys()
    {
        $agent = $this->getAgent();
        $user = Auth::user();
        $district = $agent?->district ?? $user->district;
        $upazila  = $agent?->upazila ?? $user->upazila;

        $riders = DeliveryBoy::where(function ($q) use ($agent, $district, $upazila) {
            if ($agent) $q->where('agent_id', $agent->id);
            if ($upazila) $q->orWhere('upazila', $upazila);
            if ($district) $q->orWhere('district', $district);
        })->latest()->paginate(20);

        return view('agent.delivery_boys', compact('riders', 'agent'));
    }
}
