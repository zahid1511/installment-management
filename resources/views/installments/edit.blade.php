<!-- resources/views/installments/edit.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Edit Installment</h1>

    <form action="{{ route('installments.update', $installment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select class="form-control" id="customer_id" name="customer_id" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $customer->id == $installment->customer_id ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Installment Date</label>
            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $installment->date) }}" required>
        </div>

        <div class="form-group">
            <label for="receipt_no">Receipt No</label>
            <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="{{ old('receipt_no', $installment->receipt_no) }}" required>
        </div>

        <div class="form-group">
            <label for="installment_amount">Installment Amount</label>
            <input type="number" step="0.01" class="form-control" id="installment_amount" name="installment_amount" value="{{ old('installment_amount', $installment->installment_amount) }}" required>
        </div>

        <div class="form-group">
            <label for="balance">Balance</label>
            <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="{{ old('balance', $installment->balance) }}" required>
        </div>

        <div class="form-group">
            <label for="recovery_officer">Recovery Officer</label>
            <input type="text" class="form-control" id="recovery_officer" name="recovery_officer" value="{{ old('recovery_officer', $installment->recovery_officer) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
