<x-app-layout>
    <x-slot:title>Dashboard - {{ $pharmacyName }}</x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white mb-2">Welcome Back 👋</h2>
            <p class="text-gray-400">Here's your pharmacy's financial overview for this month.</p>
        </div>
        
        @if($ledger)
            <a href="{{ route('ledger.show', $ledger) }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white font-medium rounded-lg shadow-lg shadow-emerald-500/20 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Go to Today's Ledger
            </a>
        @else
            <form action="{{ route('months.store') }}" method="POST">
                @csrf
                <input type="hidden" name="year" value="{{ now()->year }}">
                <input type="hidden" name="month" value="{{ now()->month }}">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white font-medium rounded-lg shadow-lg shadow-emerald-500/20 transition-all">
                    Create {{ now()->format('F Y') }} Ledger
                </button>
            </form>
        @endif
    </div>

    @if($ledger)
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Revenue -->
            <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-colors">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-emerald-500 group-hover:opacity-20 group-hover:scale-110 transition-all">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-400 mb-1">Total Revenue In</p>
                <p class="text-3xl font-bold text-white">{{ $currency }} {{ number_format($ledger->total_revenue, 2) }}</p>
                <div class="mt-4 flex items-center text-xs text-emerald-400">
                    <span class="px-2 py-1 bg-emerald-500/10 rounded-full">{{ $ledger->name }}</span>
                </div>
            </div>

            <!-- Expenses -->
            <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-red-500/50 transition-colors">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-red-500 group-hover:opacity-20 group-hover:scale-110 transition-all">
                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 9.586 3.707 5.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd"></path></svg>
                </div>
                <p class="text-sm font-medium text-gray-400 mb-1">Total Expenses Out</p>
                <p class="text-3xl font-bold text-white">{{ $currency }} {{ number_format($ledger->total_expenses, 2) }}</p>
                <div class="mt-4 flex items-center text-xs text-red-400">
                    <span class="px-2 py-1 bg-red-500/10 rounded-full bg-red-500/10 rounded-full">Includes Purchases & Costs</span>
                </div>
            </div>

            <!-- Net Cash -->
            <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group {{ $ledger->net_cash >= 0 ? 'hover:border-emerald-500/50' : 'hover:border-red-500/50' }} transition-colors">
                 <div class="absolute inset-0 bg-gradient-to-br {{ $ledger->net_cash >= 0 ? 'from-emerald-900/20 to-transparent' : 'from-red-900/20 to-transparent' }}"></div>
                <div class="relative z-10">
                    <p class="text-sm font-medium text-gray-400 mb-1">Net Cash (Incl. Prev Bal)</p>
                    <p class="text-3xl font-bold {{ $ledger->net_cash >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $currency }} {{ number_format($ledger->net_cash, 2) }}
                    </p>
                    <div class="mt-4 flex items-center text-xs text-gray-400">
                        <span class="px-2 py-1 bg-gray-700/50 rounded-full border border-gray-600/50">Prev Balance: {{ $currency }}{{ number_format($ledger->previous_balance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-gray-800/40 border border-dashed border-gray-700 rounded-2xl p-12 text-center mb-8">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h3 class="text-lg font-medium text-white mb-2">No active ledger for {{ now()->format('F Y') }}</h3>
            <p class="text-gray-400 max-w-sm mx-auto mb-6">Create the ledger for this month to start tracking your daily accounts, sales, and expenses.</p>
        </div>
    @endif

    <!-- Recent Ledgers -->
    <div>
        <h3 class="text-lg font-semibold text-white mb-4">Recent Months</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentLedgers as $recent)
                <a href="{{ route('ledger.show', $recent) }}" class="flex items-center justify-between p-4 bg-gray-800/40 border border-gray-700/50 hover:bg-gray-700/40 hover:border-gray-600 rounded-xl transition-all group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gray-700 flex items-center justify-center mr-4 group-hover:bg-emerald-500/20 group-hover:text-emerald-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $recent->name }}</p>
                            <p class="text-xs text-gray-400">{{ $recent->is_locked ? 'Locked (Read-only)' : 'Active' }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-emerald-400 font-medium text-sm">{{ $currency }}{{ number_format($recent->net_cash, 0) }}</span>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    No ledgers found. Create your first one to get started.
                </div>
            @endforelse
        </div>
        <div class="mt-4 text-right">
             <a href="{{ route('months.index') }}" class="text-sm text-emerald-400 hover:text-emerald-300 transition-colors">View all months &rarr;</a>
        </div>
    </div>
</x-app-layout>
