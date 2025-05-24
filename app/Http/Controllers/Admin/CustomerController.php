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
        $customers = Customer::with(['purchases', 'installments'])
            ->latest()
            ->paginate(10);
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
            'account_no' => 'required|string|max:255|unique:customers,account_no',
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'residential_type' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'residence' => 'nullable|string',
            'office_address' => 'nullable|string',
            'mobile_1' => 'required|string|max:20',
            'mobile_2' => 'nullable|string|max:20',
            'nic' => 'required|string|max:20|unique:customers,nic',
            'gender' => 'nullable|in:male,female',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('backend/img/customers'), $imageName);
            $validated['image'] = $imageName;
        }

        // Set default defaulter status
        $validated['is_defaulter'] = false;

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
            'account_no' => 'required|string|max:255|unique:customers,account_no,' . $customer->id,
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'residential_type' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'residence' => 'nullable|string',
            'office_address' => 'nullable|string',
            'mobile_1' => 'required|string|max:20',
            'mobile_2' => 'nullable|string|max:20',
            'nic' => 'required|string|max:20|unique:customers,nic,' . $customer->id,
            'gender' => 'nullable|in:male,female',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($customer->image && file_exists(public_path('backend/img/customers/' . $customer->image))) {
                unlink(public_path('backend/img/customers/' . $customer->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('backend/img/customers'), $imageName);
            $validated['image'] = $imageName;
        }

        $customer->update($validated);

        // Update defaulter status based on current installments
        $isDefaulter = $customer->installments()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->exists();
        
        $customer->update(['is_defaulter' => $isDefaulter]);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    // Delete customer
    public function destroy(Customer $customer)
    {
        // Check if customer has any purchases
        if ($customer->purchases()->exists()) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer with purchase history.');
        }

        // Check if customer has any guarantors
        if ($customer->guarantors()->exists()) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer with guarantors. Remove guarantors first.');
        }

        // Delete customer image if exists
        if ($customer->image && file_exists(public_path('backend/img/customers/' . $customer->image))) {
            unlink(public_path('backend/img/customers/' . $customer->image));
        }

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

    // API method to get customer summary
    public function getSummary(Customer $customer)
    {
        return response()->json($customer->getSummary());
    }

    // Bulk update defaulter status
    public function updateDefaulterStatus()
    {
        $defaulterIds = Customer::whereHas('installments', function($query) {
            $query->where('status', 'pending')
                  ->where('due_date', '<', now());
        })->pluck('id');

        // Mark defaulters
        Customer::whereIn('id', $defaulterIds)->update(['is_defaulter' => true]);
        
        // Clear non-defaulters
        Customer::whereNotIn('id', $defaulterIds)->update(['is_defaulter' => false]);

        return response()->json([
            'message' => 'Defaulter status updated successfully',
            'defaulters_count' => $defaulterIds->count()
        ]);
    }
}