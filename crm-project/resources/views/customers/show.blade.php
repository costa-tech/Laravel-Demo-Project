<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $customer->name }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Status: 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($customer->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->phone ?: 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Company</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->company ?: 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}</dd>
                        </div>
                    </div>

                    @if($customer->address)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->address }}</dd>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('customers.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Customers
                        </a>
                        <div>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this customer?')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Related Data -->
                    <div class="mt-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900">Proposals</h4>
                                <p class="text-3xl font-bold text-blue-600">{{ $customer->proposals()->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900">Invoices</h4>
                                <p class="text-3xl font-bold text-green-600">{{ $customer->invoices()->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900">Transactions</h4>
                                <p class="text-3xl font-bold text-purple-600">{{ $customer->transactions()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
