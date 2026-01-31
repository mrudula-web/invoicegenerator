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
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->string('quote_no')->nullable(true);
                $table->float('quoteinv_subtotal');
                $table->float('quoteinv_tax')->nullable(true);
                $table->float('quoteinv_dis')->nullable(true);
                $table->float('quoteinv_total')->nullable(true);
                $table->timestamps();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('quotations');
    }
};
