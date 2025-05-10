<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'purchase_date',
        'total_price',
        'advance_payment',
        'remaining_balance',
        'installment_months',
        'monthly_installment',
        'first_installment_date',
        'last_installment_date',
        'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'first_installment_date' => 'date',
        'last_installment_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    // Calculate monthly installment
    public static function calculateMonthlyInstallment($totalPrice, $advancePayment, $months)
    {
        $remainingBalance = $totalPrice - $advancePayment;
        return round($remainingBalance / $months, 2);
    }

    // Calculate remaining balance
    public function getRemainingBalance()
    {
        $totalPaid = $this->installments()->where('status', 'paid')->sum('installment_amount') + $this->advance_payment;
        return $this->total_price - $totalPaid;
    }

    // Check if purchase is defaulted (missed payments)
    public function isDefaulted()
    {
        // Check if there are any overdue installments
        $overdueInstallments = $this->installments()
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->exists();
        
        return $overdueInstallments;
    }
}