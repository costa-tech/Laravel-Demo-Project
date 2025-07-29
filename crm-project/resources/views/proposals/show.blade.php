<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proposal Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $proposal->title }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Status: 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($proposal->status == 'accepted') bg-green-100 text-green-800
                                @elseif($proposal->status == 'rejected') bg-red-100 text-red-800
                                @elseif($proposal->status == 'sent') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($proposal->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $proposal->customer->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($proposal->amount, 2) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $proposal->customer->email }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $proposal->created_at->format('M d, Y') }}</dd>
                        </div>
                    </div>

                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $proposal->description }}</dd>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('proposals.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Proposals
                        </a>
                        <div>
                            @if($proposal->status == 'accepted')
                                <a href="{{ route('invoices.create') }}?proposal_id={{ $proposal->id }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Create Invoice
                                </a>
                            @endif
                            <a href="{{ route('proposals.edit', $proposal->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('proposals.destroy', $proposal->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this proposal?')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
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
