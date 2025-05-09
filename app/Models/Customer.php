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
        'total_price',
        'installment_amount',
        'installments',
        'advance',
        'balance',
        'is_defaulter',
    ];

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
