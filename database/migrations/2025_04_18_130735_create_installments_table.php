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
        $table->date('date');
        $table->string('receipt_no');
        $table->decimal('pre_balance', 10, 2);
        $table->decimal('installment_amount', 10, 2);
        $table->decimal('discount', 10, 2)->default(0);
        $table->decimal('balance', 10, 2);
        $table->string('fine_type')->nullable();
        $table->string('recovery_officer');
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('installments');
}

};
