@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Customer List</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($customers->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Mobile 1</th>
                    <th>NIC</th>
                    <th>Gender</th>
                    <th>Total Price</th>
                    <th>Installment</th>
                    <th>Advance</th>
                    <th>Balance</th>
                    <th>Defaulter</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->account_no }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->mobile_1 }}</td>
                    <td>{{ $customer->nic }}</td>
                    <td>{{ ucfirst($customer->gender) }}</td>
                    <td>{{ $customer->total_price }}</td>
                    <td>{{ $customer->installment_amount }}</td>
                    <td>{{ $customer->advance }}</td>
                    <td>{{ $customer->balance }}</td>
                    <td>{{ $customer->is_defaulter ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-info">Edit</a>

                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No customers found.</p>
    @endif
</div>
@endsection
