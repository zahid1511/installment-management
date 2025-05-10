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
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Customer Info Header -->
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Customer Information</h4>
                            <address>
                                <strong>{{ $customer->name }}</strong><br>
                                Account No: {{ $customer->account_no }}<br>
                                Father Name: {{ $customer->father_name }}<br>
                                NIC: {{ $customer->nic }}<br>
                                Phone: {{ $customer->mobile_1 }}
                                @if($customer->mobile_2)
                                    , {{ $customer->mobile_2 }}
                                @endif
                                <br>
                                Address: {{ $customer->residence }}
                            </address>
                        </div>
                        <div class="col-sm-6 text-right">
                            <h4>Statement Date</h4>
                            <p>{{ date('d M Y') }}<br>
                            Gender: {{ ucfirst($customer->gender) }}<br>
                            Occupation: {{ $customer->occupation }}</p>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table invoice-table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Total Price</td>
                                            <td class="text-right">{{ number_format($customer->total_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Advance Payment</td>
                                            <td class="text-right">({{ number_format($customer->advance, 2) }})</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Current Balance</strong></td>
                                            <td class="text-right"><strong>{{ number_format($customer->balance, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Monthly Installment</td>
                                            <td class="text-right">{{ number_format($customer->installment_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total Installments</td>
                                            <td class="text-right">{{ $customer->installments }} months</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Products Section -->
                    @if($customer->purchases->count() > 0)
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>Purchased Products</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Purchase Date</th>
                                            <th>Company</th>
                                            <th>Model</th>
                                            <th>Serial No</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Advance</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->purchases as $purchase)
                                        <tr>
                                            <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                                            <td>{{ $purchase->product->company }}</td>
                                            <td>{{ $purchase->product->model }}</td>
                                            <td>{{ $purchase->product->serial_no }}</td>
                                            <td class="text-right">{{ number_format($purchase->total_price, 2) }}</td>
                                            <td class="text-right">{{ number_format($purchase->advance_payment, 2) }}</td>
                                            <td class="text-right">{{ number_format($purchase->remaining_balance, 2) }}</td>
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
                            <h4>Guarantors</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Father Name</th>
                                            <th>Relation</th>
                                            <th>NIC</th>
                                            <th>Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customer->guarantors as $guarantor)
                                        <tr>
                                            <td>{{ $guarantor->guarantor_no }}</td>
                                            <td>{{ $guarantor->name }}</td>
                                            <td>{{ $guarantor->father_name }}</td>
                                            <td>{{ $guarantor->relation }}</td>
                                            <td>{{ $guarantor->nic }}</td>
                                            <td>{{ $guarantor->phone }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                            <h4>Payment History</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Date Paid</th>
                                            <th>Receipt No</th>
                                            <th>Amount</th>
                                            <th>Discount</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Officer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allInstallments as $installment)
                                        <tr>
                                            <td>{{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $installment->date ? $installment->date->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $installment->receipt_no ?? '-' }}</td>
                                            <td>{{ number_format($installment->installment_amount, 2) }}</td>
                                            <td>{{ number_format($installment->discount, 2) }}</td>
                                            <td>{{ number_format($installment->balance, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $installment->status == 'paid' ? 'success' : ($installment->isOverdue() ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($installment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $installment->recovery_officer ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Footer -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well">
                                <h4>Status: 
                                    <span class="badge badge-{{ $customer->is_defaulter ? 'danger' : 'primary' }}">
                                        {{ $customer->is_defaulter ? 'DEFAULTER' : 'ACTIVE' }}
                                    </span>
                                </h4>
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
    }
    
    .invoice-table th {
        border-top: 2px solid #000;
        border-bottom: 2px solid #000;
    }
    
    .invoice-table td {
        border-bottom: 1px solid #ddd;
    }
</style>
@endpush
@endsection