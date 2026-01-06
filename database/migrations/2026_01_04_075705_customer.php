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
        if (!Schema::hasTable('customers')) {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('phone_no_one')->nullable(true);
            $table->string('other_details')->nullable(true);
            $table->timestamps();
        });
    } 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
