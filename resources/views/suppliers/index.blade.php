<x-app-layout>
    <x-slot:title>Supplier Management</x-slot>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Supplier</h2>
            <p class="mt-1 text-sm text-gray-400">Manage supplier contacts, balances, and transactions.</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-colors hover:bg-emerald-600">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Supplier
        </a>
    </div>

    <div class="space-y-4 md:hidden">
        @forelse($suppliers as $item)
            <div class="rounded-2xl border border-gray-700/60 bg-gray-800/80 p-5 shadow-lg shadow-black/10 backdrop-blur-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h3 class="truncate text-lg font-bold text-white">{{ $item->name }}</h3>
                        <p class="mt-1 text-sm text-gray-400">{{ $item->phone ?: 'No phone number added' }}</p>
                    </div>
                    <div class="shrink-0 rounded-xl border border-red-500/20 bg-red-500/10 px-3 py-2 text-right">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-red-300/80">Due</p>
                        <p class="text-base font-bold text-red-400">{{ $currency }} {{ number_format($item->total_due, 2) }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('suppliers.show', $item) }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-300 transition-colors hover:bg-emerald-500/20 hover:text-emerald-200">
                        View Details
                    </a>
                    <a href="{{ route('suppliers.edit', $item) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-600 bg-gray-900/70 px-4 py-3 text-sm font-semibold text-gray-200 transition-colors hover:border-gray-500 hover:bg-gray-800">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-700 bg-gray-800/50 p-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-gray-700 bg-gray-900/60">
                    <svg class="h-7 w-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <p class="font-medium text-gray-300">No suppliers found</p>
                <p class="mt-1 text-sm text-gray-500">Add your first supplier to start tracking purchases and payments.</p>
            </div>
        @endforelse
    </div>

    <div class="hidden overflow-hidden rounded-2xl border border-gray-700/60 bg-gray-800 md:block">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-300">
                <thead class="border-b border-gray-700 bg-gray-900">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Total Due</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($suppliers as $item)
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-6 py-4">{{ $item->name }}</td>
                            <td class="px-6 py-4">{{ $item->phone }}</td>
                            <td class="px-6 py-4 font-bold">{{ $currency }} {{ number_format($item->total_due, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('suppliers.show', $item) }}" class="mr-3 text-emerald-400 hover:underline">View & Transactions</a>
                                <a href="{{ route('suppliers.edit', $item) }}" class="text-emerald-400 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">No suppliers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
