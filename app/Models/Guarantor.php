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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
