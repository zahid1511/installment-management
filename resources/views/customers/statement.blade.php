@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title no-print">
                    <h5>Customer Statement</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary">Back to Customers</a>
                        <button class="btn btn-sm btn-info" onclick="window.print()">
                            <i class="fa fa-print"></i> Print
                        </button>
                        @if($customer->purchases->count() > 0)
                            <a href="{{ route('purchases.create') }}?customer={{ $customer->id }}" class="btn btn-sm btn-success">New Purchase</a>
                        @endif
                    </div>
                </div>
                <div class="ibox-content">
                    @php
                        // Calculate all financial values from relationships
                        $totalPurchases = $customer->purchases->count();
                        $totalPurchaseAmount = $customer->purchases->sum('total_price');
                        $totalAdvancePayments = $customer->purchases->sum('advance_payment');
                        $totalPaidInstallments = $customer->installments()->where('status', 'paid')->sum('installment_amount');
                        $totalPaidAmount = $totalAdvancePayments + $totalPaidInstallments;
                        $currentBalance = $totalPurchaseAmount - $totalPaidAmount;
                        $totalMonthlyInstallments = $customer->purchases()->where('status', 'active')->sum('monthly_installment');
                        $pendingInstallments = $customer->installments()->where('status', 'pending')->count();
                        $overdueInstallments = $customer->installments()->where('status', 'pending')->where('due_date', '<', now())->count();
                        
                        // Status calculation
                        $customerStatus = 'ACTIVE';
                        if ($totalPurchases == 0) {
                            $customerStatus = 'NO PURCHASES';
                        } elseif ($currentBalance <= 0) {
                            $customerStatus = 'COMPLETED';
                        } elseif ($overdueInstallments > 0) {
                            $customerStatus = 'DEFAULTER';
                        }
                    @endphp
                    
                    <!-- Customer Info Header with Image -->
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <!-- Customer Image -->
                            <div class="customer-photo-container">
                                @if($customer->image)
                                    <img src="{{ asset('backend/img/customers/' . $customer->image) }}" 
                                         alt="Customer Photo" class="customer-photo">
                                @else
                                    <div class="customer-photo-placeholder">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="customer-photo-label">Customer Photo</div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <h4>Customer Information</h4>
                            <address>
                                <strong>{{ $customer->name }}</strong><br>
                                Account No: <code>{{ $customer->account_no }}</code><br>
                                @if($customer->father_name)
                                    Father Name: {{ $customer->father_name }}<br>
                                @endif
                                NIC: <code>{{ $customer->nic }}</code><br>
                                Phone: {{ $customer->mobile_1 }}
                                @if($customer->mobile_2)
                                    , {{ $customer->mobile_2 }}
                                @endif
                                <br>
                                @if($customer->residence)
                                    Address: {{ $customer->residence }}<br>
                                @endif
                                @if($customer->occupation)
                                    Occupation: {{ $customer->occupation }}
                                @endif
                            </address>
                        </div>
                        <div class="col-sm-5 text-right">
                            <h4>Statement Information</h4>
                            <p><strong>Statement Date:</strong> {{ date('d M Y') }}<br>
                            <strong>Total Purchases:</strong> {{ $totalPurchases }}<br>
                            @if($customer->gender)
                                <strong>Gender:</strong> {{ ucfirst($customer->gender) }}<br>
                            @endif
                            <strong>Customer Status:</strong> 
                            <span class="badge badge-{{ $customerStatus == 'DEFAULTER' ? 'danger' : ($customerStatus == 'COMPLETED' ? 'success' : ($customerStatus == 'NO PURCHASES' ? 'secondary' : 'primary')) }}">
                                {{ $customerStatus }}
                            </span>
                            </p>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    @if($totalPurchases > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Financial Summary</h4>
                            <div class="table-responsive">
                                <table class="table invoice-table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Amount (Rs.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Total Purchase Amount</td>
                                            <td class="text-right">{{ number_format($totalPurchaseAmount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Advance Payments</td>
                                            <td class="text-right">({{ number_format($totalAdvancePayments, 2) }})</td>
                                        </tr>
                                        <tr>
                                            <td>Total Installments Paid</td>
                                            <td class="text-right">({{ number_format($totalPaidInstallments, 2) }})</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Current Outstanding Balance</strong></td>
                                            <td class="text-right"><strong>{{ number_format($currentBalance, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Total Monthly Installment (Active)</td>
                                            <td class="text-right">{{ number_format($totalMonthlyInstallments, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Installments</td>
                                            <td class="text-right">{{ $pendingInstallments }} installments</td>
                                        </tr>
                                        @if($overdueInstallments > 0)
                                        <tr class="danger">
                                            <td><strong>Overdue Installments</strong></td>
                                            <td class="text-right"><strong>{{ $overdueInstallments }} installments</strong></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info text-center">
                                <h4>No Purchase History</h4>
                                <p>This customer has not made any purchases yet.</p>
                                <a href="{{ route('purchases.create') }}?customer={{ $customer->id }}" class="btn btn-primary no-print">Create First Purchase</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Products Section -->
                    @if($customer->purchases->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Purchase History</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Purchase Date</th>
                                            <th>Product Details</th>
                                            <th>Serial No</th>
                                            <th class="text-right">Total Price</th>
                                            <th class="text-right">Advance</th>
                                            <th class="text-right">Remaining</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->purchases as $index => $purchase)
                                        @php
                                            $purchasePaid = $purchase->advance_payment + $purchase->installments()->where('status', 'paid')->sum('installment_amount');
                                            $purchaseRemaining = $purchase->total_price - $purchasePaid;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                            <td>
                                                <strong>{{ $purchase->product->company }} {{ $purchase->product->model }}</strong>
                                            </td>
                                            <td><code>{{ $purchase->product->serial_no }}</code></td>
                                            <td class="text-right">{{ number_format($purchase->total_price, 2) }}</td>
                                            <td class="text-right">{{ number_format($purchase->advance_payment, 2) }}</td>
                                            <td class="text-right">{{ number_format($purchaseRemaining, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $purchase->status == 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($purchase->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Guarantors Section with Images -->
                    @if($customer->guarantors->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Guarantor Information</h4>
                            <div class="guarantors-section">
                                @foreach($customer->guarantors as $guarantor)
                                <div class="guarantor-card">
                                    <div class="guarantor-header">
                                        <div class="guarantor-photo-container">
                                            @if($guarantor->image)
                                                <img src="{{ asset($guarantor->image) }}" alt="Guarantor Photo" class="guarantor-photo">
                                            @else
                                                <div class="guarantor-photo-placeholder">
                                                    {{ strtoupper(substr($guarantor->name, 0, 4)) }}
                                                </div>
                                            @endif
                                            {{-- <div class="guarantor-photo-label">
                                                <span class="badge badge-{{ $guarantor->guarantor_no == 1 ? 'primary' : 'secondary' }}">
                                                    {{ $guarantor->guarantor_no == 1 ? 'Primary' : 'Secondary' }}
                                                </span>
                                            </div> --}}
                                            <div class="guarantor-photo-label">
                                                @php
                                                    switch($guarantor->guarantor_no) {
                                                        case 1:
                                                            $label = 'Primary';
                                                            $color = 'primary';
                                                            break;
                                                        case 2:
                                                            $label = 'Secondary';
                                                            $color = 'secondary';
                                                            break;
                                                        case 3:
                                                            $label = 'Third';
                                                            $color = 'info';
                                                            break;
                                                        case 4:
                                                            $label = 'Reserve';
                                                            $color = 'dark';
                                                            break;
                                                        default:
                                                            $label = 'Unknown';
                                                            $color = 'light';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $color }}">
                                                    {{ $label }} Guarantor
                                                </span>
                                            </div>
                                        </div>
                                        <div class="guarantor-details">
                                            <h5>{{ $guarantor->name }}</h5>
                                            <p class="mb-1"><strong>Father:</strong> {{ $guarantor->father_name }}</p>
                                            <p class="mb-1"><strong>Relation:</strong> {{ $guarantor->relation }}</p>
                                            <p class="mb-1"><strong>NIC:</strong> <code>{{ $guarantor->nic }}</code></p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ $guarantor->phone }}</p>
                                            @if($guarantor->occupation)
                                                <p class="mb-1"><strong>Occupation:</strong> {{ $guarantor->occupation }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="guarantor-addresses">
                                        <div class="address-section">
                                            <strong>Residence:</strong><br>
                                            <small>{{ $guarantor->residence_address }}</small>
                                        </div>
                                        @if($guarantor->office_address)
                                        <div class="address-section">
                                            <strong>Office:</strong><br>
                                            <small>{{ $guarantor->office_address }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-warning">
                                <strong>No Guarantors Found!</strong> This customer should have guarantors before making purchases.
                                <a href="{{ route('guarantors.create') }}?customer={{ $customer->id }}" class="btn btn-sm btn-warning no-print">Add Guarantor</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Payment History -->
                    @php
                        $allInstallments = collect();
                        foreach($customer->purchases as $purchase) {
                            $allInstallments = $allInstallments->merge($purchase->installments);
                        }
                        $allInstallments = $allInstallments->sortBy('due_date');
                    @endphp

                    @if($allInstallments->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Installment Payment Schedule</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Product</th>
                                            <th class="text-right">Amount</th>
                                            <th>Status</th>
                                            <th>Date Paid</th>
                                            <th>Receipt No</th>
                                            <th class="text-right">Discount</th>
                                            <th class="text-right">Fine</th>
                                            <th>Recovery Officer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allInstallments as $installment)
                                        @php
                                            $isOverdue = $installment->status == 'pending' && $installment->due_date < now();
                                        @endphp
                                        <tr class="{{ $isOverdue ? 'table-danger' : ($installment->status == 'paid' ? 'table-success' : '') }}">
                                            <td>{{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($installment->purchase && $installment->purchase->product)
                                                    {{ $installment->purchase->product->company }} {{ $installment->purchase->product->model }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($installment->installment_amount, 0) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $installment->status == 'paid' ? 'success' : ($isOverdue ? 'danger' : 'warning') }}">
                                                    {{ $installment->status == 'paid' ? 'PAID' : ($isOverdue ? 'OVERDUE' : 'PENDING') }}
                                                </span>
                                            </td>
                                            <td>{{ $installment->date ? $installment->date->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $installment->receipt_no ?? '-' }}</td>
                                            <td class="text-right">{{ $installment->discount > 0 ? number_format($installment->discount, 0) : '-' }}</td>
                                            <td class="text-right">{{ $installment->fine_amount > 0 ? number_format($installment->fine_amount, 0) : '-' }}</td>
                                            <td>{{ $installment->officer?->name ?? $installment->recovery_officer ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Summary Footer -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4>Customer Status: 
                                            <span class="badge badge-{{ $customerStatus == 'DEFAULTER' ? 'danger' : ($customerStatus == 'COMPLETED' ? 'success' : ($customerStatus == 'NO PURCHASES' ? 'secondary' : 'primary')) }}">
                                                {{ $customerStatus }}
                                            </span>
                                        </h4>
                                        @if($overdueInstallments > 0)
                                            <p class="text-danger"><strong>⚠️ Action Required:</strong> {{ $overdueInstallments }} overdue payments need immediate attention.</p>
                                        @endif
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <p><strong>Statement Generated:</strong> {{ date('d M Y H:i') }}</p>
                                        @if($totalPurchases > 0)
                                            <p><strong>Outstanding Balance:</strong> Rs. {{ number_format($currentBalance, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Customer Photo Styles */
    .customer-photo-container {
        text-align: center;
        margin-bottom: 15px;
    }

    .customer-photo {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        object-fit: cover;
        border: 3px solid #007bff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .customer-photo-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        background-color: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5em;
        font-weight: bold;
        margin: 0 auto;
        border: 3px solid #6c757d;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .customer-photo-label {
        font-size: 0.8em;
        color: #6c757d;
        margin-top: 5px;
        font-weight: 500;
    }

    /* Guarantor Cards */
    .guarantors-section {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }

    .guarantor-card {
        flex: 1;
        min-width: 300px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 15px;
        background-color: #f8f9fa;
    }

    .guarantor-header {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .guarantor-photo-container {
        margin-right: 15px;
        text-align: center;
    }

    .guarantor-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #007bff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .guarantor-photo-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5em;
        font-weight: bold;
        border: 2px solid #6c757d;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .guarantor-photo-label {
        margin-top: 5px;
        font-size: 0.7em;
    }

    .guarantor-details {
        flex: 1;
    }

    .guarantor-details h5 {
        margin-bottom: 8px;
        color: #495057;
        font-weight: 600;
    }

    .guarantor-details p {
        margin-bottom: 5px;
        font-size: 0.9em;
        color: #6c757d;
    }

    .guarantor-addresses {
        border-top: 1px solid #dee2e6;
        padding-top: 10px;
        margin-top: 10px;
    }

    .address-section {
        margin-bottom: 10px;
    }

    .address-section strong {
        color: #495057;
        font-size: 0.9em;
    }

    .address-section small {
        color: #6c757d;
        line-height: 1.4;
    }

    /* Print Styles */
    @media print {
        .no-print,
        .ibox-tools,
        .sidebar,
        .navbar,
        .footer,
        .btn {
            display: none !important;
        }
        
        .wrapper {
            margin: 0;
        }
        
        .ibox {
            box-shadow: none;
            border: none;
        }
        
        .ibox-content {
            padding: 20px;
        }

        .customer-photo,
        .customer-photo-placeholder {
            width: 100px;
            height: 100px;
            border: 2px solid #000;
        }

        .guarantor-photo,
        .guarantor-photo-placeholder {
            width: 60px;
            height: 60px;
            border: 1px solid #000;
        }

        .guarantors-section {
            display: block;
        }

        .guarantor-card {
            margin-bottom: 15px;
            page-break-inside: avoid;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }

        .guarantor-header {
            display: flex;
            align-items: flex-start;
        }

        /* Ensure good spacing for print */
        .row {
            margin-bottom: 20px;
        }

        h4 {
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        /* Print color adjustments */
        .badge {
            border: 1px solid #000;
            padding: 2px 6px;
        }

        .table {
            font-size: 0.85em;
        }

        .table th {
            border-bottom: 2px solid #000;
            background-color: #f0f0f0;
        }

        .table td {
            border-bottom: 1px solid #ccc;
        }
    }
    
    .invoice-table th {
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
        background-color: #f8f9fa;
    }
    
    .invoice-table td {
        border-bottom: 1px solid #ddd;
    }
    
    .table-danger {
        background-color: #f8d7da !important;
    }
    
    .table-success {
        background-color: #d1edff !important;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    code {
        background-color: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 0.9em;
    }
    
    .well {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 15px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .guarantors-section {
            display: block;
        }
        
        .guarantor-card {
            min-width: 100%;
            margin-bottom: 15px;
        }
        
        .guarantor-header {
            flex-direction: column;
            text-align: center;
        }
        
        .guarantor-photo-container {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
</style>
@endpush
@endsection