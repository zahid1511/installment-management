<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guarantor extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'father_name',
        'relation',
        'nic',
        'phone',
        'residence_address',
        'office_address',
        'occupation',
        'guarantor_no',
    ];

    // Add casts for proper data types
    protected $casts = [
        'guarantor_no' => 'integer',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('guarantor_no', 1);
    }

    public function scopeSecondary($query)
    {
        return $query->where('guarantor_no', 2);
    }

    // Accessors
    public function getGuarantorTypeAttribute()
    {
        return $this->guarantor_no == 1 ? 'Primary' : 'Secondary';
    }

    // Static methods for checking
    public static function hasGuarantor($customerId, $guarantorNo)
    {
        return self::where('customer_id', $customerId)
                   ->where('guarantor_no', $guarantorNo)
                   ->exists();
    }
}