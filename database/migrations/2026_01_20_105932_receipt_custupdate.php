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
        schema::table('receipts', function (Blueprint $table) {
            $table->unsignedBigInteger('cust_id')->after('rec_status')->nullable();

            $table->foreign('cust_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['cust_id']);
            $table->dropColumn('cust_id');
        });
    }
};
