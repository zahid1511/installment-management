<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecoveryOfficer;
use Illuminate\Http\Request;

class RecoveryOfficerController extends Controller
{
    public function index()
    {
        $officers = RecoveryOfficer::latest()->get();
        return view('recovery-officers.index', compact('officers'));
    }

    public function create()
    {
        return view('recovery-officers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:recovery_officers',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        RecoveryOfficer::create($request->all());

        return redirect()->route('recovery-officers.index')
            ->with('success', 'Recovery Officer added successfully.');
    }

    public function show(RecoveryOfficer $recoveryOfficer)
    {
        $statistics = [
            'total_installments' => $recoveryOfficer->getInstallmentsCount(),
            'total_collected' => $recoveryOfficer->getTotalCollected(),
            'recent_collections' => $recoveryOfficer->installments()
                ->where('status', 'paid')
                ->with(['customer', 'purchase.product'])
                ->latest('date')
                ->take(10)
                ->get(),
        ];

        return view('recovery-officers.show', compact('recoveryOfficer', 'statistics'));
    }

    public function edit(RecoveryOfficer $recoveryOfficer)
    {
        return view('recovery-officers.edit', compact('recoveryOfficer'));
    }

    public function update(Request $request, RecoveryOfficer $recoveryOfficer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255|unique:recovery_officers,employee_id,' . $recoveryOfficer->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $recoveryOfficer->update($request->all());

        return redirect()->route('recovery-officers.index')
            ->with('success', 'Recovery Officer updated successfully.');
    }

    public function destroy(RecoveryOfficer $recoveryOfficer)
    {
        // Check if officer has any installments
        if ($recoveryOfficer->installments()->exists()) {
            return redirect()->route('recovery-officers.index')
                ->with('error', 'Cannot delete Recovery Officer with existing installments.');
        }

        $recoveryOfficer->delete();

        return redirect()->route('recovery-officers.index')
            ->with('success', 'Recovery Officer deleted successfully.');
    }
}