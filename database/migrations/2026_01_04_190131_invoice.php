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
         if (!Schema::hasTable('invoices')) {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no')->nullable(true);
            $table->float('inv_subtotal');
            $table->float('inv_tax')->nullable(true);
            $table->float('inv_dis')->nullable(true);
            $table->float('inv_total')->nullable(true);
            $table->timestamps();
        });
    }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
