<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->date('purchase_date');
            $table->decimal('total_price', 10, 2);
            $table->decimal('advance_payment', 10, 2);
            $table->decimal('remaining_balance', 10, 2);
            $table->integer('installment_months');
            $table->decimal('monthly_installment', 10, 2);
            $table->date('first_installment_date');
            $table->date('last_installment_date');
            $table->string('status')->default('active'); // active, completed, defaulted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};  