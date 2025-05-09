<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\Installment;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index()
    {
        $installments = Installment::with('customer')->get();
        return view('installments.index', compact('installments'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('installments.create', compact('customers'));
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
            'recovery_officer' => 'required',
        ]);

        Installment::create($request->all());
        return redirect()->route('installments.index')->with('success', 'Installment added successfully');
    }

    public function edit(Installment $installment)
    {
        $customers = Customer::all();
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
            'recovery_officer' => 'required',
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