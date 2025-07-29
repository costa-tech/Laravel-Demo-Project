<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('invoices.update', $invoice->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer -->
                            <div>
                                <x-input-label for="customer_id" :value="__('Customer')" />
                                <select id="customer_id" name="customer_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select a customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                            </div>

                            <!-- Proposal -->
                            <div>
                                <x-input-label for="proposal_id" :value="__('Proposal (Optional)')" />
                                <select id="proposal_id" name="proposal_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select a proposal</option>
                                    @foreach($proposals as $proposal)
                                        <option value="{{ $proposal->id }}" {{ old('proposal_id', $invoice->proposal_id) == $proposal->id ? 'selected' : '' }}>
                                            {{ $proposal->title }} - ${{ number_format($proposal->amount, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('proposal_id')" class="mt-2" />
                            </div>

                            <!-- Amount -->
                            <div>
                                <x-input-label for="amount" :value="__('Amount')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $invoice->amount)" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            <!-- Tax Amount -->
                            <div>
                                <x-input-label for="tax_amount" :value="__('Tax Amount')" />
                                <x-text-input id="tax_amount" class="block mt-1 w-full" type="number" step="0.01" name="tax_amount" :value="old('tax_amount', $invoice->tax_amount)" required />
                                <x-input-error :messages="$errors->get('tax_amount')" class="mt-2" />
                            </div>

                            <!-- Due Date -->
                            <div>
                                <x-input-label for="due_date" :value="__('Due Date')" />
                                <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', $invoice->due_date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description', $invoice->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Update Invoice') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-calculate total when amount or tax changes
        document.getElementById('amount').addEventListener('input', calculateTotal);
        document.getElementById('tax_amount').addEventListener('input', calculateTotal);

        function calculateTotal() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
            const total = amount + tax;
            
            // You could add a total display field here if needed
            console.log('Total: $' + total.toFixed(2));
        }
    </script>
</x-app-layout>
