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
                    
                    <!-- Header with Company Info -->
                    <div class="statement-header">
                        <div class="company-info">
                            <h3>Customer Account Information Detail</h3>
                            <div class="print-info">
                                <div>Print Date: {{ date('d-M-Y') }}</div>
                                <div>Print Time: {{ date('H:i:s A') }}</div>
                                <div>Page 1 of 1</div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info with Photos Section -->
                    <div class="customer-section">
                        <div class="customer-basic-info">
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>Account No:</strong> {{ $customer->account_no }}
                                </div>
                                <div class="info-item">
                                    <strong>Date:</strong> {{ date('d-M-Y') }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>Customer:</strong> {{ $customer->name }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>F/H Name:</strong> {{ $customer->father_name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <strong>Occupation:</strong> {{ $customer->occupation ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item full-width">
                                    <strong>Residence:</strong> {{ $customer->residence ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item full-width">
                                    <strong>Off. Address:</strong> {{ $customer->office_address ?? 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <!-- Customer Photo -->
                        <div class="customer-photo-section">
                            @if($customer->image)
                                <img src="{{ asset('backend/img/customers/' . $customer->image) }}" alt="Customer" class="customer-img">
                            @else
                                <div class="customer-placeholder">{{ strtoupper(substr($customer->name, 0, 2)) }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Financial and Product Summary -->
                    @if($totalPurchases > 0)
                    <div class="financial-section">
                        <div class="financial-left">
                            <div class="info-group">
                                <div class="info-row">
                                    <div class="info-item"><strong>Mobile # :</strong> {{ $customer->mobile_1 }}</div>
                                    <div class="info-item"><strong>Company:</strong> {{ $customer->purchases->first()->product->company ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Rl/Cr Mobile:</strong> {{ $customer->mobile_2 ?? 'N/A' }}</div>
                                    <div class="info-item"><strong>Product:</strong> {{ $customer->purchases->first()->product->model ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>NIC:</strong> {{ $customer->nic }}</div>
                                    <div class="info-item"><strong>Model:</strong> {{ $customer->purchases->first()->product->model ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Gender:</strong> {{ ucfirst($customer->gender ?? 'N/A') }}</div>
                                    <div class="info-item"><strong>Serial #:</strong> {{ $customer->purchases->first()->product->serial_no ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Purchase Price:</strong> {{ number_format($totalPurchaseAmount, 0) }}</div>
                                    <div class="info-item"><strong>Monthly Installment:</strong> {{ number_format($totalMonthlyInstallments, 0) }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Advance Payment:</strong> {{ number_format($totalAdvancePayments, 0) }}</div>
                                    <div class="info-item"><strong>Duration (Months):</strong> {{ $customer->purchases->first()->installment_months ?? 0 }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Total Paid:</strong> {{ number_format($totalPaidAmount, 0) }}</div>
                                    <div class="info-item"><strong>Paid Installments:</strong> {{ $customer->installments()->where('status', 'paid')->count() }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Remaining Balance:</strong> {{ number_format($currentBalance, 0) }}</div>
                                    <div class="info-item"><strong>Pending Installments:</strong> {{ $pendingInstallments }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item"><strong>Status:</strong> {{ $customerStatus }}</div>
                                    @if($overdueInstallments > 0)
                                        <div class="info-item"><strong>Overdue:</strong> {{ $overdueInstallments }} installments</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="financial-right">
                            <div class="product-info">
                                @if($customer->purchases->first())
                                    @php $firstPurchase = $customer->purchases->first(); @endphp
                                    <div class="info-item"><strong>Company:</strong> {{ $firstPurchase->product->company }}</div>
                                    <div class="info-item"><strong>Model:</strong> {{ $firstPurchase->product->model }}</div>
                                    <div class="info-item"><strong>Serial No:</strong> {{ $firstPurchase->product->serial_no }}</div>
                                    <div class="info-item"><strong>Product Price:</strong> Rs. {{ number_format($firstPurchase->product->price, 0) }}</div>
                                @else
                                    <div class="info-item"><strong>Product:</strong> No purchase yet</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Guarantors Section -->
                    @if($customer->guarantors->count() > 0)
                    <div class="guarantors-section">
                        <table class="guarantor-table">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <th>
                                            Guarantor # {{ $guarantor->guarantor_no }}
                                            <div class="guarantor-photo-in-header">
                                                @if($guarantor->image)
                                                    <img src="{{ asset($guarantor->image) }}" alt="Guarantor {{ $guarantor->guarantor_no }}" class="guarantor-img-small">
                                                @else
                                                    <div class="guarantor-placeholder-small">G{{ $guarantor->guarantor_no }}</div>
                                                @endif
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->name }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>F/H Name:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->father_name }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->phone }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>NIC:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->nic }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Residence:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ substr($guarantor->residence_address, 0, 40) }}{{ strlen($guarantor->residence_address) > 40 ? '...' : '' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Office:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->office_address ? substr($guarantor->office_address, 0, 40) . (strlen($guarantor->office_address) > 40 ? '...' : '') : 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Occupation:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->occupation ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Relation:</strong></td>
                                    @foreach($customer->guarantors->take(2) as $guarantor)
                                        <td>{{ $guarantor->relation }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Payment History Table (Compact) -->
                    @if($customer->installments->count() > 0)
                    <div class="payment-history">
                        <table class="payment-table">
                            <thead>
                                <tr>
                                    <th>S.#</th>
                                    <th>Date</th>
                                    <th>Rcv. #</th>
                                    <th>Pre-Bal</th>
                                    <th>Install.</th>
                                    <th>Disc</th>
                                    <th>Balance</th>
                                    <th>Fine</th>
                                    <th>F-Type</th>
                                    <th>Recovery Officer</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customer->installments()->orderBy('due_date')->take(10)->get() as $index => $installment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $installment->date ? $installment->date->format('d/m/Y') : $installment->due_date->format('d/m/Y') }}</td>
                                    <td>{{ $installment->receipt_no ?? substr($installment->id, -6) }}</td>
                                    <td>{{ number_format($installment->pre_balance, 0) }}</td>
                                    <td>{{ number_format($installment->installment_amount, 0) }}</td>
                                    <td>{{ $installment->discount ?? 0 }}</td>
                                    <td>{{ number_format($installment->balance, 0) }}</td>
                                    <td>{{ $installment->fine_amount ?? 0 }}</td>
                                    <td>{{ $installment->status == 'paid' ? 'Nothing' : 'Pending' }}</td>
                                    <td>{{ $installment->officer?->name ?? 'N/A' }}</td>
                                    <td>{{ $installment->status == 'paid' ? 'C' : 'P' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($totalPurchases == 0)
                    <div class="no-purchase-alert">
                        <h4>No Purchase History</h4>
                        <p>This customer has not made any purchases yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Print optimized styles */
    @page {
        size: A4;
        margin: 0.5in;
    }

    .statement-header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .statement-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .print-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        margin-top: 5px;
    }

    .customer-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        border: 1px solid #000;
        padding: 10px;
    }

    .customer-basic-info {
        flex: 1;
        font-size: 12px;
    }

    .customer-photo-section {
        width: 120px;
        text-align: center;
    }

    .customer-img, .customer-placeholder {
        width: 100px;
        height: 120px;
        border: 1px solid #000;
        object-fit: cover;
    }

    .customer-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 24px;
    }

    .info-row {
        display: flex;
        margin-bottom: 3px;
    }

    .info-item {
        flex: 1;
        font-size: 11px;
        margin-right: 10px;
    }

    .info-item.full-width {
        flex: 3;
    }

    .financial-section {
        display: flex;
        margin-bottom: 15px;
        font-size: 11px;
    }

    .financial-left {
        flex: 2;
        margin-right: 20px;
    }

    .financial-right {
        flex: 1;
    }

    .guarantors-section {
        margin-bottom: 15px;
        position: relative;
    }

    .guarantor-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-bottom: 10px;
    }

    .guarantor-table th,
    .guarantor-table td {
        border: 1px solid #000;
        padding: 3px;
        text-align: left;
    }

    .guarantor-table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .guarantor-photo-in-header {
        text-align: center;
        margin-top: 5px;
    }

    .guarantor-img-small, .guarantor-placeholder-small {
        width: 50px;
        height: 60px;
        border: 1px solid #000;
        object-fit: cover;
    }

    .guarantor-placeholder-small {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 12px;
    }

    .payment-history {
        margin-bottom: 15px;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
    }

    .payment-table th,
    .payment-table td {
        border: 1px solid #000;
        padding: 2px;
        text-align: center;
    }

    .payment-table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .no-purchase-alert {
        text-align: center;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }

    /* Hide elements when printing */
    @media print {
        .no-print,
        .ibox-tools,
        .btn,
        .sidebar,
        .navbar,
        .footer {
            display: none !important;
        }

        body {
            font-size: 12px;
        }

        .wrapper {
            margin: 0;
            padding: 0;
        }

        .ibox {
            box-shadow: none;
            border: none;
        }

        .ibox-content {
            padding: 0;
        }

        /* Ensure single page */
        .customer-section,
        .financial-section,
        .guarantors-section,
        .payment-history {
            page-break-inside: avoid;
        }

        /* Adjust font sizes for print */
        .statement-header h3 {
            font-size: 16px;
        }

        .info-item {
            font-size: 10px;
        }

        .guarantor-table {
            font-size: 9px;
        }

        .payment-table {
            font-size: 8px;
        }
    }

    /* Screen view styles */
    @media screen {
        .ibox-content {
            padding: 20px;
        }

        .customer-section {
            background-color: #f8f9fa;
        }

        .guarantor-table th {
            background-color: #e9ecef;
        }

        .payment-table th {
            background-color: #e9ecef;
        }
    }
</style>
@endpush
@endsection