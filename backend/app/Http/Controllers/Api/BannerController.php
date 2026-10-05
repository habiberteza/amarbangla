<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('position');

        return response()->json([
            'status' => 'success',
            'data' => [
                'hero_sliders' => $banners->get('hero_slider', collect()),
                'home_banner_left' => $banners->get('home_banner_left')?->first(),
                'home_banner_top' => $banners->get('home_banner_top')?->first(),
                'home_banner_bottom' => $banners->get('home_banner_bottom')?->first(),
                'home_banner_side' => $banners->get('home_banner_side', collect()),
            ],
        ]);
    }
}
