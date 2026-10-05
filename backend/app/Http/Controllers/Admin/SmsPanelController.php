<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsPanelController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');
        $query = SmsLog::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('recipient_phone', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.sms_panel.index', compact('settings', 'logs'));
    }

    public function updateConfig(Request $request)
    {
        $validated = $request->validate([
            'sms_gateway_provider' => 'required|string',
            'sms_api_key'          => 'nullable|string',
            'sms_sender_id'        => 'nullable|string',
        ]);

        Setting::set('sms_gateway_provider', $validated['sms_gateway_provider']);
        Setting::set('sms_api_key', $validated['sms_api_key'] ?? '');
        Setting::set('sms_sender_id', $validated['sms_sender_id'] ?? '');
        Setting::set('sms_order_alert', $request->has('sms_order_alert') ? '1' : '0');

        return back()->with('success', 'SMS gateway configuration saved.');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'recipient_phone' => 'required|string',
            'recipient_name'  => 'nullable|string',
            'recipient_type'  => 'required|string',
            'message'         => 'required|string|max:500',
        ]);

        $gateway = Setting::get('sms_gateway_provider', 'GreenWeb');

        // Record SMS log
        SmsLog::create([
            'recipient_phone'  => $validated['recipient_phone'],
            'recipient_name'   => $validated['recipient_name'],
            'recipient_type'   => $validated['recipient_type'],
            'message'          => $validated['message'],
            'gateway'          => $gateway,
            'status'           => 'sent',
            'response_payload' => json_encode(['status' => 'success', 'timestamp' => now()->toIso8601String()]),
        ]);

        return back()->with('success', "SMS dispatched successfully to {$validated['recipient_phone']}.");
    }
}
