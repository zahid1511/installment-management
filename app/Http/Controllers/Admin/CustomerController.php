<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    // Display all customers
    public function index()
    {
        $customers = Customer::latest()->paginate(5);
        return view('customers.index', compact('customers'));
    }

    // Show create form
    public function create()
    {
        return view('customers.create');
    }

    // Store customer data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_no' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string',
            'residential_type' => 'nullable|string',
            'occupation' => 'nullable|string',
            'residence' => 'nullable|string',
            'office_address' => 'nullable|string',
            'mobile_1' => 'required|string',
            'mobile_2' => 'nullable|string',
            'nic' => 'required|string',
            'gender' => 'nullable|string',
            'total_price' => 'nullable|numeric',
            'installment_amount' => 'nullable|numeric',
            'installments' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'is_defaulter' => 'nullable|boolean',
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('backend/img/customers'), $imageName);
            $validated['image'] = $imageName;
        }

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    // Show edit form
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'account_no' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string',
            'residential_type' => 'nullable|string',
            'occupation' => 'nullable|string',
            'residence' => 'nullable|string',
            'office_address' => 'nullable|string',
            'mobile_1' => 'required|string',
            'mobile_2' => 'nullable|string',
            'nic' => 'required|string',
            'gender' => 'nullable|string',
            'total_price' => 'nullable|numeric',
            'installment_amount' => 'nullable|numeric',
            'installments' => 'nullable|numeric',
            'advance' => 'nullable|numeric',
            'balance' => 'nullable|numeric',
            'is_defaulter' => 'nullable|boolean',
        ]);
        if ($request->hasFile('image')) {
            // delete old image
            if ($customer->image && file_exists(public_path('backend/img/customers/' . $customer->image))) {
                unlink(public_path('backend/img/customers/' . $customer->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('backend/img/customers'), $imageName);
            $validated['image'] = $imageName;
        }

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    // Delete customer
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function statement(Customer $customer)
    {
        // Load related data through proper relationships
        $customer->load([
            'guarantors',
            'purchases.product',
            'purchases.installments' => function ($query) {
                $query->orderBy('due_date', 'asc');
            }
        ]);

        return view('customers.statement', compact('customer'));
    }
}
