<x-app-layout>
    <x-slot:title>Customer Details</x-slot>
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-white">{{ $customer->name }}</h2>
            <p class="text-gray-400">Total Due: $ {{ number_format($customer->total_due, 2) }}</p>
        </div>
        <div>
            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button class="px-4 py-2 bg-red-500/20 text-red-500 rounded-lg">Delete Customer</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gray-800 p-6 rounded-xl">
            <h3 class="text-xl text-white mb-4">Add Sale</h3>
            <form action="{{ route('customers.sales.store', $customer) }}" method="POST" class="space-y-4">
                @csrf
                <div><input type="date" name="date" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white" value="{{ date('Y-m-d') }}" required></div>
                <div><input type="text" name="invoice_no" placeholder="Invoice No" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white"></div>
                <div><input type="number" step="0.01" name="total_amount" placeholder="Total Amount" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white" required></div>
                <div><input type="number" step="0.01" name="paid_amount" placeholder="Collected Amount" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white" value="0" required></div>
                <div><textarea name="details" placeholder="Details" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white"></textarea></div>
                <button class="w-full py-2 bg-emerald-500 text-white rounded-lg">Save Sale</button>
            </form>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl">
            <h3 class="text-xl text-white mb-4">Receive Payment</h3>
            <form action="{{ route('customers.payments.store', $customer) }}" method="POST" class="space-y-4">
                @csrf
                <div><input type="date" name="date" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white" value="{{ date('Y-m-d') }}" required></div>
                <div>
                    <select name="sale_id" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white">
                        <option value="">-- Apply to general balance --</option>
                        @foreach($customer->sales as $txn)
                            @if($txn->due_amount > 0)
                                <option value="{{ $txn->id }}">{{ $txn->date }} - Due: ${{ $txn->due_amount }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div><input type="number" step="0.01" name="amount" placeholder="Amount Received" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white" required></div>
                <div><textarea name="details" placeholder="Details" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white"></textarea></div>
                <button class="w-full py-2 bg-blue-500 text-white rounded-lg">Apply Received</button>
            </form>
        </div>
    </div>
</x-app-layout>