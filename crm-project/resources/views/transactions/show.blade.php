<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Transaction {{ $transaction->transaction_id }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Status: 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($transaction->status == 'completed') bg-green-100 text-green-800
                                @elseif($transaction->status == 'failed') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('customers.show', $transaction->customer->id) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $transaction->customer->name }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $transaction->customer->email }}</dd>
                        </div>

                        @if($transaction->invoice)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Related Invoice</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('invoices.show', $transaction->invoice->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $transaction->invoice->invoice_number }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-lg font-bold text-green-600">${{ number_format($transaction->amount, 2) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">Payment</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($transaction->payment_details && isset($transaction->payment_details['method']))
                                    {{ ucfirst($transaction->payment_details['method']) }}
                                @else
                                    Stripe
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Transaction Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('M d, Y H:i') }}</dd>
                        </div>

                        @if($transaction->stripe_payment_intent_id)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Stripe Payment Intent ID</dt>
                                <dd class="mt-1 text-sm text-gray-500 font-mono">{{ $transaction->stripe_payment_intent_id }}</dd>
                            </div>
                        @endif
                    </div>

                    @if($transaction->status == 'failed')
                        <div class="mt-6 bg-red-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-red-900">Transaction Failed</h4>
                            <p class="mt-2 text-sm text-red-700">
                                This transaction could not be completed. Please contact the customer to arrange alternative payment.
                            </p>
                        </div>
                    @elseif($transaction->status == 'completed')
                        <div class="mt-6 bg-green-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-green-900">Transaction Completed</h4>
                            <p class="mt-2 text-sm text-green-700">
                                This transaction was successfully completed and the payment has been received.
                            </p>
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
