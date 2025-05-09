<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('father_name');
            $table->string('relation');
            $table->string('nic');
            $table->string('phone');
            $table->text('residence_address');
            $table->text('office_address')->nullable();
            $table->string('occupation')->nullable();
            $table->integer('guarantor_no'); // 1 or 2
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
