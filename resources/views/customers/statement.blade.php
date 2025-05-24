@extends('layouts.master')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Customer Statement</h5>
                    <div class="ibox-tools">
                        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-primary">Back to Customers</a>
                        <button class="btn btn-sm btn-info" onclick="window.print()">Print</button>
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
                    
                    <!-- Customer Info Header -->
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Customer Information</h4>
                            <address>
                                <strong>{{ $customer->name }}</strong><br>
                                Account No: {{ $customer->account_no }}<br>
                                @if($customer->father_name)
                                    Father Name: {{ $customer->father_name }}<br>
                                @endif
                                NIC: {{ $customer->nic }}<br>
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
                        <div class="col-sm-6 text-right">
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
                                <a href="{{ route('purchases.create') }}?customer={{ $customer->id }}" class="btn btn-primary">Create First Purchase</a>
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

                    <!-- Guarantors Section -->
                    @if($customer->guarantors->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Guarantor Information</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Father Name</th>
                                            <th>Relation</th>
                                            <th>NIC</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->guarantors as $guarantor)
                                        <tr>
                                            <td>
                                                <span class="badge badge-{{ $guarantor->guarantor_no == 1 ? 'primary' : 'secondary' }}">
                                                    {{ $guarantor->guarantor_no == 1 ? 'Primary' : 'Secondary' }}
                                                </span>
                                            </td>
                                            <td><strong>{{ $guarantor->name }}</strong></td>
                                            <td>{{ $guarantor->father_name }}</td>
                                            <td>{{ $guarantor->relation }}</td>
                                            <td><code>{{ $guarantor->nic }}</code></td>
                                            <td>{{ $guarantor->phone }}</td>
                                            <td>{{ $guarantor->residence_address }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-warning">
                                <strong>No Guarantors Found!</strong> This customer should have guarantors before making purchases.
                                <a href="{{ route('guarantors.create') }}?customer={{ $customer->id }}" class="btn btn-sm btn-warning">Add Guarantor</a>
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
    @media print {
        .ibox-tools,
        .sidebar,
        .navbar,
        .footer {
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
        
        .btn {
            display: none !important;
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
</style>
@endpush
@endsection