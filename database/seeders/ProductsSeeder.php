<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'company' => 'XIAOMI',
                'model' => 'Redmi 10-A',
                'serial_no' => '869150067453400',
                'price' => 44400,
            ],
            [
                'company' => 'SAMSUNG',
                'model' => 'Galaxy A54',
                'serial_no' => '123456789012345',
                'price' => 89000,
            ],
            [
                'company' => 'VIVO',
                'model' => 'Y36',
                'serial_no' => '987654321098765',
                'price' => 67500,
            ],
            [
                'company' => 'OPPO',
                'model' => 'A78',
                'serial_no' => '456789123456789',
                'price' => 55000,
            ],
            [
                'company' => 'XIAOMI',
                'model' => 'Mi 11 Lite',
                'serial_no' => '789456123789456',
                'price' => 75000,
            ],
            [
                'company' => 'REALME',
                'model' => 'Narzo 50A',
                'serial_no' => '321654987321654',
                'price' => 35000,
            ],
            [
                'company' => 'SAMSUNG',
                'model' => 'Galaxy M54',
                'serial_no' => '654321789654321',
                'price' => 95000,
            ],
            [
                'company' => 'NOKIA',
                'model' => 'C32',
                'serial_no' => '159753486159753',
                'price' => 28000,
            ],
            [
                'company' => 'INFINIX',
                'model' => 'Hot 30i',
                'serial_no' => '753159864753159',
                'price' => 42000,
            ],
            [
                'company' => 'TECNO',
                'model' => 'Spark 10 Pro',
                'serial_no' => '864975312864975',
                'price' => 38500,
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}