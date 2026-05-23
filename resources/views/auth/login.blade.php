<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mokka Pharmacy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-gray-800/80 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-2xl overflow-hidden relative">
        <!-- Decorative glow -->
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-emerald-500/20 blur-3xl rounded-full"></div>
        
        <div class="p-8 relative">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent mb-2">Auth Module</h1>
                <p class="text-gray-400 text-sm">Sign in to access your pharmacy ledgers</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email <span class="text-emerald-400">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', env('APP_ENV') === 'local' ? 'admin@mokka.com' : '') }}" required autofocus
                           class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all placeholder-gray-600"
                           placeholder="admin@mokka.com">
                    @error('email')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password <span class="text-emerald-400">*</span></label>
                    <input type="password" name="password" id="password" required value="{{ env('APP_ENV') === 'local' ? 'password' : '' }}"
                           class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-3 text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all placeholder-gray-600"
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 bg-gray-900 border-gray-700 rounded text-emerald-500 focus:ring-emerald-500/50">
                    <label for="remember" class="ml-2 text-sm text-gray-400">Remember me for 30 days</label>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-emerald-500/20 active:scale-[0.98] transition-all">
                    Sign In to Dashboard
                </button>
            </form>
        </div>
    </div>

</body>
</html>
