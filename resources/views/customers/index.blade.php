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

        @if (session('error'))
            <div class="alert alert-danger custom-alert">{{ session('error') }}</div>
        @endif

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
                    <!-- DataTables will populate this -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
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

    /* DataTables Custom Styling */
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 8px 12px;
        margin-left: 10px;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 15px;
        color: #6c757d;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0 2px;
        padding: 8px 12px;
        background: #fff;
        color: #495057;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        background: #f8f9fa;
        color: #6c757d;
        border-color: #dee2e6;
    }

    /* Loading overlay */
    .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -25px;
        text-align: center;
        padding: 10px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            text-align: left;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            text-align: center;
        }
    }
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('customers.index') }}",
            type: 'GET'
        },
        columns: [
            { data: 'photo', name: 'photo', orderable: false, searchable: false },
            { data: 'account_no', name: 'account_no' },
            { data: 'name_with_father', name: 'name' },
            { data: 'mobile_numbers', name: 'mobile_1' },
            { data: 'nic', name: 'nic' },
            { data: 'purchases_count', name: 'purchases_count', orderable: false, searchable: false },
            { data: 'total_amount', name: 'total_amount', orderable: false, searchable: false },
            { data: 'paid_amount', name: 'paid_amount', orderable: false, searchable: false },
            { data: 'balance', name: 'balance', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'desc']]
    });
});

function confirmDelete(customerId, customerName, totalPurchases) {
    let message = totalPurchases > 0 
        ? `⚠️ WARNING! This will delete customer "${customerName}" and all ${totalPurchases} purchases. Continue?`
        : `Delete customer "${customerName}"?`;
    
    if (confirm(message)) {
        fetch(`/admin/customers/${customerId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#customers-table').DataTable().ajax.reload();
                alert('✅ ' + data.message);
            } else {
                alert('❌ ' + data.message);
            }
        });
    }
}
</script>
@endpush