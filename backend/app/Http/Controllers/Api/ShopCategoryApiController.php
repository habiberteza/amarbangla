<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopCategoryApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = ShopCategory::whereNull('deleted');

        if ($request->has('show_home')) {
            $query->where('show_home', $request->input('show_home'));
        }

        if ($request->has('parent_cat_id')) {
            $query->where('parent_cat_id', $request->input('parent_cat_id'));
        }

        $categories = $query->orderBy('shop_cat_id', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $categories,
        ]);
    }

    public function show($id): JsonResponse
    {
        $category = ShopCategory::whereNull('deleted')
            ->where('shop_cat_id', $id)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data'   => $category,
        ]);
    }
}
