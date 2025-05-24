@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Customer List</h1>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($customers->count())
            <div class="table-responsive">
                <table id="customers-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Account No</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>NIC</th>
                            <th>Purchases</th>
                            <th>Total Amount</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
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
                            <tr class="{{ $isDefaulter ? 'table-warning' : '' }}">
                                <td>
                                    @if ($customer->image)
                                        <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer Photo"
                                            width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                                    @else
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $customer->account_no }}</strong></td>
                                <td>
                                    <div>{{ $customer->name }}</div>
                                    <small class="text-muted">{{ $customer->father_name }}</small>
                                </td>
                                <td>
                                    <div>{{ $customer->mobile_1 }}</div>
                                    @if($customer->mobile_2)
                                        <small class="text-muted">{{ $customer->mobile_2 }}</small>
                                    @endif
                                </td>
                                <td>{{ $customer->nic }}</td>
                                <td>
                                    <span class="badge badge-info">{{ $totalPurchases }}</span>
                                </td>
                                <td>Rs. {{ number_format($totalAmount, 0) }}</td>
                                <td>Rs. {{ number_format($totalPaid, 0) }}</td>
                                <td>Rs. {{ number_format($remainingBalance, 0) }}</td>
                                <td>
                                    @if($totalPurchases == 0)
                                        <span class="badge badge-secondary">No Purchases</span>
                                    @elseif($remainingBalance == 0)
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($isDefaulter)
                                        <span class="badge badge-danger">Defaulter</span>
                                    @else
                                        <span class="badge badge-primary">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.statement', $customer->id) }}"
                                            class="btn btn-sm btn-info" title="View Statement">
                                            <i class="fa fa-file-text"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        
                                        @if($totalPurchases == 0)
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Are you sure?')"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled title="Cannot delete - has purchase history">
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
            <div class="alert alert-info text-center">
                <h4>No customers found</h4>
                <p>Start by adding your first customer!</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
            </div>
        @endif
        
        <!-- Pagination -->
        @if ($customers->hasPages())
            <div class="d-flex justify-content-center">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

@endsection

@push('styles')
<style>
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