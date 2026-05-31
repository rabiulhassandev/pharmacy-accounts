<x-app-layout>
    <x-slot:title>Supplier Details</x-slot>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-6 bg-gray-800/40 p-6 rounded-2xl border border-gray-700/50 backdrop-blur-xl">
        <div>
            <h2 class="text-3xl font-extrabold text-white mb-3">{{ $supplier->name }}</h2>
            <div class="flex flex-wrap gap-3 text-sm text-gray-400 mb-4">
                @if($supplier->phone) <span class="flex items-center bg-gray-900/50 px-3 py-1.5 rounded-lg border border-gray-700/50"><svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>{{ $supplier->phone }}</span> @endif
                @if($supplier->email) <span class="flex items-center bg-gray-900/50 px-3 py-1.5 rounded-lg border border-gray-700/50"><svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>{{ $supplier->email }}</span> @endif
            </div>
            <div class="inline-flex items-center px-4 py-2 bg-red-500/10 rounded-xl border border-red-500/20 shadow-sm shadow-red-500/5">
                <span class="text-gray-400 font-medium mr-3">Total Due:</span>
                <span class="font-bold text-red-500 text-2xl tracking-tight">{{ $currency }}{{ number_format($supplier->total_due, 2) }}</span>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full md:w-auto">
            <a href="{{ route('suppliers.report.pdf', $supplier) }}" class="px-5 py-2.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 hover:text-amber-100 rounded-xl transition-all shadow-lg shadow-amber-500/10 border border-amber-400/30 hover:border-amber-300/50 text-sm font-semibold flex items-center justify-center hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-8m0 8l-3-3m3 3l3-3M5 20h14"></path></svg>
                Download Report
            </a>
            <button onclick="document.getElementById('purchaseModal').classList.remove('hidden')" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-all shadow-lg shadow-emerald-500/25 text-sm font-semibold flex items-center justify-center hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Purchase
            </button>
            <button onclick="document.getElementById('paymentModal').classList.remove('hidden')" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-all shadow-lg shadow-blue-500/25 text-sm font-semibold flex items-center justify-center hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Record Payment
            </button>
            <div class="hidden sm:block w-px h-8 bg-gray-700/80 mx-1"></div>
            @if($supplier->purchases()->count() === 0 && $supplier->payments()->count() === 0)
            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this supplier?');" class="mt-2 sm:mt-0 w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <button class="w-full sm:w-auto px-4 py-2.5 bg-gray-800/80 hover:bg-red-500/20 text-gray-400 hover:text-red-500 rounded-xl transition-colors border border-gray-700 hover:border-red-500/30 text-sm font-medium flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Delete
                </button>
            </form>
            @else
            <button disabled class="mt-2 sm:mt-0 w-full sm:w-auto px-4 py-2.5 bg-gray-800/50 text-gray-600 rounded-xl border border-gray-700/30 text-sm font-medium flex items-center justify-center cursor-not-allowed" title="Cannot delete supplier with transaction history">
                <svg class="w-4 h-4 mr-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete
            </button>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <!-- Purchase Modal -->
    <div id="purchaseModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/80 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('purchaseModal').classList.add('hidden')"></div>
            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700/50 shadow-2xl rounded-3xl sm:my-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <span class="p-2.5 bg-emerald-500/10 rounded-xl mr-3">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </span>
                        Add New Purchase
                    </h3>
                    <button type="button" onclick="document.getElementById('purchaseModal').classList.add('hidden')" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-gray-700/50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('suppliers.purchases.store', $supplier) }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date</label>
                            <input type="date" name="date" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner px-4 py-2.5" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                            <select name="category" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner text-sm px-4 py-3" required>
                                <option value="Company">Company</option>
                                <option value="Shop">Shop</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Voucher No.</label>
                            <input type="text" name="voucher_no" placeholder="Optional" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner px-4 py-2.5">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 border-t border-gray-700/50 pt-5 mt-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Total Amount</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="total_amount" class="px-4 w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner py-2.5" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Paid Amount</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="paid_amount" class="px-4 w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner py-2.5" required>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Details / Notes</label>
                        <textarea name="details" rows="3" placeholder="Purchase details..." class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner resize-none text-sm p-4"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 transition-colors flex items-center justify-center text-lg">
                            Save Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/80 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('paymentModal').classList.add('hidden')"></div>
            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700/50 shadow-2xl rounded-3xl sm:my-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <span class="p-2.5 bg-blue-500/10 rounded-xl mr-3">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        Record Payment
                    </h3>
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-gray-700/50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('suppliers.payments.store', $supplier) }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date</label>
                            <input type="date" name="date" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-inner px-4 py-2.5" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Amount Paid</label>
                            <div class="relative">
                                <input id="payment_amount" type="number" step="0.01" name="amount" class="px-4 w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-inner py-2.5" required>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-700/50 pt-5 mt-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Apply to Purchase (Optional)</label>
                        <select id="payment_purchase_id" name="purchase_id" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-inner text-sm px-4 py-3">
                            <option value="">-- Apply to General Balance --</option>
                            @foreach($unpaidPurchases as $txn)
                                <option value="{{ $txn->id }}">Voucher: {{ $txn->voucher_no ?? 'N/A' }} | Date: {{ \Carbon\Carbon::parse($txn->date)->format('M d') }} | Due: {{ $currency }}{{ number_format($txn->due_amount, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Details or Notes</label>
                        <textarea name="details" rows="3" placeholder="Payment method or transaction notes..." class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-inner resize-none text-sm p-4"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition-colors flex items-center justify-center text-lg">
                            Apply Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Purchases List -->
        <div class="bg-gray-800/60 backdrop-blur-xl rounded-2xl overflow-hidden border border-gray-700/50 shadow-lg flex flex-col h-full">
            <div class="px-6 py-5 border-b border-gray-700/50 bg-gray-800/80 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <span class="p-2 bg-emerald-500/10 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                    Purchase History
                </h3>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-gray-900/40 text-gray-400 uppercase text-xs border-b border-gray-700/50 tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Date & Voucher</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-right">Status</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-200 font-medium mb-1.5">{{ \Carbon\Carbon::parse($purchase->date)->format('M d, Y') }}</div>
                                <div class="flex flex-wrap gap-1.5 items-center">
                                    @if($purchase->voucher_no)
                                        <span class="font-mono text-xs bg-gray-900 px-2 py-0.5 rounded border border-gray-700 text-gray-400">#{{ $purchase->voucher_no }}</span>
                                    @else
                                        <span class="text-xs text-gray-500">No voucher</span>
                                    @endif
                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider {{ $purchase->category === 'Company' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : ($purchase->category === 'Shop' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                        {{ $purchase->category }}
                                    </span>
                                </div>
                                @if($purchase->details)
                                    <div class="text-xs text-gray-500 mt-1 max-w-[150px] truncate" title="{{ $purchase->details }}">{{ $purchase->details }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-white">{{ $currency }}{{ number_format($purchase->total_amount, 2) }}</div>
                                <div class="text-xs text-gray-500 mt-1">Paid: {{ $currency }}{{ number_format($purchase->paid_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($purchase->due_amount > 0)
                                    <div class="text-red-400 font-bold">{{ $currency }}{{ number_format($purchase->due_amount, 2) }}</div>
                                    <div class="text-[10px] text-red-500/70 uppercase font-bold tracking-widest mt-1">Due</div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wider">Paid</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center align-middle">
                                @if($purchase->due_amount > 0)
                                <button onclick="document.getElementById('paymentModal').classList.remove('hidden'); document.getElementById('payment_purchase_id').value='{{ $purchase->id }}'; document.getElementById('payment_amount').value='{{ $purchase->due_amount }}';" class="text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 px-3 py-2 rounded-lg hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                    Pay Due
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                <div class="bg-gray-800/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-700/50">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.866.5l-1.4 2.5a1 1 0 01-.866.5H8.72a1 1 0 01-.866-.5L6.45 13.5a1 1 0 00-.866-.5H3"></path></svg>
                                </div>
                                <p class="font-medium text-gray-400">No purchases yet</p>
                                <p class="text-xs mt-1">Records will appear here once added.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-900/30">
                {{ $purchases->links() }}
            </div>
            @endif
        </div>

        <!-- Payments List -->
        <div class="bg-gray-800/60 backdrop-blur-xl rounded-2xl overflow-hidden border border-gray-700/50 shadow-lg flex flex-col h-full">
            <div class="px-6 py-5 border-b border-gray-700/50 bg-gray-800/80 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <span class="p-2 bg-blue-500/10 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    Payment History
                </h3>
            </div>
            <div class="p-0 flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-gray-900/40 text-gray-400 uppercase text-xs border-b border-gray-700/50 tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Applied To</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($payments as $payment)
                        <tr class="hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-gray-200 font-medium mb-1">{{ \Carbon\Carbon::parse($payment->date)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 bg-gray-900/50 inline-block px-2 py-0.5 rounded border border-gray-700/50">{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->purchase_id && $payment->purchase)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium bg-gray-900 text-gray-300 border border-gray-700 shadow-sm">
                                        Voucher #{{ $payment->purchase->voucher_no ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="text-gray-500 bg-gray-800 px-2.5 py-1 rounded text-xs font-medium border border-gray-700 shadow-sm">General Balance</span>
                                @endif
                                @if($payment->details)
                                    <p class="text-xs text-gray-400 mt-2 italic border-l-2 border-gray-600 pl-2">{{ Str::limit($payment->details, 40) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right align-middle">
                                <div class="font-bold text-blue-400 text-lg">{{ $currency }}{{ number_format($payment->amount, 2) }}</div>
                                <div class="text-[10px] text-blue-500/70 uppercase font-bold tracking-widest mt-1">Paid</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center text-gray-500">
                                <div class="bg-gray-800/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-700/50">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <p class="font-medium text-gray-400">No payments yet</p>
                                <p class="text-xs mt-1">Records will appear here once added.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-900/30">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
