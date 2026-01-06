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
        if (!Schema::hasTable('settings')) {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('cmpny_name');
            $table->string('cmpny_email');
            $table->string('cmpny_phone')->nullable(true);
            $table->string('cmpny_address')->nullable(true);
            $table->string('cmpny_other')->nullable(true);
            $table->string('tax_name');
            $table->decimal('tax_perc')->nullable(true);
            $table->string('set_currency')->nullable(true);
            $table->timestamps();
        });
    } 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
