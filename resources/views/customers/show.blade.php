<x-app-layout>
    <x-slot:title>Customer Details</x-slot>
    <div class="mb-8 flex flex-col gap-6 rounded-2xl border border-gray-700/50 bg-gray-800/40 p-6 backdrop-blur-xl md:flex-row md:items-end md:justify-between">
        <div>
            <h2 class="mb-3 text-3xl font-extrabold text-white">{{ $customer->name }}</h2>
            <div class="mb-4 flex flex-wrap gap-3 text-sm text-gray-400">
                @if ($customer->phone)
                    <span class="flex items-center rounded-lg border border-gray-700/50 bg-gray-900/50 px-3 py-1.5">
                        <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>{{ $customer->phone }}
                    </span>
                @endif
                @if ($customer->email)
                    <span class="flex items-center rounded-lg border border-gray-700/50 bg-gray-900/50 px-3 py-1.5">
                        <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>{{ $customer->email }}
                    </span>
                @endif
            </div>
            <div class="inline-flex items-center rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-2 shadow-sm shadow-red-500/5">
                <span class="mr-3 font-medium text-gray-400">Total Due:</span>
                <span class="text-2xl font-bold tracking-tight text-red-500">{{ $currency }}{{ number_format($customer->total_due, 2) }}</span>
            </div>
        </div>
        <div class="flex w-full flex-col flex-wrap items-stretch gap-3 sm:flex-row sm:items-center md:w-auto">
            <a href="{{ route('customers.report.pdf', $customer) }}" class="flex items-center justify-center rounded-xl border border-amber-400/30 bg-amber-500/10 px-5 py-2.5 text-sm font-semibold text-amber-300 shadow-lg shadow-amber-500/10 transition-all hover:scale-105 hover:border-amber-300/50 hover:bg-amber-500/20 hover:text-amber-100 active:scale-95">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-8m0 8l-3-3m3 3l3-3M5 20h14"></path></svg>
                Download Report
            </a>
            <button onclick="document.getElementById('saleModal').classList.remove('hidden')" class="flex items-center justify-center rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 transition-all hover:scale-105 hover:bg-emerald-600 active:scale-95">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Sale
            </button>
            <button onclick="document.getElementById('paymentModal').classList.remove('hidden')" class="flex items-center justify-center rounded-xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/25 transition-all hover:scale-105 hover:bg-blue-600 active:scale-95">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Record Payment
            </button>
            <div class="mx-1 hidden h-8 w-px bg-gray-700/80 sm:block"></div>
            @if (! $hasTransactions)
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete this customer?');" class="mt-2 w-full sm:mt-0 sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button class="flex w-full items-center justify-center rounded-xl border border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-400 transition-colors hover:border-red-500/30 hover:bg-red-500/20 hover:text-red-500 sm:w-auto">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Delete
                    </button>
                </form>
            @else
                <button disabled class="mt-2 flex w-full cursor-not-allowed items-center justify-center rounded-xl border border-gray-700/30 bg-gray-800/50 px-4 py-2.5 text-sm font-medium text-gray-600 sm:mt-0 sm:w-auto" title="Cannot delete customer with transaction history">
                    <svg class="mr-2 h-4 w-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Delete
                </button>
            @endif
        </div>
    </div>

    <div id="saleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="sale-modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('saleModal').classList.add('hidden')"></div>
            <div class="relative inline-block w-full max-w-md overflow-hidden rounded-3xl border border-gray-700/50 bg-gray-800 p-6 text-left align-middle shadow-2xl transition-all sm:my-8">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="flex items-center text-2xl font-bold text-white">
                        <span class="mr-3 rounded-xl bg-emerald-500/10 p-2.5">
                            <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </span>
                        Add New Sale
                    </h3>
                    <button type="button" onclick="document.getElementById('saleModal').classList.add('hidden')" class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-700/50 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('customers.sales.store', $customer) }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300">Date</label>
                            <input type="date" name="date" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-2.5 text-white shadow-inner transition-colors focus:border-emerald-500 focus:ring-emerald-500" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300">Category</label>
                            <select name="category" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-3 text-sm text-white shadow-inner transition-colors focus:border-emerald-500 focus:ring-emerald-500" required>
                                <option value="Daily Sale">Daily Sale</option>
                                <option value="Hole Sale">Hole Sale</option>
                                <option value="Other Sale">Other Sale</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-gray-300">Invoice No.</label>
                            <input type="text" name="invoice_no" placeholder="Optional" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-2.5 text-white shadow-inner transition-colors focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="mt-2 grid grid-cols-1 gap-5 border-t border-gray-700/50 pt-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300">Total Amount</label>
                            <input type="number" step="0.01" name="total_amount" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-2.5 text-white shadow-inner transition-colors focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300">Collected Amount</label>
                            <input type="number" step="0.01" name="paid_amount" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-2.5 text-white shadow-inner transition-colors focus:border-emerald-500 focus:ring-emerald-500" value="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-300">Details / Notes</label>
                        <textarea name="details" rows="3" placeholder="Sale details..." class="w-full resize-none rounded-xl border border-gray-700 bg-gray-900/80 p-4 text-sm text-white shadow-inner transition-colors focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-emerald-500 py-3.5 text-lg font-bold text-white shadow-lg shadow-emerald-500/25 transition-colors hover:bg-emerald-600">
                            Save Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="payment-modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('paymentModal').classList.add('hidden')"></div>
            <div class="relative inline-block w-full max-w-md overflow-hidden rounded-3xl border border-gray-700/50 bg-gray-800 p-6 text-left align-middle shadow-2xl transition-all sm:my-8">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="flex items-center text-2xl font-bold text-white">
                        <span class="mr-3 rounded-xl bg-blue-500/10 p-2.5">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </span>
                        Record Payment
                    </h3>
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="rounded-xl p-2 text-gray-400 transition-colors hover:bg-gray-700/50 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('customers.payments.store', $customer) }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300">Date</label>
                            <input type="date" name="date" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-2.5 text-white shadow-inner transition-colors focus:border-blue-500 focus:ring-blue-500" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-300">Amount Received</label>
                            <input id="payment_amount" type="number" step="0.01" name="amount" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-2.5 text-white shadow-inner transition-colors focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>
                    <div class="mt-2 border-t border-gray-700/50 pt-5">
                        <label class="mb-2 block text-sm font-medium text-gray-300">Apply to Sale (Optional)</label>
                        <select id="payment_sale_id" name="sale_id" class="w-full rounded-xl border border-gray-700 bg-gray-900/80 px-4 py-3 text-sm text-white shadow-inner transition-colors focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Apply to General Balance --</option>
                            @foreach ($unpaidSales as $sale)
                                <option value="{{ $sale->id }}">Invoice: {{ $sale->invoice_no ?? 'N/A' }} | Date: {{ \Carbon\Carbon::parse($sale->date)->format('M d') }} | Due: ${{ number_format($sale->due_amount, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-300">Details or Notes</label>
                        <textarea name="details" rows="3" placeholder="Payment method or transaction notes..." class="w-full resize-none rounded-xl border border-gray-700 bg-gray-900/80 p-4 text-sm text-white shadow-inner transition-colors focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-blue-500 py-3.5 text-lg font-bold text-white shadow-lg shadow-blue-500/25 transition-colors hover:bg-blue-600">
                            Apply Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-700/50 bg-gray-800/60 shadow-lg backdrop-blur-xl">
            <div class="flex items-center justify-between border-b border-gray-700/50 bg-gray-800/80 px-6 py-5">
                <h3 class="flex items-center text-lg font-bold text-white">
                    <span class="mr-3 rounded-lg bg-emerald-500/10 p-2">
                        <svg class="h-5 w-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </span>
                    Sales History
                </h3>
            </div>
            <div class="flex-1 overflow-x-auto p-0">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="border-b border-gray-700/50 bg-gray-900/40 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Date & Invoice</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-right">Status</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse ($sales as $sale)
                            <tr class="group transition-colors hover:bg-gray-700/30">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="mb-1.5 font-medium text-gray-200">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</div>
                                    <div class="flex flex-wrap gap-1.5 items-center">
                                        @if ($sale->invoice_no)
                                            <span class="rounded border border-gray-700 bg-gray-900 px-2 py-0.5 font-mono text-xs text-gray-400">#{{ $sale->invoice_no }}</span>
                                        @else
                                            <span class="text-xs text-gray-500">No invoice</span>
                                        @endif
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider {{ $sale->category === 'Daily Sale' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($sale->category === 'Hole Sale' ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                            {{ $sale->category }}
                                        </span>
                                    </div>
                                    @if ($sale->details)
                                        <div class="mt-1 max-w-[150px] truncate text-xs text-gray-500" title="{{ $sale->details }}">{{ $sale->details }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold text-white">{{ $currency }}{{ number_format($sale->total_amount, 2) }}</div>
                                    <div class="mt-1 text-xs text-gray-500">Collected: {{ $currency }}{{ number_format($sale->paid_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($sale->due_amount > 0)
                                        <div class="font-bold text-red-400">{{ $currency }}{{ number_format($sale->due_amount, 2) }}</div>
                                        <div class="mt-1 text-[10px] font-bold uppercase tracking-widest text-red-500/70">Due</div>
                                    @else
                                        <span class="inline-flex items-center rounded-md border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-400">Paid</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center align-middle">
                                    @if ($sale->due_amount > 0)
                                        <button onclick="document.getElementById('paymentModal').classList.remove('hidden'); document.getElementById('payment_sale_id').value='{{ $sale->id }}'; document.getElementById('payment_amount').value='{{ $sale->due_amount }}';" class="rounded-lg border border-blue-500/20 bg-blue-500/10 px-3 py-2 text-xs font-bold text-blue-400 shadow-sm transition-all hover:bg-blue-500 hover:text-white">
                                            Receive Due
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-gray-700/50 bg-gray-800/50">
                                        <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="font-medium text-gray-400">No sales yet</p>
                                    <p class="mt-1 text-xs">Records will appear here once added.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($sales->hasPages())
                <div class="border-t border-gray-700/50 bg-gray-900/30 px-6 py-4">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>

        <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-700/50 bg-gray-800/60 shadow-lg backdrop-blur-xl">
            <div class="flex items-center justify-between border-b border-gray-700/50 bg-gray-800/80 px-6 py-5">
                <h3 class="flex items-center text-lg font-bold text-white">
                    <span class="mr-3 rounded-lg bg-blue-500/10 p-2">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    Received Payments
                </h3>
            </div>
            <div class="flex-1 overflow-x-auto p-0">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="border-b border-gray-700/50 bg-gray-900/40 text-xs font-semibold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Applied To</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse ($payments as $payment)
                            <tr class="transition-colors hover:bg-gray-700/30">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="mb-1 font-medium text-gray-200">{{ \Carbon\Carbon::parse($payment->date)->format('M d, Y') }}</div>
                                    <div class="inline-block rounded border border-gray-700/50 bg-gray-900/50 px-2 py-0.5 text-xs text-gray-500">{{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($payment->sale_id && $payment->sale)
                                        <span class="inline-flex items-center rounded border border-gray-700 bg-gray-900 px-2.5 py-1 text-xs font-medium text-gray-300 shadow-sm">
                                            Invoice #{{ $payment->sale->invoice_no ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="rounded border border-gray-700 bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-500 shadow-sm">General Balance</span>
                                    @endif
                                    @if ($payment->details)
                                        <p class="mt-2 border-l-2 border-gray-600 pl-2 text-xs italic text-gray-400">{{ \Illuminate\Support\Str::limit($payment->details, 40) }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right align-middle">
                                    <div class="text-lg font-bold text-blue-400">{{ $currency }}{{ number_format($payment->amount, 2) }}</div>
                                    <div class="mt-1 text-[10px] font-bold uppercase tracking-widest text-blue-500/70">Received</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-16 text-center text-gray-500">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full border border-gray-700/50 bg-gray-800/50">
                                        <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <p class="font-medium text-gray-400">No payments yet</p>
                                    <p class="mt-1 text-xs">Records will appear here once added.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payments->hasPages())
                <div class="border-t border-gray-700/50 bg-gray-900/30 px-6 py-4">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
