@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Purchases</h1>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">New Purchase</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Purchase Date</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Total Price</th>
                    <th>Advance</th>
                    <th>Balance</th>
                    <th>Monthly</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                    <td>{{ $purchase->customer->name }}</td>
                    <td>{{ $purchase->product->company }} {{ $purchase->product->model }}</td>
                    <td>{{ number_format($purchase->total_price, 2) }}</td>
                    <td>{{ number_format($purchase->advance_payment, 2) }}</td>
                    <td>{{ number_format($purchase->remaining_balance, 2) }}</td>
                    <td>{{ number_format($purchase->monthly_installment, 2) }}</td>
                    <td>
                        <span class="label label-{{ $purchase->status == 'completed' ? 'primary' : 'warning' }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection