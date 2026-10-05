@extends('admin.layout')

@section('title', 'SMS Gateway & Panel - Amar Bangla Super Admin')
@section('page-title', 'SMS Gateway & Notification Panel (super_admin/sms_panel)')

@section('content')
<div class="space-y-6">

    <!-- Card Navigation Tabs matching CI 3 / Live Super Admin -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 bg-gray-50/70 px-4 pt-3">
            <ul class="flex flex-wrap -mb-px text-xs font-bold text-center text-gray-500" id="smsTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg active tab-btn"
                            id="custom-tabs-four-home-tab" data-tab-target="custom-tabs-four-home" type="button" role="tab">
                        <i class="fa fa-tower-broadcast text-blue-600"></i>
                        <span>Gateway Configuration</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-profile-tab" data-tab-target="custom-tabs-four-profile" type="button" role="tab">
                        <i class="fa fa-paper-plane text-emerald-600"></i>
                        <span>Instant Dispatch</span>
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-flex items-center gap-2 py-3 px-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 rounded-t-lg tab-btn"
                            id="custom-tabs-four-messages-tab" data-tab-target="custom-tabs-four-messages" type="button" role="tab">
                        <i class="fa fa-list-check text-purple-600"></i>
                        <span>Delivery Logs</span>
                        <span class="ml-1.5 px-2 py-0.5 text-[10px] bg-purple-100 text-purple-800 rounded-full">{{ $logs->total() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-6">
            <!-- TAB 1: Gateway Configuration -->
            <div id="custom-tabs-four-home" class="tab-pane space-y-6 max-w-2xl mx-auto">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">SMS Gateway Credentials & Provider Settings</h3>
                    <p class="text-xs text-gray-500 mt-1">Configure your SMS gateway provider, authorization token, and automated order alerts.</p>
                </div>

                <form action="{{ route('admin.sms_panel.update_config') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Gateway Provider <span class="text-rose-500">*</span></label>
                        <select name="sms_gateway_provider" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="GreenWeb" {{ ($settings['sms_gateway_provider'] ?? '') === 'GreenWeb' ? 'selected' : '' }}>GreenWeb SMS BD</option>
                            <option value="BulkSMSBD" {{ ($settings['sms_gateway_provider'] ?? '') === 'BulkSMSBD' ? 'selected' : '' }}>BulkSMS BD</option>
                            <option value="AlphaSMS" {{ ($settings['sms_gateway_provider'] ?? '') === 'AlphaSMS' ? 'selected' : '' }}>Alpha Net SMS</option>
                            <option value="SSLWireless" {{ ($settings['sms_gateway_provider'] ?? '') === 'SSLWireless' ? 'selected' : '' }}>SSL Wireless</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">API Key / Secret Token</label>
                        <input type="password" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" placeholder="Enter secret API token..."
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-mono text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <p class="text-[11px] text-gray-400 mt-1">Provided by your SMS aggregator account dashboard.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sender ID / Masking Name</label>
                        <input type="text" name="sms_sender_id" value="{{ $settings['sms_sender_id'] ?? 'AmarBangla' }}" placeholder="e.g. AmarBangla"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <p class="text-[11px] text-gray-400 mt-1">Approved alphanumeric caller ID displayed to customers.</p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="sms_order_alert" value="1" {{ ($settings['sms_order_alert'] ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-bold text-gray-800 block">Automated Order Notifications</span>
                                <span class="text-[11px] text-gray-500">Automatically dispatch SMS updates to customers on checkout, status transitions, and delivery completion.</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-3 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa fa-floppy-disk"></i>
                            <span>Save Gateway Configuration</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: Instant Dispatch -->
            <div id="custom-tabs-four-profile" class="tab-pane hidden space-y-6 max-w-2xl mx-auto">
                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-base font-bold text-gray-900">Send Quick SMS Notification</h3>
                    <p class="text-xs text-gray-500 mt-1">Send individual or group SMS alerts immediately to customers, sellers, or delivery riders.</p>
                </div>

                <form action="{{ route('admin.sms_panel.send') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Recipient Mobile Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="recipient_phone" required placeholder="017XXXXXXXX"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-mono text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Recipient Name</label>
                            <input type="text" name="recipient_name" placeholder="e.g. Rahim Chowdhury"
                                   class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Audience Role</label>
                            <select name="recipient_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                <option value="customer">Customer</option>
                                <option value="seller">Seller / Shop</option>
                                <option value="delivery_boy">Delivery Rider</option>
                                <option value="agent">Field Agent</option>
                            </select>
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div>
                        <span class="block text-xs font-semibold text-gray-700 mb-1.5">Insert Quick Template:</span>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setTemplate('Dear Customer, your Amar Bangla order is confirmed and on the way! Thank you for choosing us.')"
                                    class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-blue-50 text-xs text-gray-600 hover:text-blue-700 border border-gray-200 transition">
                                Order Confirm
                            </button>
                            <button type="button" onclick="setTemplate('Your verification code for Amar Bangla account is: 849201. Valid for 5 minutes.')"
                                    class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-blue-50 text-xs text-gray-600 hover:text-blue-700 border border-gray-200 transition">
                                OTP Code
                            </button>
                            <button type="button" onclick="setTemplate('Rider Alert: New delivery package pickup scheduled. Check your rider app for details.')"
                                    class="px-3 py-1 rounded-xl bg-gray-100 hover:bg-blue-50 text-xs text-gray-600 hover:text-blue-700 border border-gray-200 transition">
                                Rider Alert
                            </button>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-gray-700">Message Content <span class="text-rose-500">*</span></label>
                            <span id="charCount" class="text-[10px] text-gray-400 font-mono">0 / 160 chars</span>
                        </div>
                        <textarea id="smsText" name="message" rows="4" required placeholder="Type SMS body here..."
                                  oninput="document.getElementById('charCount').innerText = this.value.length + ' / 160 chars'"
                                  class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center justify-center gap-2">
                            <i class="fa fa-paper-plane"></i>
                            <span>Transmit SMS Dispatch</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: Delivery Logs -->
            <div id="custom-tabs-four-messages" class="tab-pane hidden space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">SMS Outbox & Transmission Logs</h4>
                        <p class="text-xs text-gray-500">Chronological history of dispatched notifications and gateway responses.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.sms_panel.index') }}" class="flex items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search phone or text..."
                               class="py-1.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none">
                        <button type="submit" class="px-3.5 py-1.5 bg-gray-800 text-white rounded-xl text-xs font-semibold hover:bg-gray-900 transition">
                            Find
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.sms_panel.index') }}" class="text-xs text-rose-600 underline">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">Recipient</th>
                                <th class="py-3 px-4">Audience</th>
                                <th class="py-3 px-4">Message</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4">Gateway</th>
                                <th class="py-3 px-4">Sent At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-mono font-bold text-gray-900">{{ $log->recipient_phone }}</div>
                                    @if($log->recipient_name)
                                        <div class="text-[11px] text-gray-500">{{ $log->recipient_name }}</div>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 capitalize">
                                        {{ str_replace('_', ' ', $log->recipient_type) }}
                                    </span>
                                </td>

                                <td class="py-3.5 px-4 text-gray-700 max-w-sm">
                                    <p class="line-clamp-2">{{ $log->message }}</p>
                                </td>

                                <td class="py-3.5 px-4 text-center">
                                    @if($log->status === 'sent')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Sent</span>
                                    @elseif($log->status === 'failed')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Failed</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                                    @endif
                                </td>

                                <td class="py-3.5 px-4 text-gray-500 font-mono text-[11px]">
                                    {{ $log->gateway ?: 'GreenWeb' }}
                                </td>

                                <td class="py-3.5 px-4 text-gray-500 text-[11px] whitespace-nowrap">
                                    {{ $log->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    No SMS records found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

<script>
function setTemplate(text) {
    document.getElementById('smsText').value = text;
    document.getElementById('charCount').innerText = text.length + ' / 160 chars';
}

document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    const tabMap = {
        '#custom-tabs-four-home': 'custom-tabs-four-home',
        '#custom-tabs-four-profile': 'custom-tabs-four-profile',
        '#custom-tabs-four-messages': 'custom-tabs-four-messages',
        '#config': 'custom-tabs-four-home',
        '#gateway': 'custom-tabs-four-home',
        '#send': 'custom-tabs-four-profile',
        '#dispatch': 'custom-tabs-four-profile',
        '#logs': 'custom-tabs-four-messages',
        '#outbox': 'custom-tabs-four-messages',
        '#sms_panel': 'custom-tabs-four-home'
    };

    function switchTab(targetId) {
        tabButtons.forEach(btn => {
            if (btn.getAttribute('data-tab-target') === targetId) {
                btn.classList.add('active', 'text-blue-600', 'border-blue-600');
                btn.classList.remove('border-transparent', 'text-gray-500');
            } else {
                btn.classList.remove('active', 'text-blue-600', 'border-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });

        tabPanes.forEach(pane => {
            if (pane.id === targetId) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        });
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.getAttribute('data-tab-target');
            switchTab(target);
            window.location.hash = target;
        });
    });

    if (window.location.hash && tabMap[window.location.hash]) {
        switchTab(tabMap[window.location.hash]);
    }

    window.addEventListener('hashchange', function() {
        if (window.location.hash && tabMap[window.location.hash]) {
            switchTab(tabMap[window.location.hash]);
        }
    });
});
</script>
@endsection
