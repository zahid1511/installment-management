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
            
            // Basic Customer Information
            $table->string('account_no')->unique(); // Added unique constraint
            $table->string('name');
            $table->string('father_name')->nullable(); // Made nullable to match controller
            $table->string('residential_type')->nullable(); // Made nullable
            $table->string('occupation')->nullable(); // Made nullable
            
            // Address Information
            $table->text('residence')->nullable(); // Made nullable
            $table->text('office_address')->nullable();
            
            // Contact Information
            $table->string('mobile_1', 20); // Added length constraint
            $table->string('mobile_2', 20)->nullable(); // Added length constraint
            $table->string('nic', 20)->unique(); // Added unique constraint and length
            
            // Personal Information
            $table->enum('gender', ['male', 'female'])->nullable(); // Changed to enum for data integrity
            $table->string('image')->nullable(); // Added image field
            
            // Status Information
            $table->boolean('is_defaulter')->default(false);
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('account_no');
            $table->index('nic');
            $table->index('is_defaulter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};