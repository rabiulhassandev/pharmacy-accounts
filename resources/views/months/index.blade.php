<x-app-layout>
    <x-slot:title>Month Manager - Mokka Pharmacy</x-slot>

    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Month Manager</h2>
            <p class="text-gray-400 mt-1">Create, view, and manage your monthly accounts.</p>
        </div>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white rounded-lg shadow-lg shadow-emerald-500/20 font-medium transition-all">
            + New Month
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($ledgers as $ledger)
            <div class="bg-gray-800/60 border {{ $ledger->is_locked ? 'border-gray-700/50 opacity-80' : 'border-emerald-500/30' }} rounded-2xl overflow-hidden flex flex-col hover:border-gray-500 transition-colors relative group">
                <!-- Card Header -->
                <div class="p-5 border-b border-gray-700/50 flex justify-between items-center bg-gray-800/80">
                    <h3 class="font-bold text-lg text-white">
                        <a href="{{ route('ledger.show', $ledger) }}" class="hover:text-emerald-400 transition-colors before:absolute before:inset-0 before:z-0">{{ $ledger->name }}</a>

                    </h3>
                    @if($ledger->is_locked)
                        <span class="px-2.5 py-1 bg-red-500/10 text-red-400 text-xs font-semibold rounded-full border border-red-500/20 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg> Locked
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-semibold rounded-full border border-emerald-500/20 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"></path></svg> Active
                        </span>
                    @endif
                </div>
                
                <!-- Card Body -->
                <div class="p-5 flex-1 text-sm space-y-3 text-gray-300">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Prev. Balance:</span>
                        <span>{{ number_format($ledger->previous_balance, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Net Cash:</span>
                        <span class="{{ $ledger->net_cash >= 0 ? 'text-emerald-400' : 'text-red-400' }} font-medium">
                            {{ number_format($ledger->net_cash, 2) }}
                        </span>
                    </div>
                    <div class="pt-2 border-t border-gray-700/30 flex justify-between font-semibold">
                        <span>Total Balance:</span>
                        <span class="text-white">
                           {{ \App\Models\Setting::get('currency_symbol', '৳') }} {{ number_format($ledger->previous_balance + $ledger->net_cash, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="px-5 py-3 border-t border-gray-700/50 bg-gray-900/30 flex justify-between items-center relative z-10">
                    <div>
                        @if(!$ledger->is_locked)
                            <a href="{{ route('ledger.show', $ledger) }}" class="inline-flex items-center px-4 py-1.5 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white border border-emerald-500/20 hover:border-emerald-500 text-xs font-semibold rounded-lg transition-all">
                                Enter Data <span class="ml-1">&rarr;</span>
                            </a>
                        @endif
                    </div>
                    <div class="flex gap-2 text-sm">
                        <a href="{{ route('export.excel', $ledger) }}" title="Export Excel" class="p-2 text-emerald-400 hover:bg-emerald-400/10 rounded-lg transition-colors">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </a>
                        
                        <form action="{{ route('months.lock', $ledger) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" title="{{ $ledger->is_locked ? 'Unlock' : 'Lock' }}" class="p-2 {{ $ledger->is_locked ? 'text-gray-400 hover:text-white' : 'text-amber-500 hover:bg-amber-500/10' }} rounded-lg transition-colors">
                                @if($ledger->is_locked)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                @endif
                            </button>
                        </form>

                        <form action="{{ route('months.destroy', $ledger) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ledger? This will permanently delete all data for {{ $ledger->name }}.');">
                            @csrf @method('DELETE')
                            <button type="submit" title="Delete" class="p-2 text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Modal Backdrop -->
    <div id="createModal" class="hidden fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-800 border border-gray-700/80 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-700 bg-gray-800/80 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Create New Month</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('months.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Year</label>
                            <input type="number" name="year" value="{{ now()->year }}" required min="2000" max="2100" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Month</label>
                            <select name="month" required class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all [&>option]:bg-gray-900">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ now()->month === $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Previous Balance (Carry Forward)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ \App\Models\Setting::get('currency_symbol', '৳') }}</span>
                            <input type="number" step="0.01" name="previous_balance" value="0.00" class="w-full bg-gray-900 border border-gray-700 rounded-xl pl-10 pr-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Enter the total cash left from the previous month.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="px-5 py-2.5 border border-gray-600 text-gray-300 hover:bg-gray-700 rounded-xl font-medium transition-colors">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-medium shadow-lg shadow-emerald-500/20 transition-all">Create Ledger</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
