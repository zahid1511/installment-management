<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Installment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['customer', 'product'])->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('purchases.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'purchase_date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'advance_payment' => 'required|numeric|min:0',
            'installment_months' => 'required|integer|min:1',
            'first_installment_date' => 'required|date|after:purchase_date',
        ]);

        // Calculate values
        $remainingBalance = $request->total_price - $request->advance_payment;
        $monthlyInstallment = Purchase::calculateMonthlyInstallment(
            $request->total_price,
            $request->advance_payment,
            $request->installment_months
        );

        $lastInstallmentDate = Carbon::parse($request->first_installment_date)
            ->addMonths($request->installment_months - 1);

        // Create purchase
        $purchase = Purchase::create([
            'customer_id' => $request->customer_id,
            'product_id' => $request->product_id,
            'purchase_date' => $request->purchase_date,
            'total_price' => $request->total_price,
            'advance_payment' => $request->advance_payment,
            'remaining_balance' => $remainingBalance,
            'installment_months' => $request->installment_months,
            'monthly_installment' => $monthlyInstallment,
            'first_installment_date' => $request->first_installment_date,
            'last_installment_date' => $lastInstallmentDate,
        ]);

        // Create installment schedule
        $this->createInstallmentSchedule($purchase);

        // Update customer record
        $customer = Customer::find($request->customer_id);
        $customer->update([
            'total_price' => $request->total_price,
            'advance' => $request->advance_payment,
            'balance' => $remainingBalance,
            'installment_amount' => $monthlyInstallment,
            'installments' => $request->installment_months,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['customer', 'product', 'installments' => function($query) {
            $query->with('officer');
        }]);
        return view('purchases.show', compact('purchase'));
    }

    private function createInstallmentSchedule(Purchase $purchase)
    {
        $currentDate = Carbon::parse($purchase->first_installment_date);
        $remainingBalance = $purchase->remaining_balance;

        for ($i = 1; $i <= $purchase->installment_months; $i++) {
            $dueDate = $currentDate->copy()->addMonths($i - 1);
            
            // Calculate balance for this installment
            $newBalance = $remainingBalance - $purchase->monthly_installment;
            if ($i == $purchase->installment_months) {
                // Last installment might need adjustment
                $purchase->monthly_installment = $remainingBalance;
                $newBalance = 0;
            }

            Installment::create([
                'customer_id' => $purchase->customer_id,
                'purchase_id' => $purchase->id,
                'date' => null, // Will be filled when payment is made
                'due_date' => $dueDate,
                'receipt_no' => null, // Will be filled when payment is made
                'pre_balance' => $remainingBalance,
                'installment_amount' => $purchase->monthly_installment,
                'discount' => 0,
                'balance' => $newBalance,
                'fine_amount' => 0,
                'status' => 'pending',
                'recovery_officer' => null, // Will be filled when payment is made
                'remarks' => "Installment $i of {$purchase->installment_months}",
            ]);

            $remainingBalance = $newBalance;
        }
    }

    // Update the processPayment method in PurchaseController
    public function processPayment(Request $request, Purchase $purchase)
    {
        $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'payment_date' => 'required|date',
            'receipt_no' => 'required|string',
            'payment_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'recovery_officer_id' => 'required|exists:recovery_officers,id', // Changed from recovery_officer to recovery_officer_id
            'payment_method' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $installment = Installment::findOrFail($request->installment_id);
        
        // Calculate fine if overdue
        $fine = $installment->calculateFine();
        
        // Update installment
        $installment->update([
            'date' => $request->payment_date,
            'receipt_no' => $request->receipt_no,
            'installment_amount' => $request->payment_amount,
            'discount' => $request->discount ?? 0,
            'fine_amount' => $fine,
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'recovery_officer_id' => $request->recovery_officer_id, // Changed to recovery_officer_id
            'remarks' => $request->remarks,
        ]);

        // Check if all installments are paid
        $remainingInstallments = $purchase->installments()->where('status', 'pending')->count();
        if ($remainingInstallments == 0) {
            $purchase->update(['status' => 'completed']);
        }

        // Update customer defaulter status
        $customer = $purchase->customer;
        $customer->update([
            'is_defaulter' => $customer->purchases()->where('status', 'active')->get()->some(function($p) {
                return $p->isDefaulted();
            })
        ]);

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Payment processed successfully');
    }
}