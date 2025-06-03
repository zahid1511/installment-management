@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Purchases</h1>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> New Purchase
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-body text-center">
                    <h4>{{ $purchases->count() }}</h4>
                    <p>Total Purchases</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-success">
                <div class="panel-body text-center">
                    <h4>{{ $purchases->where('status', 'completed')->count() }}</h4>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-warning">
                <div class="panel-body text-center">
                    <h4>{{ $purchases->where('status', 'active')->count() }}</h4>
                    <p>Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-info">
                <div class="panel-body text-center">
                    <h4>Rs. {{ number_format($purchases->sum('total_price'), 0) }}</h4>
                    <p>Total Value</p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">All Purchases</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="purchasesTable">
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
                        @php
                            $paidInstallments = $purchase->installments()->where('status', 'paid')->count();
                            $canEdit = $paidInstallments == 0;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $purchase->customer->name }}</strong><br>
                                <small class="text-muted">{{ $purchase->customer->account_no }}</small>
                            </td>
                            <td>
                                <strong>{{ $purchase->product->company }} {{ $purchase->product->model }}</strong><br>
                                <small class="text-muted">{{ $purchase->product->serial_no }}</small>
                            </td>
                            <td>Rs. {{ number_format($purchase->total_price, 2) }}</td>
                            <td>Rs. {{ number_format($purchase->advance_payment, 2) }}</td>
                            <td>Rs. {{ number_format($purchase->getRemainingBalance(), 2) }}</td>
                            <td>Rs. {{ number_format($purchase->monthly_installment, 2) }}</td>
                            <td>
                                <span class="label label-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                                @if($paidInstallments > 0)
                                    <br><small class="text-info">{{ $paidInstallments }} paid</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- View Button -->
                                    <a href="{{ route('purchases.show', $purchase) }}" 
                                       class="btn btn-sm btn-info" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    @if($canEdit)
                                        <a href="{{ route('purchases.edit', $purchase) }}" 
                                           class="btn btn-sm btn-warning" title="Edit Purchase">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-warning" disabled 
                                                title="Cannot edit - has payments">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    @endif
                                    
                                    <!-- Delete Button -->
                                    @if($canEdit)
                                        <button onclick="confirmDelete({{ $purchase->id }}, '{{ addslashes($purchase->customer->name) }}', '{{ addslashes($purchase->product->company . ' ' . $purchase->product->model) }}', {{ $paidInstallments }})" 
                                                class="btn btn-sm btn-danger" title="Delete Purchase">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-danger" disabled 
                                                title="Cannot delete - has payments">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
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
                <h4 class="modal-title">Confirm Delete</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i>
                    <strong>Warning!</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete this purchase?</p>
                <div id="purchase-details"></div>
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

@push('script')
<script>
$(document).ready(function() {
    $('#purchasesTable').DataTable({
        responsive: true,
        order: [[1, 'desc']], // Sort by purchase date descending
        columnDefs: [
            { targets: [9], orderable: false }, // Disable sorting for Actions column
        ]
    });
});

function confirmDelete(purchaseId, customerName, productName, paidInstallments) {
    if (paidInstallments > 0) {
        alert('Cannot delete purchase with paid installments.');
        return;
    }
    
    $('#purchase-details').html(`
        <table class="table table-sm">
            <tr><th>Customer:</th><td>${customerName}</td></tr>
            <tr><th>Product:</th><td>${productName}</td></tr>
            <tr><th>Paid Installments:</th><td>${paidInstallments}</td></tr>
        </table>
    `);
    
    $('#confirmDeleteBtn').off('click').on('click', function() {
        deletePurchase(purchaseId);
    });
    
    $('#deleteModal').modal('show');
}

function deletePurchase(purchaseId) {
    // Show loading state
    $('#confirmDeleteBtn').html('<i class="fa fa-spinner fa-spin"></i> Deleting...').prop('disabled', true);
    
    $.ajax({
        url: '/admin/purchases/' + purchaseId,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#deleteModal').modal('hide');
                
                // Show success message
                $('<div class="alert alert-success alert-dismissible">' +
                  '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                  response.message + '</div>').prependTo('.container-fluid');
                
                // Reload page to refresh data
                setTimeout(function() {
                    location.reload();
                }, 1500);
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
}
</script>
@endpush
@endsection