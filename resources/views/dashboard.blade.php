<x-app-layout>
    <x-slot:title>Dashboard - {{ $pharmacyName }}</x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-white mb-2">Financial Cockpit 👋</h2>
            <p class="text-gray-400">Real-time statistics and accounts overview for {{ $pharmacyName }}.</p>
        </div>
        <div>
            <a href="{{ route('reports.monthly.pdf', ['month' => now()->format('Y-m')]) }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 text-emerald-400 font-semibold text-sm hover:bg-emerald-500/20 hover:border-emerald-500/50 hover:text-white transition-all shadow-lg shadow-emerald-500/5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download Monthly Report
            </a>
        </div>
    </div>

    <!-- Cockpit Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Card 1: Sale Amount -->
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-cyan-500/50 transition-colors shadow-lg shadow-black/20">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-cyan-400 group-hover:opacity-20 group-hover:scale-110 transition-all">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <p class="text-xs font-bold text-cyan-400 uppercase tracking-widest mb-1.5">Sales Volume</p>
            <h3 class="text-gray-400 text-sm font-semibold mb-1">Today's Sales</h3>
            <p class="text-3xl font-bold text-white mb-3 tracking-tight">{{ $currency }}{{ number_format($saleToday, 2) }}</p>
            <div class="pt-3.5 border-t border-gray-700/50 flex justify-between items-center text-xs">
                <span class="text-gray-400 font-medium">This Month:</span>
                <span class="text-cyan-400 font-bold text-sm">{{ $currency }}{{ number_format($saleMonthly, 2) }}</span>
            </div>
        </div>

        <!-- Card 2: Customer Due -->
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-amber-500/50 transition-colors shadow-lg shadow-black/20">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-amber-500 group-hover:opacity-20 group-hover:scale-110 transition-all">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <p class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-1.5">Customer Receivables</p>
            <h3 class="text-gray-400 text-sm font-semibold mb-1">Dues Created Today</h3>
            <p class="text-3xl font-bold text-white mb-3 tracking-tight">{{ $currency }}{{ number_format($customerDueToday, 2) }}</p>
            <div class="pt-3.5 border-t border-gray-700/50 flex justify-between items-center text-xs">
                <span class="text-gray-400 font-medium">This Month's Created Due:</span>
                <span class="text-amber-400 font-bold text-sm">{{ $currency }}{{ number_format($customerDueMonthly, 2) }}</span>
            </div>
        </div>

        <!-- Card 3: Total Purchase -->
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-colors shadow-lg shadow-black/20">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-emerald-400 group-hover:opacity-20 group-hover:scale-110 transition-all">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <p class="text-xs font-bold text-emerald-400 uppercase tracking-widest mb-1.5">Procurement</p>
            <h3 class="text-gray-400 text-sm font-semibold mb-1">Today's Purchases</h3>
            <p class="text-3xl font-bold text-white mb-3 tracking-tight">{{ $currency }}{{ number_format($purchaseToday, 2) }}</p>
            <div class="pt-3.5 border-t border-gray-700/50 flex justify-between items-center text-xs">
                <span class="text-gray-400 font-medium">This Month:</span>
                <span class="text-emerald-400 font-bold text-sm">{{ $currency }}{{ number_format($purchaseMonthly, 2) }}</span>
            </div>
        </div>

        <!-- Card 4: Supplier Due -->
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-red-500/50 transition-colors shadow-lg shadow-black/20">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-red-500 group-hover:opacity-20 group-hover:scale-110 transition-all">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-xs font-bold text-red-400 uppercase tracking-widest mb-1.5">Supplier Payables</p>
            <h3 class="text-gray-400 text-sm font-semibold mb-1">Dues Incurred Today</h3>
            <p class="text-3xl font-bold text-white mb-3 tracking-tight">{{ $currency }}{{ number_format($supplierDueToday, 2) }}</p>
            <div class="pt-3.5 border-t border-gray-700/50 flex justify-between items-center text-xs">
                <span class="text-gray-400 font-medium">This Month's Incurred Due:</span>
                <span class="text-red-400 font-bold text-sm">{{ $currency }}{{ number_format($supplierDueMonthly, 2) }}</span>
            </div>
        </div>

        <!-- Card 5: Total Expenses -->
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-orange-500/50 transition-colors shadow-lg shadow-black/20">
            <div class="absolute top-0 right-0 p-4 opacity-10 text-orange-400 group-hover:opacity-20 group-hover:scale-110 transition-all">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <p class="text-xs font-bold text-orange-400 uppercase tracking-widest mb-1.5">Operational Cost</p>
            <h3 class="text-gray-400 text-sm font-semibold mb-1">Today's Expenses</h3>
            <p class="text-3xl font-bold text-white mb-3 tracking-tight">{{ $currency }}{{ number_format($expenseToday, 2) }}</p>
            <div class="pt-3.5 border-t border-gray-700/50 flex justify-between items-center text-xs">
                <span class="text-gray-400 font-medium">This Month:</span>
                <span class="text-orange-400 font-bold text-sm">{{ $currency }}{{ number_format($expenseMonthly, 2) }}</span>
            </div>
        </div>

        <!-- Card 6: Current Cash -->
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-400/50 transition-colors shadow-lg shadow-black/20">
            <div class="absolute inset-0 bg-gradient-to-br {{ $currentCash >= 0 ? 'from-emerald-900/10 to-transparent' : 'from-red-900/10 to-transparent' }}"></div>
            <div class="relative z-10">
                <div class="absolute top-0 right-0 p-4 opacity-10 {{ $currentCash >= 0 ? 'text-emerald-400' : 'text-red-400' }} group-hover:opacity-20 group-hover:scale-110 transition-all">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs font-bold {{ $currentCash >= 0 ? 'text-emerald-400' : 'text-red-400' }} uppercase tracking-widest mb-1.5">Net Liquidity</p>
                <h3 class="text-gray-400 text-sm font-semibold mb-1">Current Vault Cash</h3>
                <p class="text-3xl font-extrabold {{ $currentCash >= 0 ? 'text-emerald-400' : 'text-red-400' }} mb-3 tracking-tight">{{ $currency }}{{ number_format($currentCash, 2) }}</p>
                <div class="pt-3.5 border-t border-gray-700/50 flex justify-between items-center text-xs">
                    <span class="text-gray-500 font-medium">Calculation Model:</span>
                    <span class="text-gray-400 font-semibold text-xs border border-gray-700 rounded px-2 py-0.5 bg-gray-900/40">All-Time Net Cash</span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
