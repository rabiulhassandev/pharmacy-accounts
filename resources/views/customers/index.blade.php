<x-app-layout>
    <x-slot:title>Customer Management</x-slot>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Customer</h2>
            <p class="mt-1 text-sm text-gray-400">Manage customer contacts, balances, and sales history.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-colors hover:bg-emerald-600">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Customer
        </a>
    </div>

    <div class="space-y-4 md:hidden">
        @forelse($customers as $item)
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
                    <a href="{{ route('customers.show', $item) }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-300 transition-colors hover:bg-emerald-500/20 hover:text-emerald-200">
                        View Details
                    </a>
                    <a href="{{ route('customers.edit', $item) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-600 bg-gray-900/70 px-4 py-3 text-sm font-semibold text-gray-200 transition-colors hover:border-gray-500 hover:bg-gray-800">
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-700 bg-gray-800/50 p-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-gray-700 bg-gray-900/60">
                    <svg class="h-7 w-7 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <p class="font-medium text-gray-300">No customers found</p>
                <p class="mt-1 text-sm text-gray-500">Add your first customer to start tracking sales and payments.</p>
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
                    @forelse($customers as $item)
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-6 py-4">{{ $item->name }}</td>
                            <td class="px-6 py-4">{{ $item->phone }}</td>
                            <td class="px-6 py-4 font-bold">{{ $currency }} {{ number_format($item->total_due, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('customers.show', $item) }}" class="mr-3 text-emerald-400 hover:underline">View & Transactions</a>
                                <a href="{{ route('customers.edit', $item) }}" class="text-emerald-400 hover:underline">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
