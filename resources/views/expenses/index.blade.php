<x-app-layout>
    <x-slot:title>Expenses Management</x-slot>
    
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-6 bg-gray-800/40 p-6 rounded-2xl border border-gray-700/50 backdrop-blur-xl">
        <div>
            <h2 class="text-3xl font-extrabold text-white mb-2">Expenses Management</h2>
            <p class="text-sm text-gray-400">Track and manage all pharmacy expenses and operational costs.</p>
        </div>
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full md:w-auto">
            <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-all shadow-lg shadow-emerald-500/25 text-sm font-semibold flex items-center justify-center hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Expense
            </button>
        </div>
    </div>

    <!-- Expense Modal -->
    <div id="expenseModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/80 backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('expenseModal').classList.add('hidden')"></div>
            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-gray-800 border border-gray-700/50 shadow-2xl rounded-3xl sm:my-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <span class="p-2.5 bg-emerald-500/10 rounded-xl mr-3">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                        Add New Expense
                    </h3>
                    <button type="button" onclick="document.getElementById('expenseModal').classList.add('hidden')" class="text-gray-400 hover:text-white transition-colors p-2 hover:bg-gray-700/50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                        <input type="text" name="title" placeholder="e.g. Electricity Bill, Staff Lunch" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner px-4 py-2.5" required>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Amount</label>
                            <input type="number" step="0.01" min="0.01" name="amount" placeholder="0.00" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner px-4 py-2.5" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Cost Type</label>
                            <select name="cost_type" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner text-sm px-4 py-3" required>
                                @foreach($costTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date & Time</label>
                        <input type="datetime-local" name="datetime" class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner px-4 py-2.5" value="{{ date('Y-m-d\TH:i') }}" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                        <textarea name="note" rows="3" placeholder="Additional details..." class="w-full bg-gray-900/80 border border-gray-700 rounded-xl text-white focus:ring-emerald-500 focus:border-emerald-500 transition-colors shadow-inner resize-none text-sm p-4"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/25 transition-colors flex items-center justify-center text-lg">
                            Save Expense
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-gray-800/60 backdrop-blur-xl rounded-2xl overflow-hidden border border-gray-700/50 shadow-lg flex flex-col">
        <div class="px-6 py-5 border-b border-gray-700/50 bg-gray-800/80 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white flex items-center">
                <span class="p-2 bg-emerald-500/10 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </span>
                Expense History
            </h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-gray-900/40 text-gray-400 uppercase text-xs border-b border-gray-700/50 tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Title & Note</th>
                        <th class="px-6 py-4">Cost Type</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-gray-700/30 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-200 font-medium mb-1">{{ $expense->datetime->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500 bg-gray-900/50 inline-block px-2 py-0.5 rounded border border-gray-700/50">{{ $expense->datetime->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-white font-semibold">{{ $expense->title }}</div>
                            @if($expense->note)
                                <p class="text-xs text-gray-400 mt-1 border-l-2 border-gray-600 pl-2 italic">{{ $expense->note }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-[10px] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider 
                                {{ $expense->cost_type === 'Daily Staff Cost' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/20' : 
                                  ($expense->cost_type === 'Transportation' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 
                                  ($expense->cost_type === 'Salary' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 
                                  ($expense->cost_type === 'Bill' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 
                                  ($expense->cost_type === 'Rent' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 
                                  'bg-gray-500/10 text-gray-400 border border-gray-500/20')))) }}">
                                {{ $expense->cost_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap font-bold text-red-400 text-base">
                            {{ $currency }}{{ number_format($expense->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap align-middle">
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this expense record?');" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold bg-red-500/10 text-red-400 border border-red-500/20 px-3 py-2 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                            <div class="bg-gray-800/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-700/50">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="font-medium text-gray-400">No expenses recorded yet</p>
                            <p class="text-xs mt-1">Records will appear here once added.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-900/30">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
