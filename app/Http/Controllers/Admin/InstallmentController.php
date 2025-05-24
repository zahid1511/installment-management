<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\RecoveryOfficer;
use App\Models\Installment;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index()
    {
        $installments = Installment::with('customer')->orderBy('id', 'desc')->paginate(10);
        return view('installments.index', compact('installments'));
    }

    public function getCustomerInstallmentInfo($id)
    {
        $customer = Customer::findOrFail($id);
        $latestInstallment = $customer->installments()->latest()->first();

        // Generate next receipt number (example: R-1001)
        $lastReceipt = Installment::latest()->first();
        $nextReceiptNumber = 'R-' . str_pad(($lastReceipt ? intval(substr($lastReceipt->receipt_no, 2)) + 1 : 1), 4, '0', STR_PAD_LEFT);

        return response()->json([
            'installment_amount' => $latestInstallment->installment_amount ?? 0,
            'pre_balance' => $latestInstallment->balance ?? 0,
            'balance' => 0, // Adjust if needed
            'receipt_no' => $nextReceiptNumber,
        ]);
    }

    public function create()
    {
        $customers = Customer::all();
        $recoveryOfficers = RecoveryOfficer::all();
        return view('installments.create', compact('customers','recoveryOfficers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'receipt_no' => 'required',
            'pre_balance' => 'required|numeric',
            'installment_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'balance' => 'required|numeric',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
        ]);

        Installment::create($request->all());

        return redirect()->route('installments.index')->with('success', 'Installment added successfully');
    }

    public function edit(Installment $installment)
    {
        $customers = Customer::all();
        // dd($customers);
        return view('installments.edit', compact('installment', 'customers'));
    }

    public function update(Request $request, Installment $installment)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'receipt_no' => 'required',
            'pre_balance' => 'required|numeric',
            'installment_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'balance' => 'required|numeric',
            'recovery_officer_id' => 'required|exists:recovery_officers,id',
        ]);

        $installment->update($request->all());
        return redirect()->route('installments.index')->with('success', 'Installment updated successfully');
    }

    public function destroy(Installment $installment)
    {
        $installment->delete();
        return redirect()->route('installments.index')->with('success', 'Installment deleted successfully');
    }
}