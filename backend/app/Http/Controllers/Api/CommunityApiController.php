<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GroupPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityApiController extends Controller
{
    /**
     * Community Feed - Posts from customers, shop owners, agents
     */
    public function index(Request $request): JsonResponse
    {
        $posts = GroupPost::where('status', 'published')
            ->with(['user' => function ($q) {
                $q->select('id', 'name', 'avatar', 'image', 'role', 'division', 'district');
            }])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data'   => $posts,
        ]);
    }

    /**
     * Publish a new community post
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:200',
            'content'  => 'required|string|max:5000',
            'category' => 'nullable|string|max:100',
            'image'    => 'nullable|string',
        ]);

        $post = GroupPost::create([
            'user_id'     => $request->user()?->id ?? 1,
            'title'       => $validated['title'],
            'slug'        => Str::slug($validated['title']) . '-' . uniqid(),
            'content'     => $validated['content'],
            'category'    => $validated['category'] ?? 'General',
            'image'       => $validated['image'] ?? null,
            'likes_count' => 0,
            'status'      => 'published',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'কমিউনিটি পোস্টে সফলভাবে প্রকাশ করা হয়েছে!',
            'data'    => $post->load('user'),
        ], 201);
    }

    /**
     * Like a post
     */
    public function like(int $id): JsonResponse
    {
        $post = GroupPost::findOrFail($id);
        $post->increment('likes_count');

        return response()->json([
            'status'      => 'success',
            'likes_count' => $post->likes_count,
        ]);
    }
}
