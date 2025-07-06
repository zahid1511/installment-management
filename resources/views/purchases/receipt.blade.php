<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .receipt-container { max-width: 1000px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .receipt-title { font-size: 16px; font-weight: bold; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .info-label { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 15px 0; }
        .amount-section { background-color: #f5f5f5; padding: 10px; margin: 15px 0; }
        .total-amount { font-size: 18px; font-weight: bold; text-align: center; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }

        /* Add styling for grid layout */
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .col-md-6 {
            flex: 0 0 48%; /* 6 columns */
            max-width: 48%;
        }
        .col-md-12 {
            flex: 0 0 100%; /* 12 columns */
            max-width: 100%;
        }

        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Talal & Niazi Electronics</div>
            <div class="d-flex" style="display: flex; justify-content: space-between;">
            <div> <div class="receipt-title">PAYMENT RECEIPT</div>
            </div>
        <div>
    <div class="company-name">Contact: 03008183092</div>

    </div>
        </div>
    </div>

        <!-- Receipt Info (First row of 6 columns each) -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Receipt No:</span>
                    <span>{{ $installment->receipt_no }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span>{{ $installment->date->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Time:</span>
                    <span>{{ $installment->date->format('h:i A') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span>{{ $installment->customer->name }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Account No:</span>
                    <span>{{ $installment->customer->account_no }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span>{{ $installment->customer->mobile_1 }}</span>
                </div>
            </div>
        </div>

        <!-- Second row of 6 columns each -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Paid Installment:</span>
                    <span>
                        #{{ $installment->purchase->installments()->where('id', '<=', $installment->id)->where('status', 'paid')->count() }}
                        of {{ $installment->purchase->installment_months }}
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Product:</span>
                    <span>{{ $installment->purchase->product->company }} {{ $installment->purchase->product->model }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Serial No:</span>
                    <span>{{ $installment->purchase->product->serial_no }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Total Price:</span>
                    <span>{{ ucfirst($installment->purchase->total_price) }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span>{{ ucfirst($installment->payment_method) }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Previous Balance:</span>
                    <span>Rs. {{ number_format($installment->pre_balance, 2) }}</span>
                </div>
            </div>
        </div>


        <!-- Third row of 6 columns each -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Payment Amount:</span>
                    <span>Rs. {{ number_format($installment->installment_amount, 2) }}</span>
                </div>
            </div>
            @if($installment->discount > 0)
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Discount:</span>
                    <span>Rs. {{ number_format($installment->discount, 2) }}</span>
                </div>
            </div>
            @endif
            @if($installment->fine_amount > 0)
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Fine Amount:</span>
                    <span>Rs. {{ number_format($installment->fine_amount, 2) }}</span>
                </div>
            </div>
            @endif
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Remaining Balance:</span>
                    <span>Rs. {{ number_format($installment->balance, 2) }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">TOTAL PAID: Rs.</span>
                     {{ number_format($installment->installment_amount, 2) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Received By:</span>
                    <span>{{ $installment->officer->name ?? 'N/A' }}</span>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="text-bold" style="font-weight: 600;">
                <strong>ضروری نوٹ</strong><br>
                <strong>
                  قسط ہر ماہ کے 5 <strong> تاریخ تک لازمی جمع کروائیں۔ ادارہ کی رسید کے بغیر لین دین نہ کریں، ادارہ کسی بھی غیر قانونی استعمال کا ذمہ دار نہ ہوگا۔</strong>
                  ادارہ کا کوئی بھی سٹاف رسید پرنٹ کیے بغیر کسی قسم کی تصدیق نہیں دے سکتا۔
                </strong>
              </div>

        </div>
    </div>

    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 20px; font-size: 16px;">
            Print Receipt
        </button>
        <button onclick="window.close()" class="btn btn-default" style="padding: 10px 20px; font-size: 16px; margin-left: 10px;">
            Close
        </button>
    </div>

    <script>
        // Auto print when page loads
