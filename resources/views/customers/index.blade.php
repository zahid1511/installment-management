@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 custom-header">
            <h1>Customer List</h1>
            <a href="{{ route('customers.create') }}" class="btn btn-primary custom-add-btn">Add New Customer</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success custom-alert">{{ session('success') }}</div>
        @endif

        @if ($customers->count())
            <div class="table-responsive custom-table-wrapper">
                <table id="customers-table" class="table table-bordered custom-table">
                    <thead>
                        <tr>
                            <th class="field-spacing">Photo</th>
                            <th class="field-spacing">Account No</th>
                            <th class="field-spacing">Name</th>
                            <th class="field-spacing">Mobile</th>
                            <th class="field-spacing">NIC</th>
                            <th class="field-spacing">Purchases</th>
                            <th class="field-spacing">Total Amount</th>
                            <th class="field-spacing">Paid Amount</th>
                            <th class="field-spacing">Balance</th>
                            <th class="field-spacing">Status</th>
                            <th class="field-spacing">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            @php
                                // Calculate values using relationships
                                $totalPurchases = $customer->purchases->count();
                                $totalAmount = $customer->purchases->sum('total_price');
                                $totalAdvance = $customer->purchases->sum('advance_payment');
                                $totalPaid = $totalAdvance + $customer->installments()->where('status', 'paid')->sum('installment_amount');
                                $remainingBalance = $totalAmount - $totalPaid;
                                $isDefaulter = $customer->installments()->where('status', 'pending')->where('due_date', '<', now())->exists();
                            @endphp
                            <tr class="{{ $isDefaulter ? 'table-warning' : '' }} custom-row">
                                <td class="field-spacing photo-cell">
                                    @if ($customer->image)
                                        <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer Photo"
                                            width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center custom-photo-placeholder" style="width: 50px; height: 50px;">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="field-spacing"><strong>{{ $customer->account_no }}</strong></td>
                                <td class="field-spacing">
                                    <div>{{ $customer->name }}</div>
                                    <small class="text-muted custom-subtext">{{ $customer->father_name }}</small>
                                </td>
                                <td class="field-spacing">
                                    <div>{{ $customer->mobile_1 }}</div>
                                    @if($customer->mobile_2)
                                        <small class="text-muted custom-subtext">{{ $customer->mobile_2 }}</small>
                                    @endif
                                </td>
                                <td class="field-spacing">{{ $customer->nic }}</td>
                                <td class="field-spacing">
                                    <span class="badge badge-info custom-badge">{{ $totalPurchases }}</span>
                                </td>
                                <td class="field-spacing">Rs. {{ number_format($totalAmount, 0) }}</td>
                                <td class="field-spacing">Rs. {{ number_format($totalPaid, 0) }}</td>
                                <td class="field-spacing">Rs. {{ number_format($remainingBalance, 0) }}</td>
                                <td class="field-spacing">
                                    @if($totalPurchases == 0)
                                        <span class="badge badge-secondary custom-badge">No Purchases</span>
                                    @elseif($remainingBalance == 0)
                                        <span class="badge badge-success custom-badge">Completed</span>
                                    @elseif($isDefaulter)
                                        <span class="badge badge-danger custom-badge">Defaulter</span>
                                    @else
                                        <span class="badge badge-primary custom-badge">Active</span>
                                    @endif
                                </td>
                                <td class="field-spacing">
                                    <div class="btn-group action-btn-group" role="group">
                                        <a href="{{ route('customers.statement', $customer->id) }}"
                                            class="btn btn-sm btn-info custom-action-btn" title="View Statement">
                                            <i class="fa fa-file-text"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning custom-action-btn" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($totalPurchases == 0)
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')"
                                                    class="btn btn-sm btn-danger custom-action-btn" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary custom-action-btn" disabled title="Cannot delete - has purchase history">
                                                <i class="fa fa-lock"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center custom-empty-state">
                <h4>No customers found</h4>
                <p>Start by adding your first customer!</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary custom-add-btn">Add Customer</a>
            </div>
        @endif

        <!-- Pagination -->
        @if ($customers->hasPages())
            <div class="d-flex justify-content-center custom-pagination">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
<style>
/* Existing styles retained */
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.table-warning {
    background-color: #fff3cd !important;
}

/* New unique classes for enhanced styling */
.custom-header {
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.custom-add-btn {
    transition: all 0.3s ease;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
}

.custom-add-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.custom-table-wrapper {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    overflow: hidden;
}

.custom-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.field-spacing {
    padding: 12px 15px !important;
    vertical-align: middle;
    border-right: 1px solid #dee2e6;
}

.field-spacing:last-child {
    border-right: none;
}

.custom-row {
    transition: background-color 0.3s ease;
}

.custom-row:hover {
    background-color: #f1f3f5;
}

.photo-cell {
    text-align: center;
}

.custom-photo-placeholder {
    font-size: 1rem;
    font-weight: 500;
}

.custom-subtext {
    display: block;
    margin-top: 5px;
    font-size: 0.85em;
}

.custom-badge {
    padding: 6px 10px;
    border-radius: 12px;
    font-weight: 500;
}

.action-btn-group .custom-action-btn {
    padding: 6px 10px;
    margin-right: 5px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.action-btn-group .custom-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.custom-alert {
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.custom-empty-state {
    padding: 20px;
    border-radius: 8px;
    background-color: #e7f1ff;
}

.custom-empty-state h4 {
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.custom-empty-state p {
    margin-bottom: 15px;
    font-size: 1rem;
}

.custom-pagination {
    margin-top: 20px;
}

.custom-pagination .pagination .page-link {
    border-radius: 4px;
    margin: 0 3px;
    transition: all 0.2s ease;
}

.custom-pagination .pagination .page-link:hover {
    background-color: #007bff;
    color: #fff;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .field-spacing {
        padding: 8px 10px !important;
    }

    .custom-action-btn {
        padding: 4px 8px;
        font-size: 0.85rem;
    }

    .custom-table th, .custom-table td {
        font-size: 0.9rem;
    }

    .custom-photo-placeholder, img {
        width: 40px !important;
        height: 40px !important;
    }
}
</style>
@endpush

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#customers-table').DataTable({
            paging: false,
            info: false,
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, -1] } // Disable sorting on photo and actions columns
            ]
        });
    });
</script>
@endpush