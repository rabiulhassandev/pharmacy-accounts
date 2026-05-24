<x-app-layout>
    <x-slot:title>Customer Management</x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Customer</h2>
        <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg">Add Customer</a>
    </div>
    <div class="bg-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-left text-gray-300">
            <thead class="bg-gray-900 border-b border-gray-700">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Total Due</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($customers as $item)
                <tr class="hover:bg-gray-700/50">
                    <td class="px-6 py-4">{{ $item->name }}</td>
                    <td class="px-6 py-4">{{ $item->phone }}</td>
                    <td class="px-6 py-4 font-bold">{{ $currency }} {{ number_format($item->total_due, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('customers.show', $item) }}" class="text-emerald-400 hover:underline mr-3">View & Transactions</a>
                        <a href="{{ route('customers.edit', $item) }}" class="text-emerald-400 hover:underline mr-3">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>