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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('account_no');
            $table->string('name');
            $table->string('father_name');
            $table->string('residential_type');
            $table->string('occupation');
            $table->text('residence');
            $table->text('office_address')->nullable();
            $table->string('mobile_1');
            $table->string('mobile_2')->nullable();
            $table->string('nic');
            $table->string('gender');
            $table->decimal('total_price', 10, 2);
            $table->decimal('installment_amount', 10, 2);
            $table->integer('installments');
            $table->decimal('advance', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->boolean('is_defaulter')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
