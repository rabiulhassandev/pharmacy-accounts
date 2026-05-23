<x-app-layout>
    <x-slot:title>{{ $ledger->name }} Ledger - Mokka Pharmacy</x-slot>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isLocked = {{ $ledger->is_locked ? 'true' : 'false' }};
            if (isLocked) {
                document.querySelectorAll('input.cell-input').forEach(input => input.disabled = true);
                return;
            }

            const inputs = document.querySelectorAll('input.cell-input');
            let timeout = null;

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const row = this.closest('tr');
                    const date = row.dataset.date;
                    const entryId = row.dataset.entryId;
                    
                    // Clear previous visual status
                    this.classList.remove('border-transparent');
                    this.classList.add('border-amber-500', 'bg-amber-500/10');

                    clearTimeout(timeout);
                    timeout = setTimeout(() => saveRow(row, date, entryId), 1000);
                });
            });

            async function saveRow(row, date, entryId) {
                const inputs = row.querySelectorAll('input.cell-input');
                const data = {
                    _token: '{{ csrf_token() }}',
                    entry_date: date
                };

                inputs.forEach(input => {
                    data[input.name] = input.value || 0;
                    input.classList.remove('border-amber-500', 'bg-amber-500/10');
                    input.classList.add('border-emerald-500', 'bg-emerald-500/10');
                });

                const url = entryId 
                    ? `/ledger/entries/${entryId}` 
                    : `/ledger/{{ $ledger->id }}/entries`;
                
                const method = entryId ? 'PATCH' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    
                    if (response.ok) {
                        // Update calculated fields in the row
                        row.dataset.entryId = result.entry.id;
                        row.querySelector('.total-payment').textContent = Number(result.total_payment).toFixed(2);
                        row.querySelector('.cash-flow').textContent = Number(result.cash).toFixed(2);
                        
                        // Flash success on inputs
                        setTimeout(() => {
                            inputs.forEach(input => {
                                input.classList.remove('border-emerald-500', 'bg-emerald-500/10');
                                input.classList.add('border-transparent');
                            });
                        }, 1000);

                        // Note: doing a full page reload for now to update running totals and column totals properly
                        // In a more complex SPA, we'd recalculate everything client-side.
                        window.location.reload();
                    } else {
                        console.error('Save failed:', result);
                        inputs.forEach(input => {
                            input.classList.add('border-red-500', 'bg-red-500/10');
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        });
    </script>
    @endpush

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('months.index') }}" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="text-2xl font-bold text-white flex items-center">
                    {{ $ledger->name }} Returns
                    @if($ledger->is_locked)
                        <span class="ml-3 px-2.5 py-1 bg-red-500/10 text-red-400 text-xs font-semibold rounded-full border border-red-500/20 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg> Locked
                        </span>
                    @endif
                </h2>
            </div>
            <p class="text-gray-400 text-sm ml-9">All amounts in {{ $currency }}</p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('export.excel', $ledger) }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-white rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </a>
            <a href="{{ route('export.pdf', $ledger) }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-white rounded-lg transition-colors text-sm font-medium">
                <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </a>
            @if(!$ledger->is_locked && $ledger->year == now()->year && $ledger->month == now()->month)
             <button onclick="document.getElementById('row-{{ $todayKey }}').scrollIntoView({behavior: 'smooth', block: 'center'}); document.getElementById('row-{{ $todayKey }}').classList.add('bg-emerald-900/30'); setTimeout(()=>document.getElementById('row-{{ $todayKey }}').classList.remove('bg-emerald-900/30'), 2000)" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white rounded-lg transition-colors text-sm font-medium shadow-lg shadow-emerald-500/20">
                Go to Today
            </button>
            @endif
        </div>
    </div>

    @if(!$ledger->is_locked)
    <div class="mb-4 text-xs text-gray-400 bg-gray-800/50 p-3 rounded-lg border border-gray-700 inline-block">
        💡 <strong class="text-white">Pro tip:</strong> Edits save automatically 1 second after you stop typing.
    </div>
    @endif

    <!-- Ledger Table Container -->
    <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700/50 rounded-2xl shadow-xl overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse min-w-[2000px]">
                <thead>
                    <!-- Super headers for grouping -->
                    <tr class="bg-gray-900/80 border-b border-gray-700">
                        <th class="p-3 font-semibold text-gray-300 border-r border-gray-700 sticky left-0 z-10 bg-gray-900"></th>
                        <th colspan="3" class="p-3 text-center font-bold text-sky-400 border-r border-sky-900/50 bg-sky-900/10">PURCHASES (IN)</th>
                        <th colspan="4" class="p-3 text-center font-bold text-orange-400 border-r border-orange-900/50 bg-orange-900/10">PAYMENTS (OUT)</th>
                        <th colspan="5" class="p-3 text-center font-bold text-emerald-400 border-r border-emerald-900/50 bg-emerald-900/10">SALES (IN)</th>
                        <th colspan="5" class="p-3 text-center font-bold text-rose-400 border-r border-rose-900/50 bg-rose-900/10">COSTS (OUT)</th>
                        <th colspan="3" class="p-3 text-center font-bold text-indigo-400 bg-indigo-900/10">CASH FLOW</th>
                    </tr>
                    <!-- Sub headers -->
                    <tr class="bg-gray-800 border-b border-gray-700 uppercase text-xs">
                        <th class="p-3 font-medium text-gray-400 border-r border-gray-700 sticky left-0 z-10 bg-gray-800 shadow-[1px_0_0_0_#374151]">Date</th>
                        
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50 whitespace-nowrap">Med Purchase<br><span class="text-gray-500">Company</span></th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50 whitespace-nowrap">Med Purchase<br><span class="text-gray-500">Shop</span></th>
                        <th class="p-3 font-medium text-gray-300 border-r border-sky-900/50 whitespace-nowrap">Med Purchase<br><span class="text-gray-500">Other</span></th>
                        
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Payment<br><span class="text-gray-500">Company</span></th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Payment<br><span class="text-gray-500">Shop</span></th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Payment<br><span class="text-gray-500">Other</span></th>
                        <th class="p-3 font-bold text-orange-400 border-r border-orange-900/50">Total<br>Payment</th>
                        
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Daily<br>Sale</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Hole<br>Sale</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Other<br>Sale</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Due<br>Purchase</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-emerald-900/50">Due<br>Sale</th>
                        
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Daily Staff<br>Cost</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Other<br>Cost</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Salary</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-gray-700/50">Bill</th>
                        <th class="p-3 font-medium text-gray-300 border-r border-rose-900/50">Rent</th>
                        
                        <th class="p-3 font-bold text-indigo-400 border-r border-gray-700">Cash</th>
                        <th class="p-3 font-bold text-indigo-400 border-r border-gray-700">Total</th>
                        <th class="p-3 font-medium text-gray-400">Previous<br>Balance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @php 
                        $totals = array_fill_keys(['mpc', 'mps', 'mpo', 'pc', 'ps', 'po', 'tp', 'ds', 'hs', 'os', 'dp', 'dus', 'dsc', 'oc', 's', 'b', 'r', 'c'], 0);
                    @endphp

                    @foreach($rows as $row)
                        @php
                            $entry = $row['entry'];
                            $dateStr = $row['date']->format('Y-m-d');
                            $isToday = $dateStr === $todayKey;
                            
                            // Accumulate totals
                            if ($entry) {
                                $totals['mpc'] += $entry->medicine_purchase_company;
                                $totals['mps'] += $entry->medicine_purchase_shop;
                                $totals['mpo'] += $entry->medicine_purchase_other;
                                $totals['pc'] += $entry->payment_company;
                                $totals['ps'] += $entry->payment_shop;
                                $totals['po'] += $entry->payment_other;
                                $totals['tp'] += $entry->total_payment;
                                $totals['ds'] += $entry->daily_sale;
                                $totals['hs'] += $entry->hole_sale;
                                $totals['os'] += $entry->other_sale;
                                $totals['dp'] += $entry->due_purchase;
                                $totals['dus'] += $entry->due_sale;
                                $totals['dsc'] += $entry->daily_staff_cost;
                                $totals['oc'] += $entry->other_cost;
                                $totals['s'] += $entry->salary;
                                $totals['b'] += $entry->bill;
                                $totals['r'] += $entry->rent;
                                $totals['c'] += $entry->cash;
                            }
                        @endphp
                        <tr id="row-{{ $dateStr }}" 
                            data-date="{{ $dateStr }}" 
                            data-entry-id="{{ $entry->id ?? '' }}" 
                            class="hover:bg-gray-700/30 transition-colors {{ $isToday ? 'bg-gray-800/80 border-l-4 border-l-emerald-500' : '' }}">
                            
                            <!-- Date Column (Sticky) -->
                            <td class="p-0 border-r border-gray-700 sticky left-0 z-10 {{ $isToday ? 'bg-gray-800/90 text-emerald-400 font-bold shadow-[1px_0_0_0_#374151]' : 'bg-gray-800 shadow-[1px_0_0_0_#374151]' }}">
                                <div class="px-3 py-2 whitespace-nowrap">
                                    {{ $row['date']->format('j-M-y') }}
                                </div>
                            </td>

                            <!-- Purchases -->
                            <x-ledger-cell name="medicine_purchase_company" :val="$entry->medicine_purchase_company ?? ''" />
                            <x-ledger-cell name="medicine_purchase_shop" :val="$entry->medicine_purchase_shop ?? ''" />
                            <x-ledger-cell name="medicine_purchase_other" :val="$entry->medicine_purchase_other ?? ''" borderRight="border-sky-900/50" />

                            <!-- Payments -->
                            <x-ledger-cell name="payment_company" :val="$entry->payment_company ?? ''" />
                            <x-ledger-cell name="payment_shop" :val="$entry->payment_shop ?? ''" />
                            <x-ledger-cell name="payment_other" :val="$entry->payment_other ?? ''" />
                            <td class="p-3 border-r border-orange-900/50 text-right font-semibold text-gray-200 bg-gray-900/30 total-payment">{{ $entry ? number_format($entry->total_payment, 2) : '' }}</td>

                            <!-- Sales -->
                            <x-ledger-cell name="daily_sale" :val="$entry->daily_sale ?? ''" />
                            <x-ledger-cell name="hole_sale" :val="$entry->hole_sale ?? ''" />
                            <x-ledger-cell name="other_sale" :val="$entry->other_sale ?? ''" />
                            <x-ledger-cell name="due_purchase" :val="$entry->due_purchase ?? ''" />
                            <x-ledger-cell name="due_sale" :val="$entry->due_sale ?? ''" borderRight="border-emerald-900/50" />

                            <!-- Costs -->
                            <x-ledger-cell name="daily_staff_cost" :val="$entry->daily_staff_cost ?? ''" />
                            <x-ledger-cell name="other_cost" :val="$entry->other_cost ?? ''" />
                            <x-ledger-cell name="salary" :val="$entry->salary ?? ''" />
                            <x-ledger-cell name="bill" :val="$entry->bill ?? ''" />
                            <x-ledger-cell name="rent" :val="$entry->rent ?? ''" borderRight="border-rose-900/50" />

                            <!-- Cash Flow (Computed) -->
                            <td class="p-3 border-r border-gray-700 text-right font-bold {{ ($entry?->cash ?? 0) >= 0 ? 'text-emerald-400' : 'text-red-400' }} bg-gray-900/30 cash-flow">{{ $entry ? number_format($entry->cash, 2) : '' }}</td>
                            <td class="p-3 border-r border-gray-700 text-right font-bold text-white bg-gray-900/50">{{ number_format($row['running_total'], 2) }}</td>
                            <td class="p-3 text-right text-gray-400 bg-gray-900/20">{{ number_format($row['prev_balance'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <!-- Footer Totals -->
                <tfoot class="bg-gray-900 border-t-2 border-gray-600 font-bold text-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                    <tr>
                        <td class="p-3 border-r border-gray-700 sticky left-0 z-10 bg-gray-900 shadow-[1px_0_0_0_#374151]">TOTAL=</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['mpc'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['mps'], 2) }}</td>
                        <td class="p-3 border-r border-sky-900/50 text-right">{{ number_format($totals['mpo'], 2) }}</td>
                        
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['pc'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['ps'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['po'], 2) }}</td>
                        <td class="p-3 border-r border-orange-900/50 text-right text-orange-400">{{ number_format($totals['tp'], 2) }}</td>
                        
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['ds'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['hs'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['os'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['dp'], 2) }}</td>
                        <td class="p-3 border-r border-emerald-900/50 text-right">{{ number_format($totals['dus'], 2) }}</td>
                        
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['dsc'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['oc'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['s'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700/50 text-right">{{ number_format($totals['b'], 2) }}</td>
                        <td class="p-3 border-r border-rose-900/50 text-right">{{ number_format($totals['r'], 2) }}</td>
                        
                        <td class="p-3 border-r border-gray-700 text-right text-indigo-400">{{ number_format($totals['c'], 2) }}</td>
                        <td class="p-3 border-r border-gray-700 text-right bg-emerald-900/20 text-emerald-400">{{ number_format($ledger->previous_balance + $totals['c'], 2) }}</td>
                        <td class="p-3 text-right text-gray-400"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</x-app-layout>
