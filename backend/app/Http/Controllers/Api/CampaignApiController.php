<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignApiController extends Controller
{
    /**
     * Active & Featured Campaigns
     */
    public function index(): JsonResponse
    {
        $campaigns = Campaign::where('status', 'active')
            ->orderByDesc('is_featured')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $campaigns,
        ]);
    }

    /**
     * Campaign Details
     */
    public function show(string $idOrSlug): JsonResponse
    {
        $campaign = Campaign::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $campaign,
        ]);
    }
}
