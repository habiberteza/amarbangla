@extends('admin.layout')

@section('title', 'Add Customer - Amar Bangla Super Admin')
@section('page-title', 'Register New Customer')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Customer List</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <h3 class="font-bold text-gray-900 text-sm mb-1">Primary Customer Information</h3>
                <p class="text-xs text-gray-400">Basic contact and identification details</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Rahim Ahmed"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('name')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 01712345678"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 font-mono focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('phone')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. rahim@example.com"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('email')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Customer Classification / Type</label>
                    <select name="customer_type_id" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="">Standard Retail / General</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ old('customer_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->discount_percentage }}% Discount)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-bold text-gray-900 text-sm mb-1">Address & Location</h3>
                <p class="text-xs text-gray-400 mb-4">Default delivery destination for future orders</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Division</label>
                        <input type="text" name="division" value="{{ old('division', 'Dhaka') }}" placeholder="e.g. Dhaka"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">District / City</label>
                        <input type="text" name="district" value="{{ old('district', 'Dhaka') }}" placeholder="e.g. Dhaka"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Upazila / Area</label>
                        <input type="text" name="upazila" value="{{ old('upazila') }}" placeholder="e.g. Uttara"
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Full Street Address</label>
                    <textarea name="address" rows="2" placeholder="House, Road, Area details..."
                              class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <h3 class="font-bold text-gray-900 text-sm mb-1">Account & Preferences</h3>
                <p class="text-xs text-gray-400 mb-4">Status and login credentials</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account Status</label>
                        <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Internal Notes</label>
                        <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Special buyer notes..."
                               class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="create_user" id="create_user" value="1" {{ old('create_user') ? 'checked' : '' }}
                               onchange="document.getElementById('pwdSection').classList.toggle('hidden', !this.checked)"
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="create_user" class="text-xs font-bold text-slate-800 cursor-pointer">
                            Create Web Login Account for this customer
                        </label>
                    </div>

                    <div id="pwdSection" class="{{ old('create_user') ? '' : 'hidden' }} pt-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Account Password (Min 6 chars)</label>
                        <input type="password" name="password" placeholder="Enter password..."
                               class="w-full sm:w-64 py-2 px-3 bg-white border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Customer</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
