@extends('admin.layout')

@section('title', 'Create Seller - Amar Bangla Super Admin')
@section('page-title', 'Create Seller Account (super_admin/sellers_create)')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.vendors.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-semibold">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                    <span>Back to Seller List</span>
                </a>
                <span>/</span>
                <span class="text-gray-700 font-semibold">Create Seller</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                <i class="fa fa-user-plus text-blue-600"></i>
                <span>Register New Seller</span>
            </h2>
            <p class="text-xs text-gray-500">Create a merchant seller login credential and account profile</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-mono font-semibold self-start sm:self-center">
            <i class="fa fa-link text-[10px]"></i>
            <span>super_admin/sellers_create</span>
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

    <!-- Create Seller Form Card -->
    <!-- Exact Fields: Name | Phone | Password | Confirm Password | Status -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
        <form action="{{ route('admin.sellers.store_legacy') }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 mb-2">
                    Name <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="name" name="name" required
                           value="{{ old('name') }}"
                           placeholder="Enter seller full name..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <!-- 2. Phone -->
            <div>
                <label for="phone" class="block text-xs font-bold text-gray-700 mb-2">
                    Phone <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="phone" name="phone" required
                           value="{{ old('phone') }}"
                           placeholder="017XXXXXXXX"
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-semibold text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">Mobile number used for account verification and login.</p>
            </div>

            <!-- 3. Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 mb-2">
                    Password <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required minlength="6"
                           placeholder="Minimum 6 characters..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <!-- 4. Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-2">
                    Confirm Password <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                           placeholder="Re-type password..."
                           class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                </div>
            </div>

            <!-- 5. Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-700 mb-2">
                    Status <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="status" name="status" required
                            class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.vendors.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                    <i class="fa fa-check"></i>
                    <span>Create Seller</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
