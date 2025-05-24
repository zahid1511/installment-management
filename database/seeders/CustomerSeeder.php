<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'account_no' => '2163320',
                'name' => 'Zahid Ur Rehman',
                'father_name' => 'Rehman Ali',
                'residential_type' => 'Personal',
                'occupation' => 'Salesman',
                'residence' => 'Mohalla Kona Kho Nazd Mushtaq Gujar Bashtnot Kot Ranjeet Mandiyali road gujranwala road SKP (Zati B B)',
                'office_address' => 'Italian food Nazd Dill Lagi Chok Sargudha road SKP',
                'mobile_1' => '03014773641',
                'mobile_2' => '03017654321',
                'nic' => '35404-0076027-7',
                'gender' => 'male',
                'is_defaulter' => false,
            ],
            [
                'account_no' => '2163321',
                'name' => 'Muhammad Ahmad',
                'father_name' => 'Khalil Ahmad',
                'residential_type' => 'Personal',
                'occupation' => 'Engineer',
                'residence' => 'Street 5, Block B, Johar Town, Lahore',
                'office_address' => 'Tech Solutions, DHA Phase 5',
                'mobile_1' => '03216549873',
                'mobile_2' => null,
                'nic' => '35202-1234567-1',
                'gender' => 'male',
                'is_defaulter' => false,
            ],
            [
                'account_no' => '2163322',
                'name' => 'Fatima Bibi',
                'father_name' => 'Abdul Majeed',
                'residential_type' => 'Personal',
                'occupation' => 'Teacher',
                'residence' => 'House 25, Sector B-17, Islamabad',
                'office_address' => 'Government Girls School, I-9',
                'mobile_1' => '03345678912',
                'mobile_2' => '03129876543',
                'nic' => '61101-5432168-6',
                'gender' => 'female',
                'is_defaulter' => false,
            ]
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }
    }
}