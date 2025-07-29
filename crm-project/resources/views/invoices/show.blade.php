<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Invoice {{ $invoice->invoice_number }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Status: 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($invoice->status == 'paid') bg-green-100 text-green-800
                                @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                                @elseif($invoice->status == 'sent') bg-blue-100 text-blue-800
                                @elseif($invoice->status == 'cancelled') bg-gray-100 text-gray-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->customer->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->customer->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($invoice->amount, 2) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tax Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($invoice->tax_amount, 2) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-lg font-bold text-green-600">${{ number_format($invoice->total_amount, 2) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</dd>
                        </div>

                        @if($invoice->proposal)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Related Proposal</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ route('proposals.show', $invoice->proposal->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $invoice->proposal->title }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->created_at->format('M d, Y') }}</dd>
                        </div>
                    </div>

                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $invoice->description }}</dd>
                    </div>

                    @if($invoice->status == 'paid' && $invoice->transaction)
                        <div class="mt-6 bg-green-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-green-900">Payment Information</h4>
                            <div class="mt-2">
                                <p class="text-sm text-green-700">
                                    Paid on: {{ $invoice->transaction->created_at->format('M d, Y H:i') }}
                                </p>
                                <p class="text-sm text-green-700">
                                    Transaction ID: {{ $invoice->transaction->transaction_id }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Invoices
                        </a>
                        <div>
                            @if($invoice->status != 'paid')
                                <form method="POST" action="{{ route('invoices.send', $invoice->id) }}" class="inline mr-2">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Send Invoice
                                    </button>
                                </form>
                                
                                <a href="{{ route('payment.show', $invoice->id) }}" target="_blank" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Payment Link
                                </a>
                            @endif
                            
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this invoice?')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
