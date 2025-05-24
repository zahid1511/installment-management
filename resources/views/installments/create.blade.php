<!-- resources/views/installments/create.blade.php -->
@extends('layouts.master')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container-fluid">
    <h1 class="mb-4">Add New Installment</h1>

    <form action="{{ route('installments.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select class="form-control" id="customer_id" name="customer_id" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
             {{-- @php
                 dd($customers);
             @endphp --}}
        <div class="form-group">
            <label for="date">Installment Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="form-group">
            <label for="receipt_no">Receipt No</label>
            <input type="text" class="form-control" id="receipt_no" name="receipt_no" required>
        </div>

        <div class="form-group">
            <label for="installment_amount">Installment Amount</label>
            <input type="number" step="0.01" class="form-control" id="installment_amount" name="installment_amount" required>
        </div>

        <div class="form-group">
            <label for="pre_balance">Previous Balance</label>
            <input type="number" step="0.01" class="form-control" id="pre_balance" name="pre_balance" required>
        </div>

        <div class="form-group">
            <label for="balance">Balance</label>
            <input type="number" step="0.01" class="form-control" id="balance" name="balance"required>
        </div>

        <div class="form-group">
            <label for="recovery_officer_id">Recovery Officer</label>
            <select class="form-control" id="recovery_officer_id" name="recovery_officer_id" required>
                <option value="">Select Recovery Officer</option>
                @foreach($recoveryOfficers as $recoveryOfficer)
                    <option value="{{ $recoveryOfficer->id }}">{{ $recoveryOfficer->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        $('#customer_id').on('change', function () {
            var customerId = $(this).val();
            if (customerId) {
                $.ajax({
                    url: '/admin/customer/' + customerId + '/installment-info',
                    type: 'GET',
                    success: function (data) {
                        $('#installment_amount').val(data.installment_amount);
                        $('#pre_balance').val(data.pre_balance);
                        $('#balance').val(data.balance);
                        $('#receipt_no').val(data.receipt_no);
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            } else {
                $('#installment_amount').val('');
                $('#pre_balance').val('');
                $('#balance').val('');
            }
        });
    });
</script>

