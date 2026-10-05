@extends('admin.layout')

@section('title', 'Register Agent - Amar Bangla Super Admin')
@section('page-title', 'Register Territory / Union Agent (super_admin/agent)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.agents.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-gray-800 transition">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Agents</span>
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.agents.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Agent Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Aminul Islam"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('name') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 01755112233"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 font-mono focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    @error('phone') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="agent@amarbangla.com.bd"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">NID Card Number</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number') }}" placeholder="National ID number"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Division</label>
                    <input type="text" name="division" value="{{ old('division', 'Dhaka') }}" placeholder="e.g. Dhaka"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">District</label>
                    <input type="text" name="district" value="{{ old('district', 'Dhaka') }}" placeholder="e.g. Dhaka"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upazila / Thana</label>
                    <input type="text" name="upazila" value="{{ old('upazila') }}" placeholder="e.g. Dhanmondi"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Union / Ward / Area</label>
                    <input type="text" name="union_ward" value="{{ old('union_ward') }}" placeholder="e.g. Ward 15"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Commission Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', '3.00') }}"
                           class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full py-2.5 px-3 bg-gray-50 border border-gray-300 rounded-xl text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-600 focus:outline-none">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.agents.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-xl transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fa fa-save"></i>
                    <span>Save Agent</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
