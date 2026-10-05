<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = Ledger::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('party_type')) {
            $query->where('party_type', $request->party_type);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('txn_number', 'like', "%{$search}%")
                  ->orWhere('party_name', 'like', "%{$search}%")
                  ->orWhere('reference_id', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $ledgers = (clone $query)->latest()->paginate(20)->withQueryString();
        $credits = Ledger::where('type', 'credit')->latest()->take(25)->get();
        $debits  = Ledger::where('type', 'debit')->latest()->take(25)->get();

        $totalCredit = Ledger::where('type', 'credit')->sum('amount');
        $totalDebit  = Ledger::where('type', 'debit')->sum('amount');
        $netBalance  = $totalCredit - $totalDebit;

        return view('admin.ledger.index', compact('ledgers', 'totalCredit', 'totalDebit', 'netBalance', 'credits', 'debits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'           => 'required|in:credit,debit',
            'category'       => 'required|string|max:100',
            'amount'         => 'required|numeric|min:0.01',
            'party_type'     => 'required|string|max:50',
            'party_name'     => 'nullable|string|max:150',
            'reference_id'   => 'nullable|string|max:100',
            'payment_method' => 'nullable|string|max:100',
            'note'           => 'nullable|string',
        ]);

        $validated['txn_number'] = 'TXN-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $lastBalance = Ledger::latest('id')->value('balance_after') ?? 0.00;
        if ($validated['type'] === 'credit') {
            $validated['balance_after'] = $lastBalance + $validated['amount'];
        } else {
            $validated['balance_after'] = $lastBalance - $validated['amount'];
        }

        Ledger::create($validated);

        return back()->with('success', 'Ledger transaction recorded successfully.');
    }
}
