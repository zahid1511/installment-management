@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Purchase Details</h1>
        <div class="btn-group">
            @php
                $paidInstallments = $purchase->installments()->where('status', 'paid')->count();
            @endphp
            
            <!-- Edit Button -->
            <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning">
                <i class="fa fa-edit"></i> Edit Purchase
            </a>
      
            
            <!-- Delete Button -->
            <button onclick="confirmDelete()" class="btn btn-danger">
                <i class="fa fa-trash"></i> Delete Purchase
            </button>
            
            <a href="{{ route('purchases.index') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Purchase Information</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">Purchase Date:</th>
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
                            <td><strong>Rs. {{ number_format($purchase->total_price, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Advance Payment:</th>
                            <td>Rs. {{ number_format($purchase->advance_payment, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Remaining Balance:</th>
                            <td><strong>Rs. {{ number_format($purchase->remaining_balance, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Monthly Installment:</th>
                            <td>Rs. {{ number_format($purchase->monthly_installment, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Installment Period:</th>
                            <td>{{ $purchase->installment_months }} months</td>
                        </tr>
                        <tr>
                            <th>First Installment:</th>
                            <td>{{ $purchase->first_installment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Last Installment:</th>
                            <td>{{ $purchase->last_installment_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="label label-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
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
                    @php
                        $totalPaid = $purchase->advance_payment + $purchase->installments()->where('status', 'paid')->sum('installment_amount');
                        $remainingBalance = $purchase->getRemainingBalance();
                        $overdueInstallments = $purchase->installments()->where('due_date', '<', now())->where('status', '!=', 'paid')->count();
                        $totalInstallments = $purchase->installments()->count();
                        $paidInstallmentCount = $purchase->installments()->where('status', 'paid')->count();
                    @endphp
                    
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">Total Paid:</th>
                            <td><strong class="text-success">Rs. {{ number_format($totalPaid, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Remaining Balance:</th>
                            <td>
                                <strong class="{{ $remainingBalance > 0 ? 'text-warning' : 'text-success' }}">
                                    Rs. {{ number_format($remainingBalance, 2) }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Progress:</th>
                            <td>
                                <div class="progress" style="margin-bottom: 5px;">
                                    @php $percentage = $totalPaid > 0 ? ($totalPaid / $purchase->total_price) * 100 : 0; @endphp
                                    <div class="progress-bar progress-bar-{{ $percentage == 100 ? 'success' : 'info' }}" 
                                         style="width: {{ $percentage }}%">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </div>
                                <small class="text-muted">{{ $paidInstallmentCount }}/{{ $totalInstallments }} installments paid</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Overdue Payments:</th>
                            <td>
                                @if($overdueInstallments > 0)
                                    <span class="text-danger">
                                        <strong>{{ $overdueInstallments }}</strong> overdue
                                    </span>
                                @else
                                    <span class="text-success">No overdue payments</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Next Due Date:</th>
                            <td>
                                @php
                                    $nextInstallment = $purchase->installments()
                                        ->where('status', 'pending')
                                        ->orderBy('due_date')
                                        ->first();
                                @endphp
                                @if($nextInstallment)
                                    {{ $nextInstallment->due_date->format('d/m/Y') }}
                                    @if($nextInstallment->due_date < now())
                                        <span class="text-danger">(Overdue)</span>
                                    @endif
                                @else
                                    <span class="text-success">All paid</span>
                                @endif
                            </td>
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
                            <th>Installment #</th>
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
                            $installmentNumber = $loop->iteration;
                        @endphp
                        <tr class="{{ $isOverdue ? 'danger' : '' }}" id="installment-{{ $installment->id }}">
                            <td><strong>#{{ $installmentNumber }}</strong></td>
                            <td>
                                {{ $installment->due_date->format('d/m/Y') }}
                                @if($isOverdue)
                                    <br><small class="text-danger">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        {{ $installment->due_date->diffForHumans() }}
                                    </small>
                                @endif
                            </td>
                            <td>Rs. {{ number_format($installment->installment_amount, 2) }}</td>
                            <td>
                                <span class="label label-{{ $installment->status == 'paid' ? 'success' : ($isOverdue ? 'danger' : 'warning') }}">
                                    {{ ucfirst($installment->status) }}
                                    @if($isOverdue) (Overdue) @endif
                                </span>
                            </td>
                            <td>{{ $installment->date ? $installment->date->format('d/m/Y') : '-' }}</td>
                            <td>{{ $installment->receipt_no ?? '-' }}</td>
                            <td>
                                @if($installment->fine_amount > 0)
                                    <span class="text-danger">Rs. {{ number_format($installment->fine_amount, 2) }}</span>
                                @else
                                    Rs. 0.00
                                @endif
                            </td>
                            <td>{{ $installment->officer?->name ?? $installment->recovery_officer ?? '-' }}</td>
                            <td>
                                @if($installment->status == 'pending')
                                    <button class="btn btn-sm btn-success process-payment-btn" 
                                        data-installment-id="{{ $installment->id }}">
                                        <i class="fa fa-credit-card"></i> Pay
                                    </button>
                                @else
                                    <span class="text-muted">Paid</span>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirm Delete Purchase</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> This action will permanently delete this purchase and all its installment records.
                </div>
                <p>Are you sure you want to delete this purchase?</p>
                <table class="table table-sm">
                    <tr><th>Customer:</th><td>{{ $purchase->customer->name }}</td></tr>
                    <tr><th>Product:</th><td>{{ $purchase->product->company }} {{ $purchase->product->model }}</td></tr>
                    <tr><th>Total Amount:</th><td>Rs. {{ number_format($purchase->total_price, 2) }}</td></tr>
                    <tr><th>Paid Installments:</th><td>{{ $paidInstallments }}</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fa fa-trash"></i> Delete Purchase
                </button>
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
        var options = '<option value="">Select Recovery Officer</option>';
        
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
    }
});

function confirmDelete() {
        $('#deleteModal').modal('show');
}

$('#confirmDeleteBtn').on('click', function() {
    // Show loading state
    $(this).html('<i class="fa fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
    
    $.ajax({
        url: '{{ route("purchases.destroy", $purchase) }}',
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                window.location.href = '{{ route("purchases.index") }}';
            } else {
                alert('Error: ' + response.message);
                $('#confirmDeleteBtn').html('<i class="fa fa-trash"></i> Delete Purchase').prop('disabled', false);
            }
        },
        error: function(xhr) {
            let message = 'An error occurred while deleting the purchase.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert('Error: ' + message);
            $('#confirmDeleteBtn').html('<i class="fa fa-trash"></i> Delete Purchase').prop('disabled', false);
        }
    });
});
</script>
@endpush
@endsection