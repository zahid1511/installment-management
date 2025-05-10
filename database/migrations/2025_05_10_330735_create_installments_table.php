<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('receipt_no')->nullable();
            $table->decimal('pre_balance', 10, 2);
            $table->decimal('installment_amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->string('status')->default('pending'); // paid, pending, overdue
            $table->string('payment_method')->nullable();
            $table->string('fine_type')->nullable();
            $table->foreignId('recovery_officer_id')->nullable()->constrained('recovery_officers')->onDelete('set null');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('installments');
    }

};
