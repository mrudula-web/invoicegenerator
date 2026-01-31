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

           schema::table('quotations', function (Blueprint $table) {
                $table->integer('quotecust_id');
               $table->text('quote_terms')->nullable();

               
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('quotecust_id');
            $table->dropColumn('quote_terms');
        });
    }
};
