<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecoveryOfficer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'employee_id',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    // Get active recovery officers
    public static function active()
    {
        return self::where('is_active', true);
    }

    // Get installments count for this officer
    public function getInstallmentsCount()
    {
        return $this->installments()->count();
    }

    // Get total collected amount by this officer
    public function getTotalCollected()
    {
        return $this->installments()
            ->where('status', 'paid')
            ->sum('installment_amount');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}