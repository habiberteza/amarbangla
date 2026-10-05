<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->latest()->paginate(15)->withQueryString();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'banner_image'        => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date|after_or_equal:start_date',
            'status'              => 'required|in:upcoming,active,expired,disabled',
            'description'         => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['is_featured'] = $request->has('is_featured');

        Campaign::create($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function edit($campaign)
    {
        $campaign = $campaign instanceof Campaign ? $campaign : Campaign::findOrFail($campaign);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $campaign)
    {
        $campaign = $campaign instanceof Campaign ? $campaign : Campaign::findOrFail($campaign);

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'subtitle'            => 'nullable|string|max:255',
            'banner_image'        => 'nullable|string',
            'image'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'start_date'          => 'nullable|date',
            'end_date'            => 'nullable|date',
            'status'              => 'required|in:upcoming,active,expired,disabled',
            'description'         => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $destDir = public_path('uploads/campaign');
            if (!\Illuminate\Support\Facades\File::isDirectory($destDir)) {
                \Illuminate\Support\Facades\File::makeDirectory($destDir, 0755, true, true);
            }
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);
            $validated['banner_image'] = 'uploads/campaign/' . $filename;
        }

        unset($validated['image']);
        $validated['is_featured'] = $request->has('is_featured');

        $campaign->update($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/campaign') : route('admin.campaigns.index');
        return redirect($target)->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted successfully.');
    }
}
