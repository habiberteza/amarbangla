<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = Agent::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%");
            });
        }

        $agents = $query->latest()->paginate(15)->withQueryString();

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        return view('admin.agents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20|unique:agents,phone',
            'email'           => 'nullable|email|max:255',
            'nid_number'      => 'nullable|string|max:50',
            'division'        => 'nullable|string|max:100',
            'district'        => 'nullable|string|max:100',
            'upazila'         => 'nullable|string|max:100',
            'union_ward'      => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'balance'         => 'nullable|numeric|min:0',
            'status'          => 'required|in:active,inactive,suspended',
        ]);

        $validated['commission_rate'] = $validated['commission_rate'] ?? 0;
        $validated['balance'] = $validated['balance'] ?? 0;

        Agent::create($validated);

        return redirect()->route('admin.agents.index')->with('success', 'Agent registered successfully.');
    }

    public function edit($agent)
    {
        $agent = $agent instanceof Agent ? $agent : Agent::findOrFail($agent);
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:20|unique:agents,phone,' . $agent->id,
            'email'           => 'nullable|email|max:255',
            'nid_number'      => 'nullable|string|max:50',
            'division'        => 'nullable|string|max:100',
            'district'        => 'nullable|string|max:100',
            'upazila'         => 'nullable|string|max:100',
            'union_ward'      => 'nullable|string|max:100',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'balance'         => 'nullable|numeric|min:0',
            'status'          => 'required|in:active,inactive,suspended',
        ]);

        $agent->update($validated);

        return redirect()->route('admin.agents.index')->with('success', 'Agent details updated successfully.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')->with('success', 'Agent deleted successfully.');
    }
}
