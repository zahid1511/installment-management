@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Installments Management</h1>
        <div class="btn-group">
            <a href="{{ route('purchases.index') }}" class="btn btn-primary">
                <i class="fa fa-shopping-cart"></i> View Purchases
            </a>
        </div>
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
                    <h4>{{ $installments->where('status', 'paid')->count() }}</h4>
                    <p>Paid Installments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-warning">
                <div class="panel-body text-center">
                    <h4>{{ $installments->where('status', 'pending')->count() }}</h4>
                    <p>Pending Installments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-danger">
                <div class="panel-body text-center">
                    <h4>{{ $installments->where('status', 'pending')->where('due_date', '<', now())->count() }}</h4>
                    <p>Overdue Installments</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-success">
                <div class="panel-body text-center">
                    <h4>Rs. {{ number_format($installments->where('status', 'paid')->sum('installment_amount'), 2) }}</h4>
                    <p>Total Collected</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="panel panel-default">
        <div class="panel-body">
            <form method="GET" action="{{ route('installments.index') }}" class="form-inline">
                <div class="form-group mr-3">
                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control ml-2">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>
                <div class="form-group mr-3">
                    <label for="customer">Customer:</label>
                    <select name="customer_id" id="customer" class="form-control ml-2">
                        <option value="">All Customers</option>
                        @foreach(\App\Models\Customer::orderBy('name')->get() as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('installments.index') }}" class="btn btn-default ml-2">Clear</a>
            </form>
        </div>
    </div>

    <!-- Installments Table -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">All Installments</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="installmentsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Purchase Info</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                            <th>Receipt No</th>
                            <th>Recovery Officer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($installments as $installment)
                        @php
                            $isOverdue = ($installment->status == 'pending' && $installment->due_date < now());
                            $statusClass = $installment->status == 'paid' ? 'success' : ($isOverdue ? 'danger' : 'warning');
                        @endphp
                        <tr class="{{ $isOverdue ? 'danger' : '' }}">
                            <td>{{ $loop->iteration + ($installments->currentPage() - 1) * $installments->perPage() }}</td>
                            <td>
                                <strong>{{ $installment->customer->name }}</strong><br>
                                <small class="text-muted">{{ $installment->customer->account_no }}</small>
                            </td>
                            <td>
                                @if($installment->purchase)
                                    <strong>{{ $installment->purchase->product->company }} {{ $installment->purchase->product->model }}</strong><br>
                                    <small class="text-muted">Purchase Date: {{ $installment->purchase->purchase_date->format('d/m/Y') }}</small>
                                @else
                                    <span class="text-muted">Manual Entry</span>
                                @endif
                            </td>
                            <td>
                                {{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '-' }}
                                @if($isOverdue)
                                    <br><small class="text-danger">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        {{ $installment->due_date->diffForHumans() }}
                                    </small>
                                @endif
                            </td>
                            <td>Rs. {{ number_format($installment->installment_amount, 2) }}</td>
                            <td>
                                <span class="label label-{{ $statusClass }}">
                                    {{ ucfirst($installment->status) }}
                                    @if($isOverdue) (Overdue) @endif
                                </span>
                            </td>
                            <td>{{ $installment->date ? $installment->date->format('d/m/Y') : '-' }}</td>
                            <td>{{ $installment->receipt_no ?? '-' }}</td>
                            <td>{{ $installment->officer?->name ?? $installment->recovery_officer ?? '-' }}</td>
                            <td>
                                @if($installment->purchase_id)
                                    <!-- For purchase-based installments, redirect to purchase page -->
                                    <a href="{{ route('purchases.show', $installment->purchase_id) }}" 
                                       class="btn btn-sm btn-info" title="View Purchase Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($installment->status == 'pending')
                                        <a href="{{ route('purchases.show', $installment->purchase_id) }}#installment-{{ $installment->id }}" 
                                           class="btn btn-sm btn-success" title="Process Payment">
                                            <i class="fa fa-credit-card"></i>
                                        </a>
                                    @endif
                                @else
                                    <!-- For manual installments, allow edit/delete -->
                                    <a href="{{ route('installments.edit', $installment->id) }}" 
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('installments.destroy', $installment->id) }}" 
                                          method="POST" style="display: inline-block;" 
                                          onsubmit="return confirm('Are you sure you want to delete this installment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($installments->hasPages())
                <div class="text-center">
                    {{ $installments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    $('#installmentsTable').DataTable({
        paging: false,
        info: false,
        ordering: true,
        searching: true,
        responsive: true,
        order: [[3, 'asc']], // Sort by due date column (index 3)
        columnDefs: [
            { targets: [9], orderable: false }, // Disable sorting for Actions column
            { 
                targets: [3], // Due date column
                type: 'date',
                render: function(data, type, row) {
                    if (type === 'sort' || type === 'type') {
                        // Convert DD/MM/YYYY to YYYY-MM-DD for proper sorting
                        if (data && data !== '-') {
                            var parts = data.split('/');
                            if (parts.length === 3) {
                                return parts[2] + '-' + parts[1] + '-' + parts[0];
                            }
                        }
                        return data;
                    }
                    return data;
                }
            }
        ]
    });
});
</script>
@endpush
@endsection