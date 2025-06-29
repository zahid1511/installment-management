<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Installment;
use App\Models\RecoveryOfficer;
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
        $recoveryOfficers = RecoveryOfficer::where('is_active', true)->get();
        return view('purchases.create', compact('customers', 'products', 'recoveryOfficers'));
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
            'first_installment_date' => 'required|date|after_or_equal:purchase_date',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
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

        // Create installment schedule with selected recovery officer
        $this->createInstallmentSchedule($purchase, $request->recovery_officer_id);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['customer', 'product', 'installments' => function($query) {
            $query->with('officer');
        }]);
        return view('purchases.show', compact('purchase'));
    }

     public function edit(Purchase $purchase)
    {
        $customers = Customer::all();
        $products = Product::all();
        $recoveryOfficers = RecoveryOfficer::where('is_active', true)->get();

        return view('purchases.edit', compact('purchase', 'customers', 'products', 'recoveryOfficers'));
    }

     public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'purchase_date' => 'required|date',
            'total_price' => 'required|numeric|min:0',
            'advance_payment' => 'required|numeric|min:0',
            'installment_months' => 'required|integer|min:1',
            'first_installment_date' => 'required|date|after_or_equal:purchase_date',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
        ]);

        try {
            \DB::beginTransaction();

            // Calculate new values
            $remainingBalance = $request->total_price - $request->advance_payment;
            $monthlyInstallment = Purchase::calculateMonthlyInstallment(
                $request->total_price,
                $request->advance_payment,
                $request->installment_months
            );

            $lastInstallmentDate = Carbon::parse($request->first_installment_date)
                ->addMonths($request->installment_months - 1);

            // Update purchase
            $purchase->update([
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
                'status' => 'active', // Reset status to active
            ]);

            // Delete ALL previous installments (both paid and pending) and recreate
            $purchase->installments()->delete();
            $this->createInstallmentSchedule($purchase, $request->recovery_officer_id);

            \DB::commit();

            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase updated successfully');

        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->with('error', 'Error updating purchase: ' . $e->getMessage())
                ->withInput();
        }
    }

    // NEW: Destroy method
    public function destroy(Purchase $purchase)
    {
        try {
            \DB::beginTransaction();

            // Check if any installments are paid
            $paidInstallments = $purchase->installments()->where('status', 'paid')->count();

            // Delete all pending installments first
            $purchase->installments()->delete();

            // Delete the purchase
            $purchase->delete();

            \DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase deleted successfully.'
                ]);
            }

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted successfully.');

        } catch (\Exception $e) {
            \DB::rollback();

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting purchase: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('purchases.index')
                ->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }

    private function createInstallmentSchedule(Purchase $purchase, $recoveryOfficerId)
    {
        $currentDate = Carbon::parse($purchase->first_installment_date);
        $remainingBalance = $purchase->remaining_balance;

        for ($i = 1; $i <= $purchase->installment_months; $i++) {
            $dueDate = $currentDate->copy()->addMonths($i - 1);

            $installmentAmount = $purchase->monthly_installment;

            // Calculate balance for this installment
            if ($i == $purchase->installment_months) {
                // Last installment takes remaining balance
                $installmentAmount = $remainingBalance;
                $newBalance = 0;
            } else {
                $newBalance = $remainingBalance - $installmentAmount;
            }

            Installment::create([
                'customer_id' => $purchase->customer_id,
                'purchase_id' => $purchase->id,
                'date' => null,
                'due_date' => $dueDate,
                'receipt_no' => null,
                'pre_balance' => $remainingBalance,
                'installment_amount' => $installmentAmount,
                'discount' => 0,
                'balance' => $newBalance,
                'fine_amount' => 0,
                'status' => 'pending',
                'recovery_officer_id' => $recoveryOfficerId,
                'remarks' => "Installment $i of {$purchase->installment_months}",
            ]);

            $remainingBalance = $newBalance;
        }
    }

    public function getInstallmentDetails($installmentId)
    {
        $installment = Installment::with(['customer', 'officer', 'purchase'])->findOrFail($installmentId);

        // Generate next receipt number
        $lastReceipt = Installment::where('receipt_no', '!=', null)
            ->orderBy('id', 'desc')
            ->first();

        $nextReceiptNumber = 'R-' . str_pad(
            ($lastReceipt ? intval(substr($lastReceipt->receipt_no, 2)) + 1 : 1001),
            4, '0', STR_PAD_LEFT
        );

        return response()->json([
            'receipt_no' => $nextReceiptNumber,
            'installment_amount' => $installment->installment_amount,
            'recovery_officer_id' => $installment->recovery_officer_id,
            'recovery_officer_name' => $installment->officer?->name ?? 'N/A',
            'customer_name' => $installment->customer->name,
            'due_date' => $installment->due_date->format('d/m/Y'),
            'remarks' => "Payment for installment due on " . $installment->due_date->format('d/m/Y')
        ]);
    }

    public function processPayment(Request $request, Purchase $purchase)
    {
        $request->validate([
            'installment_id' => 'required|exists:installments,id',
            'payment_date' => 'required|date',
            // 'receipt_no' => 'required|string|unique:installments,receipt_no',
            'payment_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
            'payment_method' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $installment = Installment::findOrFail($request->installment_id);

        // Calculate fine if overdue
        $fine = $installment->calculateFine();

        // Calculate new balance after payment
        $totalPayment = $request->payment_amount - ($request->discount ?? 0);
        $newBalance = max(0, $installment->pre_balance - $totalPayment);

        // Update installment
        $installment->update([
            'date' => $request->payment_date,
            'receipt_no' => $request->receipt_no,
            'installment_amount' => $request->payment_amount,
            'discount' => $request->discount ?? 0,
            'balance' => $newBalance,
            'fine_amount' => $fine,
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'recovery_officer_id' => $request->recovery_officer_id,
            'remarks' => $request->remarks,
        ]);

        // Update subsequent installments' pre_balance
        $this->updateSubsequentInstallments($purchase, $installment, $newBalance);

        // Check if all installments are paid
        $remainingInstallments = $purchase->installments()->where('status', 'pending')->count();
        if ($remainingInstallments == 0) {
            $purchase->update(['status' => 'completed']);
        }

        // Update customer defaulter status
        $customer = $purchase->customer;
        $isDefaulter = $customer->installments()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->exists();

        $customer->update(['is_defaulter' => $isDefaulter]);

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Payment processed successfully');
    }

    // Helper method to update subsequent installments
    private function updateSubsequentInstallments(Purchase $purchase, Installment $paidInstallment, $newBalance)
    {
        $subsequentInstallments = $purchase->installments()
            ->where('due_date', '>', $paidInstallment->due_date)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->get();

        $currentBalance = $newBalance;

        foreach ($subsequentInstallments as $installment) {
            $installment->update(['pre_balance' => $currentBalance]);
            $currentBalance = max(0, $currentBalance - $installment->installment_amount);
        }
    }
}