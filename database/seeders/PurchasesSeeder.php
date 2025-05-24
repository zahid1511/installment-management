<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Installment;
use App\Models\RecoveryOfficer;
use Carbon\Carbon;

class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first 3 customers and 3 products for seeding
        $customers = Customer::take(3)->get();
        $products = Product::take(3)->get();
        
        foreach ($customers as $index => $customer) {
            if (!isset($products[$index])) continue;
            
            $product = $products[$index];
            $totalPrice = $product->price;
            $advancePayment = $customer->advance;
            $remainingBalance = $totalPrice - $advancePayment;
            $months = $customer->installments;
            $monthlyInstallment = round($remainingBalance / $months, 2);
            
            // Create purchase
            $purchase = Purchase::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'purchase_date' => Carbon::now()->subDays(rand(30, 180)),
                'total_price' => $totalPrice,
                'advance_payment' => $advancePayment,
                'remaining_balance' => $remainingBalance,
                'installment_months' => $months,
                'monthly_installment' => $monthlyInstallment,
                'first_installment_date' => Carbon::now()->subDays(rand(15, 90)),
                'last_installment_date' => Carbon::now()->addMonths($months - 1),
                'status' => 'active'
            ]);
            
            // Create installment schedule
            $this->createInstallmentSchedule($purchase);
        }
    }
    
    private function createInstallmentSchedule(Purchase $purchase)
    {
        $currentDate = Carbon::parse($purchase->first_installment_date);
        $remainingBalance = $purchase->remaining_balance;
        
        // Get active recovery officers
        $recoveryOfficers = RecoveryOfficer::active()->get();
        
        for ($i = 1; $i <= $purchase->installment_months; $i++) {
            $dueDate = $currentDate->copy()->addMonths($i - 1);
            
            // Calculate balance for this installment
            $newBalance = $remainingBalance - $purchase->monthly_installment;
            if ($i == $purchase->installment_months) {
                $installmentAmount = $remainingBalance; // Use local variable
                $newBalance = 0;
            } else {
                $installmentAmount = $purchase->monthly_installment;
            }
            
            $status = 'pending';
            $paymentDate = null;
            $receiptNo = null;
            $recoveryOfficerId = null;
            
            // Mark some installments as paid
            if ($dueDate < Carbon::now() && rand(0, 100) < 70) { // 70% chance of being paid if due date has passed
                $status = 'paid';
                $paymentDate = $dueDate->copy()->addDays(rand(0, 15));
                $receiptNo = 'REC-' . str_pad($purchase->id * 1000 + $i, 6, '0', STR_PAD_LEFT);
                
                // Randomly select a recovery officer
                if ($recoveryOfficers->isNotEmpty()) {
                    $recoveryOfficerId = $recoveryOfficers->random()->id;
                }
            }
            
            // Create installment record
            $installmentData = [
                'customer_id' => $purchase->customer_id,
                'purchase_id' => $purchase->id,
                'date' => $paymentDate,
                'due_date' => $dueDate,
                'receipt_no' => $receiptNo,
                'pre_balance' => $remainingBalance,
                'installment_amount' => $installmentAmount ,
                'discount' => 0,
                'balance' => $newBalance,
                'fine_amount' => 0,
                'status' => $status,
                'payment_method' => $status == 'paid' ? 'cash' : null,
                'remarks' => "Installment $i of {$purchase->installment_months}",
            ];
            
            // Add recovery officer ID if available
            if ($recoveryOfficerId) {
                $installmentData['recovery_officer_id'] = $recoveryOfficerId;
            }
            
            Installment::create($installmentData);
            
            $remainingBalance = $newBalance;
        }
    }
}