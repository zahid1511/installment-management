<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guarantor;
use App\Models\Customer;

class GuarantorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get customers from database
        $customers = Customer::all();
        
        // Create 2 guarantors for each customer
        foreach ($customers as $customer) {
            // First guarantor
            Guarantor::create([
                'customer_id' => $customer->id,
                'name' => 'Khurram Shahzad',
                'father_name' => 'Shahzad Ali',
                'relation' => 'Friend',
                'nic' => '35404-1234567-1',
                'phone' => '03023337137',
                'residence_address' => 'Mohalla Mahryan Wala Nazd Dakhana Bypass wala road SKP (Zati B-B)',
                'office_address' => 'Mian Taiyyar Factory Nazd Joyo wala more Jura Mills SKP',
                'occupation' => 'Assistant Foreman',
                'guarantor_no' => 1,
            ]);

            // Second guarantor
            Guarantor::create([
                'customer_id' => $customer->id,
                'name' => 'Waqas Naseer',
                'father_name' => 'Naseer ul din anjum',
                'relation' => 'Friend',
                'nic' => '35404-2345678-2',
                'phone' => '03234744067',
                'residence_address' => 'Mohalla salahadin road nazd param Makhan sweet Gali sharjee Sirait SKP (zati B-B)',
                'office_address' => 'Bau ge and bait pizza burger point civil Quawatar (Rent 2 sal se)',
                'occupation' => 'Zatkarbobar',
                'guarantor_no' => 2,
            ]);
        }
    }
}