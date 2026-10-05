@extends('admin.layout')

@section('title', 'Edit Shop License - Amar Bangla Super Admin')
@section('page-title', 'Update Shop License (super_admin/license_update/' . $license->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.licenses.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Licenses</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Update License #{{ $license->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-certificate text-blue-600"></i>
                <span>Edit Shop License: {{ $license->license_key }}</span>
            </h2>
            <p class="text-xs text-gray-500">Update license details, renewal periods, validity dates and verification status</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <a href="{{ route('admin.licenses.print', $license->id) }}" target="_blank"
               class="px-3.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <i class="fa fa-print text-gray-600"></i>
                <span>Print Certificate</span>
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>license_update/{{ $license->id }}</span>
            </span>
        </div>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
        <div class="font-bold flex items-center gap-2">
            <i class="fa fa-triangle-exclamation"></i>
            <span>Please resolve the following errors:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-2">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- License Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center text-xl shadow-2xs">
                <i class="fa fa-award"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ $license->license_key }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ ($license->status ?? 'approved') === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $license->status ?? 'approved' }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5 font-medium">
                    Shop: <strong class="text-gray-800">{{ $license->shop_name ?: ($license->shop ? $license->shop->name : 'Unassigned') }}</strong>
                    @if($license->vendor_name)
                        &bull; Proprietor: <span class="text-gray-700">{{ $license->vendor_name }}</span>
                    @endif
                </p>
                <div class="flex items-center gap-2 mt-1 text-[11px] text-gray-500 font-mono">
                    <span>Valid: {{ $license->start_date ? $license->start_date->format('d M, Y') : ($license->issue_date ? $license->issue_date->format('d M, Y') : 'N/A') }}</span>
                    <span>&rarr;</span>
                    <span>{{ $license->end_date ? $license->end_date->format('d M, Y') : ($license->expiry_date ? $license->expiry_date->format('d M, Y') : 'Lifetime') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Registraion Info (#custom-tabs-four-home / #registertab)
         2. Others & Documents (#custom-tabs-four-profile / #others) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="licenseTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchLicenseTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="license-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-blue-600 text-white shadow-sm">
            <i class="fa fa-id-card"></i>
            <span>Registraion Info</span>
        </a>

        <a href="#custom-tabs-four-profile" onclick="switchLicenseTab('custom-tabs-four-profile'); return false;" id="btn-custom-tabs-four-profile"
           class="license-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-file-contract"></i>
            <span>Others & Documents</span>
        </a>
    </div>

    <!-- Edit License Form -->
    <form action="{{ route('admin.licenses.update_legacy_put', $license->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: Registraion Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="license-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-store text-blue-600"></i>
                    <span>Shop & License Registration Information</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Select registered shop outlet and define license key with validity dates</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Shop Select from DB -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Select Registered Shop <span class="text-rose-500">*</span>
                    </label>
                    <select id="shop_select" name="shop_id" onchange="onShopSelected(this)" required
                            class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="">-- Choose Existing Shop From System --</option>
                        @foreach($shops as $sh)
                            <option value="{{ $sh->id }}"
                                    data-name="{{ $sh->name }}"
                                    data-phone="{{ $sh->phone }}"
                                    data-vendor="{{ $sh->vendor ? $sh->vendor->name : ($sh->contact_person ?? '') }}"
                                    {{ old('shop_id', $license->shop_id) == $sh->id ? 'selected' : '' }}>
                                {{ $sh->name }} (Phone: {{ $sh->phone ?: 'N/A' }} | Balance: ৳{{ number_format($sh->balance, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Shop Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Shop Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="shop_name" name="shop_name" required
                           value="{{ old('shop_name', $license->shop_name ?: ($license->shop ? $license->shop->name : '')) }}"
                           placeholder="Enter registered trade / outlet name..."
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Vendor / Owner Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Merchant / Proprietor Name
                    </label>
                    <input type="text" id="vendor_name" name="vendor_name"
                           value="{{ old('vendor_name', $license->vendor_name ?: ($license->shop && $license->shop->vendor ? $license->shop->vendor->name : '')) }}"
                           placeholder="Owner or contact person name..."
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- License Key -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        License Key <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="license_key" name="license_key" required
                               value="{{ old('license_key', $license->license_key ?: $license->license_number) }}"
                               placeholder="e.g. AB-LIC-2026-XXXX"
                               class="flex-1 py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-blue-700 tracking-wider focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <button type="button" onclick="generateRandomLicenseKey()"
                                class="px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap">
                            <i class="fa fa-arrows-rotate text-[11px]"></i>
                            <span>Generate Key</span>
                        </button>
                    </div>
                </div>

                <!-- License / Permit Type -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        License / Permit Type
                    </label>
                    <select name="license_type" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="Trade License" {{ old('license_type', $license->license_type) == 'Trade License' ? 'selected' : '' }}>Trade License (সিটি কর্পোরেশন / পৌরসভা)</option>
                        <option value="Municipal Shop Permit" {{ old('license_type', $license->license_type) == 'Municipal Shop Permit' ? 'selected' : '' }}>Municipal Shop Permit</option>
                        <option value="Fire & Safety License" {{ old('license_type', $license->license_type) == 'Fire & Safety License' ? 'selected' : '' }}>Fire & Safety License</option>
                        <option value="BSTI Quality Certificate" {{ old('license_type', $license->license_type) == 'BSTI Quality Certificate' ? 'selected' : '' }}>BSTI Quality Certificate</option>
                        <option value="Drug License" {{ old('license_type', $license->license_type) == 'Drug License' ? 'selected' : '' }}>Pharmacy / Drug License</option>
                        <option value="TIN / Tax Certificate" {{ old('license_type', $license->license_type) == 'TIN / Tax Certificate' ? 'selected' : '' }}>TIN / Tax Certificate</option>
                        <option value="Environment Clearance" {{ old('license_type', $license->license_type) == 'Environment Clearance' ? 'selected' : '' }}>Environment Clearance</option>
                        <option value="Other Permit" {{ old('license_type', $license->license_type) == 'Other Permit' ? 'selected' : '' }}>Other Verification</option>
                    </select>
                </div>

                <!-- Verification Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Verification Status <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="approved" {{ old('status', $license->status) == 'approved' ? 'selected' : '' }}>Approved / Active</option>
                        <option value="pending" {{ old('status', $license->status) == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="expired" {{ old('status', $license->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="rejected" {{ old('status', $license->status) == 'rejected' ? 'selected' : '' }}>Rejected / Suspended</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Start Date (Issue Date) <span class="text-rose-500">*</span>
                    </label>
                    @php
                        $startVal = $license->start_date ? $license->start_date->format('Y-m-d') : ($license->issue_date ? $license->issue_date->format('Y-m-d') : '');
                    @endphp
                    <input type="date" id="start_date" name="start_date" required
                           value="{{ old('start_date', $startVal) }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- End Date with Duration Shortcuts -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-gray-700">
                            End Date (Expiry Date)
                        </label>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="setExpiryDuration(6)" class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition">+6 Mo</button>
                            <button type="button" onclick="setExpiryDuration(12)" class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition">+1 Yr</button>
                            <button type="button" onclick="setExpiryDuration(24)" class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 hover:bg-blue-100 transition">+2 Yrs</button>
                        </div>
                    </div>
                    @php
                        $endVal = $license->end_date ? $license->end_date->format('Y-m-d') : ($license->expiry_date ? $license->expiry_date->format('Y-m-d') : '');
                    @endphp
                    <input type="date" id="end_date" name="end_date"
                           value="{{ old('end_date', $endVal) }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.licenses.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update License Record</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Others & Documents (#custom-tabs-four-profile / #others) -->
        <div id="custom-tabs-four-profile" class="license-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-file-contract text-blue-600"></i>
                    <span>Supporting Document, Scans & Inspector Notes</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Attach certificates, tax clearances, or inspector verification remarks</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- File Upload -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Replace / Upload Certificate Scan
                    </label>
                    <input type="file" name="document" accept=".jpg,.jpeg,.png,.pdf,.webp"
                           class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-200 rounded-xl p-1 bg-gray-50">
                    <p class="text-[10px] text-gray-400 mt-1">Accepted: PDF, JPG, PNG, WEBP (Max: 5MB)</p>

                    @if($license->document_file)
                    <div class="mt-2 p-2 bg-blue-50/70 border border-blue-100 rounded-lg flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 truncate">
                            <i class="fa fa-paperclip text-blue-600"></i>
                            <span class="truncate font-semibold text-gray-700">Existing Document Attached</span>
                        </div>
                        <a href="{{ $license->document_file }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline font-bold shrink-0">
                            View File
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Document External Link URL -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Or External Document URL
                    </label>
                    <input type="url" name="document_file" value="{{ old('document_file', $license->document_file) }}"
                           placeholder="https://drive.google.com/file/d/..."
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <!-- Notes / Remarks -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Inspector Observations / Remarks
                    </label>
                    <textarea name="notes" rows="4" placeholder="Municipal registration authority, tax circle, inspection observations..."
                              class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">{{ old('notes', $license->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchLicenseTab('custom-tabs-four-home')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Registration Info
                </button>

                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save License Changes</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function switchLicenseTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'registration': 'custom-tabs-four-home',
        'custom-tabs-four-profile': 'custom-tabs-four-profile',
        'others': 'custom-tabs-four-profile',
        'documents': 'custom-tabs-four-profile'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.license-tab-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.license-tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });

    const activeBtn = document.getElementById('btn-' + activeId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchLicenseTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchLicenseTab(hash);
    }
});

function onShopSelected(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption && selectedOption.value) {
        const name = selectedOption.getAttribute('data-name');
        const vendor = selectedOption.getAttribute('data-vendor');
        if (name) {
            document.getElementById('shop_name').value = name;
        }
        if (vendor) {
            document.getElementById('vendor_name').value = vendor;
        }
    }
}

function generateRandomLicenseKey() {
    const year = new Date().getFullYear();
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 6; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('license_key').value = `AB-LIC-${year}-${code}`;
}

function setExpiryDuration(months) {
    const startDateInput = document.getElementById('start_date');
    let baseDate = new Date();
    if (startDateInput && startDateInput.value) {
        baseDate = new Date(startDateInput.value);
    }
    baseDate.setMonth(baseDate.getMonth() + months);

    const y = baseDate.getFullYear();
    const m = String(baseDate.getMonth() + 1).padStart(2, '0');
    const d = String(baseDate.getDate()).padStart(2, '0');

    const endInput = document.getElementById('end_date');
    if (endInput) {
        endInput.value = `${y}-${m}-${d}`;
    }
}
</script>
@endsection
