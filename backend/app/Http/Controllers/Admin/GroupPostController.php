<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GroupPostController extends Controller
{
    public function index(Request $request)
    {
        $query = GroupPost::with('user');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.group_posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.group_posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content'  => 'required|string',
            'image'    => 'nullable|string',
            'status'   => 'required|in:published,draft,archived',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['user_id'] = auth()->id();

        GroupPost::create($validated);

        return redirect()->route('admin.group_posts.index')->with('success', 'Group post published successfully.');
    }

    public function edit($groupPost)
    {
        $groupPost = $groupPost instanceof GroupPost ? $groupPost : GroupPost::findOrFail($groupPost);
        return view('admin.group_posts.edit', compact('groupPost'));
    }

    public function update(Request $request, $groupPost)
    {
        $groupPost = $groupPost instanceof GroupPost ? $groupPost : GroupPost::findOrFail($groupPost);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'content'        => 'required|string',
            'image'          => 'nullable|string',
            'image_file'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'status'         => 'required|in:published,draft,archived',
            'youtube_video'  => 'nullable|string|max:255',
            'facebook_video' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image_file')) {
            $destDir = public_path('uploads/group_post');
            if (!\Illuminate\Support\Facades\File::isDirectory($destDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($destDir, 0755, true, true);
            }
            $file = $request->file('image_file');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $validated['image'] = 'uploads/group_post/' . $filename;
        }

        unset($validated['image_file']);

        $groupPost->update($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/group_post') : route('admin.group_posts.index');
        return redirect($target)->with('success', 'Group post updated successfully.');
    }

    public function destroy(GroupPost $groupPost)
    {
        $groupPost->delete();
        return redirect()->route('admin.group_posts.index')->with('success', 'Group post deleted successfully.');
    }
}
