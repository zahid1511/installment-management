@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Manual Installment</h1>
        <a href="{{ route('installments.index') }}" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($installment->purchase_id)
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i>
            <strong>Warning:</strong> This installment is linked to a purchase. 
            <a href="{{ route('purchases.show', $installment->purchase_id) }}" class="btn btn-sm btn-primary">
                Edit via Purchase Page
            </a>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Installment Details</h3>
        </div>
        <div class="panel-body">
            <form action="{{ route('installments.update', $installment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_id">Customer <span class="text-danger">*</span></label>
                            <select class="form-control" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $customer->id == $installment->customer_id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->account_no }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="recovery_officer_id">Recovery Officer <span class="text-danger">*</span></label>
                            <select class="form-control" id="recovery_officer_id" name="recovery_officer_id" required>
                                <option value="">Select Recovery Officer</option>
                                @foreach($recoveryOfficers as $officer)
                                    <option value="{{ $officer->id }}" {{ $officer->id == $installment->recovery_officer_id ? 'selected' : '' }}>
                                        {{ $officer->name }} ({{ $officer->employee_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ old('date', $installment->date ? $installment->date->format('Y-m-d') : '') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="receipt_no">Receipt No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="receipt_no" name="receipt_no" 
                                   value="{{ old('receipt_no', $installment->receipt_no) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pre_balance">Previous Balance <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="pre_balance" name="pre_balance" 
                                   value="{{ old('pre_balance', $installment->pre_balance) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="installment_amount">Installment Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="installment_amount" name="installment_amount" 
                                   value="{{ old('installment_amount', $installment->installment_amount) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="number" step="0.01" class="form-control" id="discount" name="discount" 
                                   value="{{ old('discount', $installment->discount) }}" min="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="balance">Remaining Balance <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="balance" name="balance" 
                                   value="{{ old('balance', $installment->balance) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Select Method</option>
                                <option value="cash" {{ old('payment_method', $installment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank" {{ old('payment_method', $installment->payment_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ old('payment_method', $installment->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks', $installment->remarks) }}</textarea>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update Installment
                    </button>
                    <a href="{{ route('installments.index') }}" class="btn btn-default">
                        <i class="fa fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    // Auto-calculate balance when amounts change
    $('#pre_balance, #installment_amount, #discount').on('input', function() {
        calculateBalance();
    });

    function calculateBalance() {
        var preBalance = parseFloat($('#pre_balance').val()) || 0;
        var installmentAmount = parseFloat($('#installment_amount').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        
        var totalPayment = installmentAmount - discount;
        var newBalance = Math.max(0, preBalance - totalPayment);
        
        $('#balance').val(newBalance.toFixed(2));
    }
});
</script>
@endpush
@endsection