<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'date',
        'receipt_no',
        'pre_balance',
        'installment_amount',
        'discount',
        'balance',
        'fine_type',
        'recovery_officer',
        'remarks',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
