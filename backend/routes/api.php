<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CallingRequestApiController;
use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommunityApiController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Public Browsing & Geo Hierarchy
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{idOrSlug}', [CategoryController::class, 'show']);
    Route::get('/shop-categories', [App\Http\Controllers\Api\ShopCategoryApiController::class, 'index']);
    Route::get('/shop-categories/{id}', [App\Http\Controllers\Api\ShopCategoryApiController::class, 'show']);
    Route::get('/locations', [ShopController::class, 'locations']);
    Route::get('/geo/cascade', [ShopController::class, 'geoCascade']);

    // Hyper-local Shops (with 4-level smart fallback)
    Route::get('/shops', [ShopController::class, 'index']);
    Route::get('/shops/{idOrSlug}', [ShopController::class, 'show']);

    // Marketplace Products
    Route::get('/products/quick-search', [ProductController::class, 'quickSearch']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{idOrSlug}', [ProductController::class, 'show']);

    // Smart Calling Requests (Instant Demand / ফর্দ)
    Route::post('/calling-requests', [CallingRequestApiController::class, 'store']);

    // Community Social Feed (Community Wall)
    Route::get('/community/posts', [CommunityApiController::class, 'index']);
    Route::post('/community/posts', [CommunityApiController::class, 'store']);
    Route::post('/community/posts/{id}/like', [CommunityApiController::class, 'like']);

    // Campaigns & Festive Deals Engine
    Route::get('/campaigns', [CampaignApiController::class, 'index']);
    Route::get('/campaigns/{idOrSlug}', [CampaignApiController::class, 'show']);

    // Orders (Public / Guest & Logged in)
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::get('/user/orders', [OrderController::class, 'userOrders']);
    });
});
