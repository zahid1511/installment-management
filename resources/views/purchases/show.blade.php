@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Purchase Details</h1>
        <a href="{{ route('purchases.index') }}" class="btn btn-default">Back to List</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Purchase Information</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>Purchase Date:</th>
                            <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td>{{ $purchase->customer->name }} ({{ $purchase->customer->account_no }})</td>
                        </tr>
                        <tr>
                            <th>Product:</th>
                            <td>{{ $purchase->product->company }} {{ $purchase->product->model }}</td>
                        </tr>
                        <tr>
                            <th>Serial No:</th>
                            <td>{{ $purchase->product->serial_no }}</td>
                        </tr>
                        <tr>
                            <th>Total Price:</th>
                            <td>Rs. {{ number_format($purchase->total_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Advance Payment:</th>
                            <td>Rs. {{ number_format($purchase->advance_payment, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining Balance:</th>
                            <td>Rs. {{ number_format($purchase->remaining_balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Monthly Installment:</th>
                            <td>Rs. {{ number_format($purchase->monthly_installment, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="label label-{{ $purchase->status == 'completed' ? 'primary' : 'warning' }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Payment Summary</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>Total Paid:</th>
                            <td>Rs. {{ number_format($purchase->advance_payment + $purchase->installments()->where('status', 'paid')->sum('installment_amount'), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining Balance:</th>
                            <td>Rs. {{ number_format($purchase->getRemainingBalance(), 2) }}</td>
                        </tr>
                        <tr>
                            <th>Defaulted Payments:</th>
                            <td>{{ $purchase->installments()->where('due_date', '<', now())->where('status', '!=', 'paid')->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Payment Schedule</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Paid Date</th>
                            <th>Receipt No</th>
                            <th>Fine</th>
                            <th>Recovery Officer</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->installments as $installment)
                        @php
                            $isOverdue = ($installment->status != 'paid' && $installment->due_date < now());
                        @endphp
                        <tr class="{{ $isOverdue ? 'warning' : '' }}">
                            <td>{{ $installment->due_date->format('d/m/Y') }}</td>
                            <td>Rs. {{ number_format($installment->installment_amount, 2) }}</td>
                            <td>
                                <span class="label label-{{ $installment->status == 'paid' ? 'success' : ($isOverdue ? 'danger' : 'warning') }}">
                                    {{ ucfirst($installment->status) }}
                                </span>
                            </td>
                            <td>{{ $installment->date ? $installment->date->format('d/m/Y') : '-' }}</td>
                            <td>{{ $installment->receipt_no ?? '-' }}</td>
                            <td>Rs. {{ number_format($installment->fine_amount, 2) }}</td>
                            <td>{{ $installment->officer?->name ?? $installment->recovery_officer ?? '-' }}</td>
                            <td>
                                @if($installment->status == 'pending')
                                    <button class="btn btn-sm btn-primary process-payment-btn" 
                                        data-installment-id="{{ $installment->id }}">
                                        Process Payment
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('purchases.process-payment', $purchase) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Process Payment</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="installment_id" id="installment_id">

                    <div class="form-group">
                        <label>Payment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Receipt No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="receipt_no" id="receipt_no" readonly required style="background-color: #f5f5f5;">
                        <small class="text-muted">Auto-generated receipt number</small>
                    </div>

                    <div class="form-group">
                        <label>Payment Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="payment_amount" id="payment_amount" step="0.01" required>
                        <small class="text-muted">Pre-filled with scheduled installment amount</small>
                    </div>

                    <div class="form-group">
                        <label>Discount</label>
                        <input type="number" class="form-control" name="discount" value="0" step="0.01" min="0">
                        <small class="text-muted">Enter discount amount if applicable</small>
                    </div>

                    <div class="form-group">
                        <label>Payment Method <span class="text-danger">*</span></label>
                        <select class="form-control" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Recovery Officer <span class="text-danger">*</span></label>
                        <select class="form-control" name="recovery_officer_id" id="recovery_officer_id" required>
                            <option value="">Loading...</option>
                        </select>
                        <small class="text-muted">Pre-selected officer assigned to this installment</small>
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                        <small class="text-muted">Auto-generated default remarks</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Process Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    // Handle process payment button click
    $('.process-payment-btn').on('click', function() {
        var installmentId = $(this).data('installment-id');
        
        // Show loading state
        $('#paymentModal').modal('show');
        $('#receipt_no').val('Loading...');
        $('#payment_amount').val('Loading...');
        $('#recovery_officer_id').html('<option value="">Loading...</option>');
        $('#remarks').val('Loading...');
        
        // Fetch installment details
        $.ajax({
            url: '{{ url("admin/purchases/installment") }}/' + installmentId + '/details',
            type: 'GET',
            success: function(response) {
                // Populate modal with fetched data
                $('#installment_id').val(installmentId);
                $('#receipt_no').val(response.receipt_no);
                $('#payment_amount').val(response.installment_amount);
                $('#remarks').val(response.remarks);
                
                // Populate recovery officers dropdown
                populateRecoveryOfficers(response.recovery_officer_id);
            },
            error: function(xhr) {
                alert('Error loading installment details. Please try again.');
                $('#paymentModal').modal('hide');
            }
        });
    });
    
    function populateRecoveryOfficers(selectedOfficerId) {
        $.ajax({
            url: '{{ route("recovery-officers.index") }}', // We'll need to create an API endpoint
            type: 'GET',
            success: function(officers) {
                var options = '<option value="">Select Recovery Officer</option>';
                
                // If we have officers data, populate the dropdown
                // For now, let's use the selected officer and populate from the existing data
                @php
                $activeOfficers = \App\Models\RecoveryOfficer::active()->get();
                @endphp
                
                var officersData = @json($activeOfficers);
                
                $.each(officersData, function(index, officer) {
                    var selected = (officer.id == selectedOfficerId) ? 'selected' : '';
                    options += '<option value="' + officer.id + '" ' + selected + '>' + 
                              officer.name + ' (' + officer.employee_id + ')</option>';
                });
                
                $('#recovery_officer_id').html(options);
            },
            error: function() {
                // Fallback: populate with existing data
                var options = '<option value="">Select Recovery Officer</option>';
                @foreach(\App\Models\RecoveryOfficer::active()->get() as $officer)
                var selected = ({{ $officer->id }} == selectedOfficerId) ? 'selected' : '';
                options += '<option value="{{ $officer->id }}" ' + selected + '>{{ $officer->name }} ({{ $officer->employee_id }})</option>';
                @endforeach
                
                $('#recovery_officer_id').html(options);
            }
        });
    }
});
</script>
@endpush
@endsection