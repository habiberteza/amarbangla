@extends('admin.layout')

@section('title', 'Edit Product Color - Amar Bangla Super Admin')
@section('page-title', 'Edit Color: ' . $productColor->name . ' (super_admin/product_color_update/' . $productColor->id . ')')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.product_colors.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Product Colors</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Color #{{ $productColor->id }}</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-palette text-purple-600"></i>
                <span>Edit Product Color: {{ $productColor->name }}</span>
            </h2>
            <p class="text-xs text-gray-500">Configure catalog variant color attribute, hex swatch code, and active toggle</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-800 border border-purple-200 rounded-full text-xs font-mono font-semibold">
                <i class="fa fa-link text-[10px]"></i>
                <span>product_color_update/{{ $productColor->id }}</span>
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

    <!-- Color Overview Card -->
    <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div id="swatch_preview_box" class="w-14 h-14 rounded-2xl border-2 border-gray-200 shadow-sm flex items-center justify-center transition-all"
                 style="background-color: {{ $productColor->code ?: '#000000' }};">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-black text-gray-900">{{ $productColor->name }}</h3>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $productColor->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $productColor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-xs font-mono font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                        {{ $productColor->code }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Product Variant Color Swatch</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Matching Super Admin:
         1. Registraion Info (#custom-tabs-four-home / #registertab)
         2. Preview & Swatches (#custom-tabs-four-profile / #image) -->
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-1" id="productColorTabsNav">
        <a href="#custom-tabs-four-home" onclick="switchProductColorTab('custom-tabs-four-home'); return false;" id="btn-custom-tabs-four-home"
           class="prod-color-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-purple-600 text-white shadow-sm">
            <i class="fa fa-id-card"></i>
            <span>Registraion Info</span>
        </a>

        <a href="#custom-tabs-four-profile" onclick="switchProductColorTab('custom-tabs-four-profile'); return false;" id="btn-custom-tabs-four-profile"
           class="prod-color-tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i class="fa fa-eye"></i>
            <span>Preview & Swatches</span>
        </a>
    </div>

    <form action="{{ route('admin.product_colors.update', $productColor->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: Registraion Info (#custom-tabs-four-home) -->
        <div id="custom-tabs-four-home" class="prod-color-tab-pane bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-palette text-purple-600"></i>
                    <span>Color Details & Hex Code</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Enter standard color name and pick or type hex swatch code</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Color Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $productColor->name) }}" required placeholder="e.g. Navy Blue, Maroon, Jet Black"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">
                        Hex Color Code <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="color_picker_input" value="{{ old('code', $productColor->code ?: '#000000') }}"
                               oninput="syncColorCode(this.value)"
                               class="w-11 h-11 p-1 bg-white border border-gray-200 rounded-xl cursor-pointer">
                        <input type="text" name="code" id="color_text_input" value="{{ old('code', $productColor->code ?: '#000000') }}" required
                               oninput="syncPicker(this.value)"
                               placeholder="#000000"
                               class="flex-1 py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $productColor->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4">
                        <span class="text-xs font-bold text-gray-700">Active (Available when merchants create products)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.product_colors.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Update Color</span>
                </button>
            </div>
        </div>

        <!-- TAB 2: Preview & Swatches (#custom-tabs-four-profile) -->
        <div id="custom-tabs-four-profile" class="prod-color-tab-pane hidden bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="border-b border-gray-100 pb-3">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-eye text-purple-600"></i>
                    <span>Storefront Swatch Preview</span>
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Preview how customers will see this color badge on product detail pages</p>
            </div>

            <div class="space-y-4">
                <div class="p-6 bg-gray-50 border border-gray-200 rounded-2xl flex flex-col items-center justify-center gap-3 text-center">
                    <div id="swatch_large_preview" class="w-24 h-24 rounded-2xl border-4 border-white shadow-lg transition-all"
                         style="background-color: {{ $productColor->code ?: '#000000' }};"></div>
                    <span id="swatch_name_preview" class="text-sm font-bold text-gray-800">{{ $productColor->name }}</span>
                    <span id="swatch_hex_preview" class="text-xs font-mono text-gray-500">{{ $productColor->code }}</span>
                </div>

                <div class="p-4 bg-white border border-gray-200 rounded-xl space-y-2">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Example Size & Color Option Badge on Product Card:</h4>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-lg border-2 border-purple-600 text-xs font-bold flex items-center gap-2 text-gray-800 bg-purple-50">
                            <span id="swatch_badge_dot" class="w-3.5 h-3.5 rounded-full border border-gray-300" style="background-color: {{ $productColor->code ?: '#000000' }};"></span>
                            <span id="swatch_badge_text">{{ $productColor->name }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <button type="button" onclick="switchProductColorTab('custom-tabs-four-home')"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    &larr; Back to Registration Info
                </button>

                <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Color Swatch</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
function switchProductColorTab(targetTab) {
    const tabMap = {
        'custom-tabs-four-home': 'custom-tabs-four-home',
        'registertab': 'custom-tabs-four-home',
        'info': 'custom-tabs-four-home',
        'custom-tabs-four-profile': 'custom-tabs-four-profile',
        'image': 'custom-tabs-four-profile',
        'preview': 'custom-tabs-four-profile',
        'others': 'custom-tabs-four-profile'
    };

    const activeId = tabMap[targetTab] || 'custom-tabs-four-home';

    document.querySelectorAll('.prod-color-tab-pane').forEach(el => {
        el.classList.add('hidden');
    });

    const targetPane = document.getElementById(activeId);
    if (targetPane) {
        targetPane.classList.remove('hidden');
    }

    document.querySelectorAll('.prod-color-tab-btn').forEach(btn => {
        btn.classList.remove('bg-purple-600', 'text-white', 'shadow-sm');
        btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
    });

    const activeBtn = document.getElementById('btn-' + activeId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-purple-600', 'text-white', 'shadow-sm');
    }

    try {
        history.replaceState(null, null, '#' + targetTab);
    } catch(e) {}
}

window.addEventListener('DOMContentLoaded', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchProductColorTab(hash);
    }
});

window.addEventListener('hashchange', () => {
    const hash = (window.location.hash || '').replace('#', '');
    if (hash) {
        switchProductColorTab(hash);
    }
});

function syncColorCode(colorHex) {
    document.getElementById('color_text_input').value = colorHex;
    updateSwatches(colorHex);
}

function syncPicker(colorHex) {
    if (/^#[0-9A-Fa-f]{6}$/.test(colorHex)) {
        document.getElementById('color_picker_input').value = colorHex;
        updateSwatches(colorHex);
    }
}

function updateSwatches(colorHex) {
    const box1 = document.getElementById('swatch_preview_box');
    const box2 = document.getElementById('swatch_large_preview');
    const box3 = document.getElementById('swatch_badge_dot');
    const hexText = document.getElementById('swatch_hex_preview');
    if (box1) box1.style.backgroundColor = colorHex;
    if (box2) box2.style.backgroundColor = colorHex;
    if (box3) box3.style.backgroundColor = colorHex;
    if (hexText) hexText.innerText = colorHex;
}
</script>
@endsection
