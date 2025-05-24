<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            ProductsSeeder::class,
            GuarantorsSeeder::class,
            RecoveryOfficersSeeder::class,
            /*PurchasesSeeder::class,*/
        ]);
    }
}