<x-app-layout>
    <x-slot:title>Add Customer</x-slot>
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-white mb-6">Add Customer</h2>
        <form action="{{ route('customers.store') }}" method="POST" class="bg-gray-800 p-6 rounded-xl space-y-4">
            @csrf
            <div><label class="block text-gray-400 mb-1">Name</label><input type="text" name="name" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2 text-white" required></div>
            <div><label class="block text-gray-400 mb-1">Phone</label><input type="text" name="phone" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2 text-white"></div>
            <div><label class="block text-gray-400 mb-1">Email</label><input type="email" name="email" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2 text-white"></div>
            <div><label class="block text-gray-400 mb-1">Address</label><textarea name="address" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-2 text-white"></textarea></div>
            <button type="submit" class="px-6 py-2 bg-emerald-500 text-white rounded-lg">Save</button>
        </form>
    </div>
</x-app-layout>