@extends('admin.layout')

@section('title', 'Create Shop License - Amar Bangla Super Admin')
@section('page-title', 'Create Shop License (super_admin/license_create)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.licenses.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to License List</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">License Create</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-id-card text-blue-600"></i>
                <span>Create Shop License</span>
            </h2>
            <p class="text-xs text-gray-500">Assign and activate a verified shop license key</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>super_admin/license_create</span>
        </span>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1 shadow-sm">
        <div class="font-bold flex items-center gap-2">
            <i class="fa fa-triangle-exclamation text-rose-600"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Create License Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.licenses.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. Select Registered Shop -->
            <div>
                <label for="shop_id" class="block text-xs font-bold text-gray-700 mb-2">
                    Select Registered Shop <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="shop_id" name="shop_id" required
                            class="w-full py-2.5 pl-3 pr-10 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">-- Choose Registered Shop --</option>
                        @foreach($shops as $sh)
                            <option value="{{ $sh->id }}" {{ old('shop_id', request('shop_id')) == $sh->id ? 'selected' : '' }}>
                                {{ $sh->name }} (Phone: {{ $sh->phone ?: 'N/A' }} | Balance: ৳{{ number_format($sh->balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Select the merchant shop to which this license key will be issued.</p>
            </div>

            <!-- 2. License Key * -->
            <div>
                <label for="license_key" class="block text-xs font-bold text-gray-700 mb-2">
                    License Key <span class="text-rose-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <input type="text" id="license_key" name="license_key" required
                           value="{{ old('license_key', $defaultKey) }}"
                           placeholder="e.g. AB-LIC-2026-XXXXXX"
                           class="flex-1 py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-blue-700 tracking-wider focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    <button type="button" onclick="generateRandomLicenseKey()"
                            class="px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap shadow-xs">
                        <i class="fa fa-arrows-rotate text-[11px]"></i>
                        <span>Generate Key</span>
                    </button>
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Unique authorization key for the shop.</p>
            </div>

            <!-- 3. Start Date (Issue Date) * -->
            <div>
                <label for="start_date" class="block text-xs font-bold text-gray-700 mb-2">
                    Start Date (Issue Date) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="date" id="start_date" name="start_date" required
                           value="{{ old('start_date', date('Y-m-d')) }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <!-- 4. End Date (Expiry Date) -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="end_date" class="block text-xs font-bold text-gray-700">
                        End Date (Expiry Date)
                    </label>
                    <!-- Quick Duration Shortcuts -->
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="setExpiryDuration(6)" class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">+6 Mo</button>
                        <button type="button" onclick="setExpiryDuration(12)" class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">+1 Yr</button>
                        <button type="button" onclick="setExpiryDuration(24)" class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">+2 Yrs</button>
                    </div>
                </div>
                <div class="relative">
                    <input type="date" id="end_date" name="end_date"
                           value="{{ old('end_date', date('Y-m-d', strtotime('+1 year'))) }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Leave empty if the license has no expiration date.</p>
            </div>

            <!-- Form Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.licenses.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-check"></i>
                    <span>Register License</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
// Generate random formatted license key
function generateRandomLicenseKey() {
    const year = new Date().getFullYear();
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('license_key').value = `AB-LIC-${year}-${code}`;
}

// Quick preset for Expiry Date
function setExpiryDuration(months) {
    const startDateInput = document.getElementById('start_date');
    let baseDate = new Date();
    if (startDateInput.value) {
        baseDate = new Date(startDateInput.value);
    }
    baseDate.setMonth(baseDate.getMonth() + months);

    const y = baseDate.getFullYear();
    const m = String(baseDate.getMonth() + 1).padStart(2, '0');
    const d = String(baseDate.getDate()).padStart(2, '0');

    document.getElementById('end_date').value = `${y}-${m}-${d}`;
}
</script>
@endsection
