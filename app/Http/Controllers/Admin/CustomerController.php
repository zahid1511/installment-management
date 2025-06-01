<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    // Display all customers - Updated for DataTables
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::with(['purchases', 'installments'])->select('customers.*');
            
            return DataTables::of($customers)
                ->addColumn('photo', function ($customer) {
                    if ($customer->image) {
                        return '<img src="' . asset('backend/img/customers/' . $customer->image) . '" alt="Customer Photo" width="50" height="50" style="object-fit: cover; border-radius: 50%;">';
                    } else {
                        return '<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1rem; font-weight: 500;">' . strtoupper(substr($customer->name, 0, 2)) . '</div>';
                    }
                })
                ->addColumn('name_with_father', function ($customer) {
                    $html = '<div>' . $customer->name . '</div>';
                    if ($customer->father_name) {
                        $html .= '<small class="text-muted">' . $customer->father_name . '</small>';
                    }
                    return $html;
                })
                ->addColumn('mobile_numbers', function ($customer) {
                    $html = '<div>' . $customer->mobile_1 . '</div>';
                    if ($customer->mobile_2) {
                        $html .= '<small class="text-muted">' . $customer->mobile_2 . '</small>';
                    }
                    return $html;
                })
                ->addColumn('purchases_count', function ($customer) {
                    $count = $customer->purchases->count();
                    return '<span class="badge badge-info">' . $count . '</span>';
                })
                ->addColumn('total_amount', function ($customer) {
                    $totalAmount = $customer->purchases->sum('total_price');
                    return 'Rs. ' . number_format($totalAmount, 0);
                })
                ->addColumn('paid_amount', function ($customer) {
                    $totalAdvance = $customer->purchases->sum('advance_payment');
                    $totalPaid = $totalAdvance + $customer->installments()->where('status', 'paid')->sum('installment_amount');
                    return 'Rs. ' . number_format($totalPaid, 0);
                })
                ->addColumn('balance', function ($customer) {
                    $totalAmount = $customer->purchases->sum('total_price');
                    $totalAdvance = $customer->purchases->sum('advance_payment');
                    $totalPaid = $totalAdvance + $customer->installments()->where('status', 'paid')->sum('installment_amount');
                    $remainingBalance = $totalAmount - $totalPaid;
                    return 'Rs. ' . number_format($remainingBalance, 0);
                })
                ->addColumn('status', function ($customer) {
                    $totalPurchases = $customer->purchases->count();
                    $totalAmount = $customer->purchases->sum('total_price');
                    $totalAdvance = $customer->purchases->sum('advance_payment');
                    $totalPaid = $totalAdvance + $customer->installments()->where('status', 'paid')->sum('installment_amount');
                    $remainingBalance = $totalAmount - $totalPaid;
                    $isDefaulter = $customer->installments()->where('status', 'pending')->where('due_date', '<', now())->exists();
                    
                    if ($totalPurchases == 0) {
                        return '<span class="badge badge-secondary">No Purchases</span>';
                    } elseif ($remainingBalance == 0) {
                        return '<span class="badge badge-success">Completed</span>';
                    } elseif ($isDefaulter) {
                        return '<span class="badge badge-danger">Defaulter</span>';
                    } else {
                        return '<span class="badge badge-primary">Active</span>';
                    }
                })
                ->addColumn('actions', function ($customer) {
                    $totalPurchases = $customer->purchases->count();
                    
                    return '
                        <div class="btn-group" role="group">
                            <a href="' . route('customers.statement', $customer->id) . '" class="btn btn-sm btn-info" title="View Statement">
                                <i class="fa fa-file-text"></i>
                            </a>
                            <a href="' . route('customers.edit', $customer->id) . '" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete(' . $customer->id . ', \'' . addslashes($customer->name) . '\', ' . $totalPurchases . ')" class="btn btn-sm btn-danger" title="Delete Customer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['photo', 'name_with_father', 'mobile_numbers', 'purchases_count', 'status', 'actions'])
                ->make(true);
        }
        
        return view('customers.index');
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
        try {
            \DB::beginTransaction();
            
            // Delete customer image if exists
            if ($customer->image && file_exists(public_path('backend/img/customers/' . $customer->image))) {
                unlink(public_path('backend/img/customers/' . $customer->image));
            }
            
            // Delete guarantor images and guarantors
            foreach ($customer->guarantors as $guarantor) {
                if ($guarantor->image && file_exists(public_path($guarantor->image))) {
                    unlink(public_path($guarantor->image));
                }
            }
            $customer->guarantors()->delete();
            
            // Delete all installments for this customer
            $customer->installments()->delete();
            
            // Delete all purchases for this customer
            $customer->purchases()->delete();
            
            // Finally delete the customer
            $customer->delete();
            
            \DB::commit();
            
            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer and all related data deleted successfully.'
                ]);
            }
            
            return redirect()->route('customers.index')
                ->with('success', 'Customer and all related data deleted successfully.');
                
        } catch (\Exception $e) {
            \DB::rollback();
            
            // Check if request is AJAX
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting customer: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('customers.index')
                ->with('error', 'Error deleting customer: ' . $e->getMessage());
        }
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