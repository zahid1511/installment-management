<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\RecoveryOfficer; // Add this import

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'purchase_id',
        'date',
        'due_date',
        'receipt_no',
        'pre_balance',
        'installment_amount',
        'discount',
        'balance',
        'fine_amount',
        'fine_type',
        'recovery_officer_id', // Changed from recovery_officer
        'status',
        'payment_method',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Fix the relationship name from recoveryOfficer to officer
    public function officer()
    {
        return $this->belongsTo(RecoveryOfficer::class, 'recovery_officer_id');
    }

    // Check if installment is overdue
    public function isOverdue()
    {
        if ($this->status == 'paid') {
            return false;
        }
        
        // Check if due_date is in the past
        if ($this->due_date) {
            return Carbon::parse($this->due_date)->isPast();
        }
        
        return false;
    }

    // Calculate fine for overdue payment
    public function calculateFine($fineRate = 0.05)
    {
        if ($this->isOverdue()) {
            $daysLate = now()->diffInDays($this->due_date);
            return round($this->installment_amount * $fineRate * ceil($daysLate / 30), 2);
        }
        return 0;
    }

    // Accessor for getting recovery officer name (for backward compatibility)
    public function getRecoveryOfficerAttribute()
    {
        // First check if we have an officer from the relationship
        if ($this->officer) {
            return $this->officer->name;
        }
        
        // Fall back to the old recovery_officer field if it exists
        if (isset($this->attributes['recovery_officer'])) {
            return $this->attributes['recovery_officer'];
        }
        
        return null;
    }

}