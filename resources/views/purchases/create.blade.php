@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">New Purchase</h1>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="customer_id">Customer <span class="text-danger">*</span></label>
                    <select class="form-control" name="customer_id" id="customer_id" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->account_no }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="product_id">Product <span class="text-danger">*</span></label>
                    <select class="form-control" name="product_id" id="product_id" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->company }} {{ $product->model }} - Rs. {{ number_format($product->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="purchase_date">Purchase Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="purchase_date" id="purchase_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="total_price">Total Price <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="total_price" id="total_price" step="0.01" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="advance_payment">Advance Payment <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="advance_payment" id="advance_payment" step="0.01" min="0" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="installment_months">Installment Period (Months) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="installment_months" id="installment_months" min="1" required>
                </div>
            </div>
        </div>

        

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_installment_date">First Installment Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="first_installment_date" id="first_installment_date" value="" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="recovery_officer_id">Recovery Officer <span class="text-danger">*</span></label>
                    <select class="form-control" name="recovery_officer_id" id="recovery_officer_id" required>
                        <option value="">Select Recovery Officer</option>
                        @foreach($recoveryOfficers as $officer)
                            <option value="{{ $officer->id }}">{{ $officer->name }} ({{ $officer->employee_id }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="monthly_installment">Monthly Installment</label>
                    <input type="number" class="form-control" name="monthly_installment" id="monthly_installment" step="0.01" readonly>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save Purchase</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>

@push('script')
<script>
$(document).ready(function() {
    // Auto-fill price when product is selected
    $('#product_id').change(function() {
        const price = $(this).find(':selected').data('price');
        $('#total_price').val(price);
        calculateInstallment();
    });

    // Calculate installment when advance or months change
    $('#advance_payment, #installment_months').on('input', function() {
        calculateInstallment();
    });

    function calculateInstallment() {
        const totalPrice = parseFloat($('#total_price').val()) || 0;
        const advance = parseFloat($('#advance_payment').val()) || 0;
        const months = parseInt($('#installment_months').val()) || 1;

        const remaining = totalPrice - advance;
        const monthly = remaining / months;

        $('#monthly_installment').val(monthly.toFixed(2));
    }
});
</script>
@endpush
@endsection