<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\RecoveryOfficer;
use App\Models\Installment;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Installment::with(['customer', 'purchase.product', 'officer']);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status == 'overdue') {
                $query->where('status', 'pending')
                      ->where('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Order by: 1) Overdue first, 2) Then by due date ascending
        $installments = $query->orderByRaw("CASE
                                 WHEN status = 'pending' AND due_date < NOW() THEN 1
                                 WHEN status = 'pending' THEN 2
                                 ELSE 3
                               END")
                             ->orderBy('due_date', 'asc')
                             ->orderBy('id', 'asc')
                             ->paginate(50);

        return view('installments.index', compact('installments'));
    }

    public function getCustomerInstallmentInfo($id)
    {
        $customer = Customer::findOrFail($id);
        $latestInstallment = $customer->installments()->latest()->first();

        // Generate next receipt number (example: R-1001)
        $lastReceipt = Installment::whereNotNull('receipt_no')
                                 ->orderBy('id', 'desc')
                                 ->first();

        $nextReceiptNumber = 'R-' . str_pad(
            ($lastReceipt ? intval(substr($lastReceipt->receipt_no, 2)) + 1 : 1001),
            4, '0', STR_PAD_LEFT
        );

        return response()->json([
            'installment_amount' => $latestInstallment->installment_amount ?? 0,
            'pre_balance' => $latestInstallment->balance ?? 0,
            'balance' => 0, // Adjust if needed
            'receipt_no' => $nextReceiptNumber,
        ]);
    }

    /**
     * Remove create method since installments are auto-generated from purchases
     */
    // public function create() - REMOVED

    /**
     * Remove store method since installments are auto-generated from purchases
     */
    // public function store() - REMOVED

    public function edit(Installment $installment)
    {
        // Only allow editing of manual installments (not linked to purchases)
        if ($installment->purchase_id) {
            return redirect()->route('installments.index')
                ->with('error', 'Purchase-based installments cannot be edited directly. Use the purchase page to process payments.');
        }

        $customers = Customer::all();
        $recoveryOfficers = RecoveryOfficer::where('is_active', true)->get();

        return view('installments.edit', compact('installment', 'customers', 'recoveryOfficers'));
    }

    public function update(Request $request, Installment $installment)
    {
        // Only allow updating of manual installments (not linked to purchases)
        if ($installment->purchase_id) {
            return redirect()->route('installments.index')
                ->with('error', 'Purchase-based installments cannot be edited directly. Use the purchase page to process payments.');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'receipt_no' => 'required|unique:installments,receipt_no,' . $installment->id,
            'pre_balance' => 'required|numeric|min:0',
            'installment_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
            'payment_method' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $installment->update([
            'customer_id' => $request->customer_id,
            'date' => $request->date,
            'receipt_no' => $request->receipt_no,
            'pre_balance' => $request->pre_balance,
            'installment_amount' => $request->installment_amount,
            'discount' => $request->discount ?? 0,
            'balance' => $request->balance,
            'recovery_officer_id' => $request->recovery_officer_id,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks,
            'status' => 'paid', // Manual installments are typically paid
        ]);

        return redirect()->route('installments.index')
            ->with('success', 'Installment updated successfully');
    }

    public function destroy(Installment $installment)
    {
        // Only allow deletion of manual installments (not linked to purchases)
        if ($installment->purchase_id) {
            return redirect()->route('installments.index')
                ->with('error', 'Purchase-based installments cannot be deleted. They are part of the purchase agreement.');
        }

        $installment->delete();

        return redirect()->route('installments.index')
            ->with('success', 'Manual installment deleted successfully');
    }

    /**
     * Show overdue installments for recovery officers
     */
    public function overdueReport()
    {
        $overdueInstallments = Installment::with(['customer', 'purchase.product', 'officer'])
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();

        return view('installments.overdue', compact('overdueInstallments'));
    }

    /**
     * Show installments for a specific recovery officer
     */
    public function officerInstallments($officerId)
    {
        $officer = RecoveryOfficer::findOrFail($officerId);

        $installments = Installment::with(['customer', 'purchase.product'])
            ->where('recovery_officer_id', $officerId)
            ->orderBy('due_date')
            ->paginate(20);

        return view('installments.officer', compact('installments', 'officer'));
    }
}