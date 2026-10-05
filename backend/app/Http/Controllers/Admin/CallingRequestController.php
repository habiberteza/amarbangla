<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallingRequest;
use Illuminate\Http\Request;

class CallingRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = CallingRequest::with('product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('calling_id', 'desc')->paginate(15)->withQueryString();

        $statusCounts = [
            'all'       => CallingRequest::count(),
            'pending'   => CallingRequest::where('status', 'pending')->count(),
            'called'    => CallingRequest::where('status', 'called')->count(),
            'completed' => CallingRequest::where('status', 'completed')->count(),
            'cancelled' => CallingRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.calling_requests.index', compact('requests', 'statusCounts'));
    }

    public function updateStatus(Request $request, CallingRequest $callingRequest)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,called,completed,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $callingRequest->update($validated);

        return back()->with('success', 'Calling request updated successfully.');
    }

    public function poll()
    {
        $pendingCount = CallingRequest::where('status', 'pending')->count();
        $latest = CallingRequest::where('status', 'pending')->orderBy('calling_id', 'desc')->first();

        return response()->json([
            'pending_count' => $pendingCount,
            'latest' => $latest ? [
                'id'            => $latest->id,
                'customer_name' => $latest->customer_name,
                'phone'         => $latest->phone,
                'product_name'  => $latest->product_name ?? 'ফর্দ / Demand Request',
                'created_at'    => $latest->created_at->diffForHumans(),
            ] : null,
        ]);
    }

    public function destroy(CallingRequest $callingRequest)
    {
        $callingRequest->delete();
        return back()->with('success', 'Calling request deleted successfully.');
    }
}
