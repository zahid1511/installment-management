<!-- resources/views/installments/index.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Installments</h1>

    <a href="{{ route('installments.create') }}" class="btn btn-primary mb-3">Add New Installment</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Receipt No</th>
                <th>Installment Amount</th>
                <th>Balance</th>
                <th>Recovery Officer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($installments as $installment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $installment->customer->name }}</td>
                    <td>{{ $installment->receipt_no }}</td>
                    <td>{{ $installment->installment_amount }}</td>
                    <td>{{ $installment->balance }}</td>
                    <td>{{ $installment->recovery_officer }}</td>
                    <td>
                        <a href="{{ route('installments.edit', $installment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('installments.destroy', $installment->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
