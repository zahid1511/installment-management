<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'company',
        'model',
        'serial_no',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
