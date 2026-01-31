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
         if (!Schema::hasTable('quoteproducts')) {
            Schema::create('quoteproducts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quotation_id')->constrained('quotations');
                $table->foreignId('quoteprod_id')->constrained('products');
                $table->float('quote_quantity');
                $table->string('quote_unit');
                $table->float('quote_price');
                $table->float('quotetotal_price');
                $table->timestamps();
            });
        }
                
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('quoteproducts');
    }
};
