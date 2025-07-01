<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .receipt-container { max-width: 400px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .receipt-title { font-size: 16px; font-weight: bold; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .info-label { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 15px 0; }
        .amount-section { background-color: #f5f5f5; padding: 10px; margin: 15px 0; }
        .total-amount { font-size: 18px; font-weight: bold; text-align: center; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
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
            <div class="company-name">Your Company Name</div>
            <div>Contact: Your Phone | Email</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
        </div>

        <!-- Receipt Info -->
        <div class="info-row">
            <span class="info-label">Receipt No:</span>
            <span>{{ $installment->receipt_no }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date:</span>
            <span>{{ $installment->date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Time:</span>
            <span>{{ $installment->date->format('h:i A') }}</span>
        </div>

        <div class="divider"></div>

        <!-- Customer Info -->
        <div class="info-row">
            <span class="info-label">Customer:</span>
            <span>{{ $installment->customer->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Account No:</span>
            <span>{{ $installment->customer->account_no }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone:</span>
            <span>{{ $installment->customer->phone }}</span>
        </div>

        <div class="divider"></div>

        <!-- Product Info -->
        <div class="info-row">
            <span class="info-label">Product:</span>
            <span>{{ $installment->purchase->product->company }} {{ $installment->purchase->product->model }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Serial No:</span>
            <span>{{ $installment->purchase->product->serial_no }}</span>
        </div>

        <div class="divider"></div>

        <!-- Payment Details -->
        <div class="info-row">
            <span class="info-label">Payment Method:</span>
            <span>{{ ucfirst($installment->payment_method) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Previous Balance:</span>
            <span>Rs. {{ number_format($installment->pre_balance, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Amount:</span>
            <span>Rs. {{ number_format($installment->installment_amount, 2) }}</span>
        </div>
        @if($installment->discount > 0)
        <div class="info-row">
            <span class="info-label">Discount:</span>
            <span>Rs. {{ number_format($installment->discount, 2) }}</span>
        </div>
        @endif
        @if($installment->fine_amount > 0)
        <div class="info-row">
            <span class="info-label">Fine Amount:</span>
            <span>Rs. {{ number_format($installment->fine_amount, 2) }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Remaining Balance:</span>
            <span>Rs. {{ number_format($installment->balance, 2) }}</span>
        </div>

        <!-- Total Amount Section -->
        <div class="amount-section">
            <div class="total-amount">
                TOTAL PAID: Rs. {{ number_format($installment->installment_amount, 2) }}
            </div>
        </div>

        <!-- Recovery Officer -->
        <div class="info-row">
            <span class="info-label">Received By:</span>
            <span>{{ $installment->officer->name ?? 'N/A' }}</span>
        </div>

        @if($installment->remarks)
        <div class="divider"></div>
        <div class="info-row">
            <span class="info-label">Remarks:</span>
        </div>
        <div style="margin-top: 5px; font-size: 12px;">{{ $installment->remarks }}</div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Thank you for your payment!</div>
            <div>This is a computer generated receipt.</div>
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
        window.onload = function() {
            // Small delay to ensure page is fully loaded
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>