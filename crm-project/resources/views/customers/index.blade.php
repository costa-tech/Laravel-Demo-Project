@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Customers</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->company ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No customers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $customers->links() }}
    </div>
</div>
@endsection