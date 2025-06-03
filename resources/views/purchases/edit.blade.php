@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Purchase</h1>
        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Back to Purchase
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

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Warning about paid installments -->
    @php
        $paidCount = $purchase->installments()->where('status', 'paid')->count();
    @endphp
    
    @if($paidCount > 0)
        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i>
            <strong>Cannot Edit:</strong> This purchase has {{ $paidCount }} paid installment(s). 
            Purchases with payments cannot be modified to maintain data integrity.
        </div>
        <div class="text-center">
            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-primary">
                <i class="fa fa-eye"></i> View Purchase Details
            </a>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fa fa-info-circle"></i>
            <strong>Note:</strong> Editing this purchase will regenerate all pending installments. 
            Make sure all details are correct before saving.
        </div>

        <form action="{{ route('purchases.update', $purchase) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Purchase Information</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                <select class="form-control" name="customer_id" id="customer_id" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $customer->id == $purchase->customer_id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->account_no }})
                                        </option>
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
                                        <option value="{{ $product->id }}" 
                                                data-price="{{ $product->price }}"
                                                {{ $product->id == $purchase->product_id ? 'selected' : '' }}>
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
                                <input type="date" class="form-control" name="purchase_date" id="purchase_date" 
                                       value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_price">Total Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="total_price" id="total_price" 
                                       step="0.01" value="{{ old('total_price', $purchase->total_price) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="advance_payment">Advance Payment <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="advance_payment" id="advance_payment" 
                                       step="0.01" min="0" value="{{ old('advance_payment', $purchase->advance_payment) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="installment_months">Installment Period (Months) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="installment_months" id="installment_months" 
                                       min="1" value="{{ old('installment_months', $purchase->installment_months) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_installment_date">First Installment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="first_installment_date" id="first_installment_date" 
                                       value="{{ old('first_installment_date', $purchase->first_installment_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="recovery_officer_id">Recovery Officer <span class="text-danger">*</span></label>
                                <select class="form-control" name="recovery_officer_id" id="recovery_officer_id" required>
                                    <option value="">Select Recovery Officer</option>
                                    @foreach($recoveryOfficers as $officer)
                                        @php
                                            $currentOfficerId = $purchase->installments()->first()->recovery_officer_id ?? null;
                                        @endphp
                                        <option value="{{ $officer->id }}" {{ $officer->id == $currentOfficerId ? 'selected' : '' }}>
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
                                <label for="monthly_installment">Monthly Installment (Calculated)</label>
                                <input type="number" class="form-control" name="monthly_installment" id="monthly_installment" 
                                       step="0.01" value="{{ old('monthly_installment', $purchase->monthly_installment) }}" readonly>
                                <small class="text-muted">This value is automatically calculated</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="remaining_balance">Remaining Balance (Calculated)</label>
                                <input type="number" class="form-control" id="remaining_balance" 
                                       value="{{ $purchase->total_price - $purchase->advance_payment }}" readonly>
                                <small class="text-muted">Total Price - Advance Payment</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current vs New Comparison -->
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Impact Summary</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Current Schedule:</h5>
                            <ul class="list-unstyled">
                                <li><strong>Monthly Installment:</strong> Rs. {{ number_format($purchase->monthly_installment, 2) }}</li>
                                <li><strong>Total Installments:</strong> {{ $purchase->installment_months }}</li>
                                <li><strong>First Due:</strong> {{ $purchase->first_installment_date->format('d/m/Y') }}</li>
                                <li><strong>Last Due:</strong> {{ $purchase->last_installment_date->format('d/m/Y') }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>New Schedule (Preview):</h5>
                            <ul class="list-unstyled" id="new-schedule-preview">
                                <li><strong>Monthly Installment:</strong> <span id="preview-monthly">-</span></li>
                                <li><strong>Total Installments:</strong> <span id="preview-months">-</span></li>
                                <li><strong>First Due:</strong> <span id="preview-first-date">-</span></li>
                                <li><strong>Last Due:</strong> <span id="preview-last-date">-</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this purchase? This will regenerate all pending installments.')">
                    <i class="fa fa-save"></i> Update Purchase
                </button>
                <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-default">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>
    @endif
</div>

@push('script')
<script>
$(document).ready(function() {
    // Auto-fill price when product is selected
    $('#product_id').change(function() {
        const price = $(this).find(':selected').data('price');
        if (price) {
            $('#total_price').val(price);
        }
        calculateInstallment();
        updatePreview();
    });

    // Calculate installment when values change
    $('#total_price, #advance_payment, #installment_months, #first_installment_date').on('input change', function() {
        calculateInstallment();
        updatePreview();
    });

    function calculateInstallment() {
        const totalPrice = parseFloat($('#total_price').val()) || 0;
        const advance = parseFloat($('#advance_payment').val()) || 0;
        const months = parseInt($('#installment_months').val()) || 1;

        const remaining = totalPrice - advance;
        const monthly = remaining / months;

        $('#monthly_installment').val(monthly.toFixed(2));
        $('#remaining_balance').val(remaining.toFixed(2));
    }

    function updatePreview() {
        const months = parseInt($('#installment_months').val()) || 0;
        const monthly = parseFloat($('#monthly_installment').val()) || 0;
        const firstDate = $('#first_installment_date').val();

        $('#preview-monthly').text(monthly > 0 ? 'Rs. ' + monthly.toFixed(2) : '-');
        $('#preview-months').text(months || '-');
        $('#preview-first-date').text(firstDate ? formatDate(firstDate) : '-');

        if (firstDate && months > 0) {
            const lastDate = new Date(firstDate);
            lastDate.setMonth(lastDate.getMonth() + months - 1);
            $('#preview-last-date').text(formatDate(lastDate.toISOString().split('T')[0]));
        } else {
            $('#preview-last-date').text('-');
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB'); // DD/MM/YYYY format
    }

    // Initialize calculations
    calculateInstallment();
    updatePreview();
});
</script>
@endpush
@endsection