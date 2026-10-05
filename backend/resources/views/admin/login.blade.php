<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login - Amar Bangla</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-700">
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 p-8 text-center text-white">
            <div class="w-16 h-16 bg-white text-blue-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl font-black shadow-lg">
                AB
            </div>
            <h2 class="text-2xl font-bold tracking-tight">Super Admin Portal</h2>
            <p class="text-blue-100 text-xs mt-1">Amar Bangla Multi-Vendor Marketplace Platform</p>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-xs p-3 rounded-lg flex items-center gap-2">
                    <i class="fa fa-triangle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email', 'admin@amarbangla.com.bd') }}" required
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" value="password123" required
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>Keep me logged in</span>
                    </label>
                    <span class="text-blue-600 hover:underline">Forgot password?</span>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-lg shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fa fa-arrow-right-to-bracket"></i>
                    <span>Log In to Super Admin</span>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-400">
                Default Credentials: <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-600 font-mono">admin@amarbangla.com.bd / password123</code>
            </div>
        </div>
    </div>
</body>
</html>
