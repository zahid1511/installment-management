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
        $guarantors = Guarantor::with('customer')->orderBy('customer_id')->orderBy('guarantor_no')->paginate(6);
        return view('guarantors.index', compact('guarantors'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('guarantors.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'nic' => 'required|string|unique:guarantors,nic|max:20',
            'phone' => 'required|string|max:20',
            'residence_address' => 'required|string',
            'office_address' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'guarantor_no' => 'required|in:1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('backend/img/guarantors'), $filename);
            $data['image'] = 'backend/img/guarantors/' . $filename;
        }

        // Check if guarantor number already exists for this customer
        $existingGuarantor = Guarantor::where('customer_id', $data['customer_id'])
            ->where('guarantor_no', $data['guarantor_no'])
            ->first();

        if ($existingGuarantor) {
            return back()->withErrors(['guarantor_no' => 'Guarantor number ' . $data['guarantor_no'] . ' already exists for this customer.'])
                ->withInput();
        }

        Guarantor::create($data);

        return redirect()->route('guarantors.index')->with('success', 'Guarantor added successfully');
    }

    public function show(Guarantor $guarantor)
    {
        $guarantor->load('customer');
        return view('guarantors.show', compact('guarantor'));
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
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'relation' => 'required|string|max:255',
            'nic' => 'required|string|max:20|unique:guarantors,nic,' . $guarantor->id,
            'phone' => 'required|string|max:20',
            'residence_address' => 'required|string',
            'office_address' => 'nullable|string',
            'occupation' => 'nullable|string|max:255',
            'guarantor_no' => 'required|in:1,2',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($guarantor->image && file_exists(public_path($guarantor->image))) {
                unlink(public_path($guarantor->image));
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('backend/img/guarantors'), $filename);
            $data['image'] = 'backend/img/guarantors/' . $filename;
        }

        // Check if guarantor number already exists for this customer (excluding current record)
        $existingGuarantor = Guarantor::where('customer_id', $data['customer_id'])
            ->where('guarantor_no', $data['guarantor_no'])
            ->where('id', '!=', $guarantor->id)
            ->first();

        if ($existingGuarantor) {
            return back()->withErrors(['guarantor_no' => 'Guarantor number ' . $data['guarantor_no'] . ' already exists for this customer.'])
                ->withInput();
        }

        $guarantor->update($data);

        return redirect()->route('guarantors.index')->with('success', 'Guarantor updated successfully');
    }

    public function destroy(Guarantor $guarantor)
    {
        // Delete image if it exists
        if ($guarantor->image && file_exists(public_path($guarantor->image))) {
            unlink(public_path($guarantor->image));
        }

        $guarantor->delete();
        return redirect()->route('guarantors.index')->with('success', 'Guarantor deleted successfully');
    }

    public function checkGuarantor(Request $request)
    {
        $customerId = $request->customer_id;
        $guarantorNo = $request->guarantor_no;
        $excludeId = $request->exclude_id;

        $query = Guarantor::where('customer_id', $customerId)
            ->where('guarantor_no', $guarantorNo);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }
}