<x-app-layout>
    <x-slot:title>Settings - Mokka Pharmacy</x-slot>

    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-white mb-6">Pharmacy Settings</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- General Settings -->
            <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700/50 bg-gray-800/80">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        General Preferences
                    </h3>
                </div>
                
                <form action="{{ route('settings.update') }}" method="POST" class="p-6 space-y-6">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Pharmacy Name</label>
                        <input type="text" name="pharmacy_name" value="{{ old('pharmacy_name', $pharmacyName) }}" required 
                               class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                        <p class="text-xs text-gray-500 mt-1">This name appears on the dashboard and all exported reports.</p>
                        @error('pharmacy_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Currency Symbol</label>
                        <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $currency) }}" required 
                               class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all max-w-[150px]">
                        @error('currency_symbol') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-medium shadow-lg shadow-emerald-500/20 transition-all">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change -->
            <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-xl overflow-hidden">
                 <div class="p-6 border-b border-gray-700/50 bg-gray-800/80">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        Change Password
                    </h3>
                </div>

                <form action="{{ route('settings.password') }}" method="POST" class="p-6 space-y-6">
                    @csrf @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Current Password</label>
                        <input type="password" name="current_password" required 
                               class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                        @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">New Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required minlength="8"
                               class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-xl font-medium transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
