<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guarantor;
use App\Models\Customer;
use Illuminate\Http\Request;

class GuarantorController extends Controller
{
    public function index()
    {
        $guarantors = Guarantor::with('customer')->get();
        return view('guarantors.index', compact('guarantors'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('guarantors.create', compact('customers'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required',
            'father_name' => 'required',
            'relation' => 'required',
            'nic' => 'required',
            'phone' => 'required',
            'residence_address' => 'required',
            'guarantor_no' => 'required|in:1,2',
        ]);

        Guarantor::create($request->all());
        // logger($guarantor); // Writes to storage/logs/laravel.log
        return redirect()->route('guarantors.index')->with('success', 'Guarantor added successfully');
    }

    public function show(Guarantor $guarantor)
{
    return view('guarantors.index', compact('guarantor'));
}


    public function edit(Guarantor $guarantor)
    {
        $customers = Customer::all();
        return view('guarantors.edit', compact('guarantor', 'customers'));
    }

    public function update(Request $request, Guarantor $guarantor)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required',
            'father_name' => 'required',
            'relation' => 'required',
            'nic' => 'required',
            'phone' => 'required',
            'residence_address' => 'required',
            'guarantor_no' => 'required|in:1,2',
        ]);

        $guarantor->update($request->all());
        return redirect()->route('guarantors.index')->with('success', 'Guarantor updated successfully');
    }

    public function destroy(Guarantor $guarantor)
    {
        $guarantor->delete();
        return redirect()->route('guarantors.index')->with('success', 'Guarantor deleted successfully');
    }
}
