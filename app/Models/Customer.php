<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_no',
        'name',
        'father_name',
        'residential_type',
        'occupation',
        'residence',
        'office_address',
        'mobile_1',
        'mobile_2',
        'nic',
        'gender',
        'image',
        'is_defaulter', // Keep this as it's a business status
    ];

    // Relationships
    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
    
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // ===== CALCULATED PROPERTIES =====
    
    /**
     * Get total amount of all purchases
     */
    public function getTotalPurchaseAmountAttribute()
    {
        return $this->purchases()->sum('total_price');
    }

    /**
     * Get total advance payments made
     */
    public function getTotalAdvanceAttribute()
    {
        return $this->purchases()->sum('advance_payment');
    }

    /**
     * Get total remaining balance across all purchases
     */
    public function getTotalBalanceAttribute()
    {
        return $this->purchases()->sum('remaining_balance') - 
               $this->installments()->where('status', 'paid')->sum('installment_amount');
    }

    /**
     * Get total monthly installments (sum of all active purchases)
     */
    public function getTotalMonthlyInstallmentAttribute()
    {
        return $this->purchases()
            ->where('status', 'active')
            ->sum('monthly_installment');
    }

    /**
     * Get total number of pending installments
     */
    public function getTotalPendingInstallmentsAttribute()
    {
        return $this->installments()->where('status', 'pending')->count();
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAmountAttribute()
    {
        return $this->getTotalAdvanceAttribute() + 
               $this->installments()->where('status', 'paid')->sum('installment_amount');
    }

    /**
     * Get overdue installments count
     */
    public function getOverdueInstallmentsCountAttribute()
    {
        return $this->installments()
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
    }

    /**
     * Check if customer is defaulter (calculated)
     */
    public function getIsCalculatedDefaulterAttribute()
    {
        return $this->getOverdueInstallmentsCountAttribute() > 0;
    }

    /**
     * Get customer summary for dashboard
     */
    public function getSummary()
    {
        return [
            'total_purchases' => $this->purchases()->count(),
            'total_amount' => $this->total_purchase_amount,
            'total_advance' => $this->total_advance,
            'total_paid' => $this->total_paid_amount,
            'remaining_balance' => $this->total_balance,
            'monthly_installment' => $this->total_monthly_installment,
            'pending_installments' => $this->total_pending_installments,
            'overdue_count' => $this->overdue_installments_count,
            'is_defaulter' => $this->is_calculated_defaulter,
        ];
    }

    /**
     * Scope for defaulters
     */
    public function scopeDefaulters($query)
    {
        return $query->whereHas('installments', function($q) {
            $q->where('status', 'pending')
              ->where('due_date', '<', now());
        });
    }

    /**
     * Scope for active customers (with pending payments)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('purchases', function($q) {
            $q->where('status', 'active');
        });
    }
}