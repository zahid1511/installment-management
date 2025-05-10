<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RecoveryOfficer;

class RecoveryOfficersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officers = [
            [
                'name' => 'Rana Ishtihar',
                'employee_id' => 'RO001',
                'phone' => '0301-2345678',
                'email' => 'rana.ishtihar@company.com',
                'address' => 'Muhala Kona Kho Nazd Mushtaq Gujar Bashnoil Kot Ranjeet Mandiyali road gujarwala road SKP (Zati B.B)',
                'is_active' => true,
            ],
            [
                'name' => 'Waseem Abbas',
                'employee_id' => 'RO002',
                'phone' => '0302-3456789',
                'email' => 'waseem.abbas@company.com',
                'address' => 'New City, Sargodha Road, Main Bazaar',
                'is_active' => true,
            ],
            [
                'name' => 'Waris Ali',
                'employee_id' => 'RO003',
                'phone' => '0303-4567890',
                'email' => 'waris.ali@company.com',
                'address' => 'Near Railway Station, GT Road',
                'is_active' => true,
            ],
            [
                'name' => 'Raza Abbas',
                'employee_id' => 'RO004',
                'phone' => '0304-5678901',
                'email' => 'raza.abbas@company.com',
                'address' => 'Civil Lines, Near DCO Office',
                'is_active' => true,
            ],
            [
                'name' => 'M. Asif Khan',
                'employee_id' => 'RO005',
                'phone' => '0305-6789012',
                'email' => 'm.asif@company.com',
                'address' => 'Model Town, Block A',
                'is_active' => true,
            ],
            [
                'name' => 'Faisal Majeed',
                'employee_id' => 'RO006',
                'phone' => '0306-7890123',
                'email' => 'faisal.majeed@company.com',
                'address' => 'Satellite Town, Main Bazar',
                'is_active' => true,
            ],
            [
                'name' => 'Shoaib Hussain',
                'employee_id' => 'RO007',
                'phone' => '0307-8901234',
                'email' => 'shoaib.hussain@company.com',
                'address' => 'Dhobi Mohalla, Near Masjid',
                'is_active' => true,
            ],
            [
                'name' => 'Waseem Akram',
                'employee_id' => 'RO008',
                'phone' => '0308-9012345',
                'email' => 'waseem.akram@company.com',
                'address' => 'University Road, Block B',
                'is_active' => true,
            ],
            [
                'name' => 'Faiz Khurshid',
                'employee_id' => 'RO009',
                'phone' => '0309-0123456',
                'email' => 'faiz.khurshid@company.com',
                'address' => 'Industrial Area, Phase 2',
                'is_active' => true,
            ],
            [
                'name' => 'Former Officer',
                'employee_id' => 'RO010',
                'phone' => '0310-1234567',
                'email' => 'former.officer@company.com',
                'address' => 'Old City Area',
                'is_active' => false, // Inactive officer
            ],
        ];

        foreach ($officers as $officer) {
            RecoveryOfficer::create($officer);
        }
    }
}