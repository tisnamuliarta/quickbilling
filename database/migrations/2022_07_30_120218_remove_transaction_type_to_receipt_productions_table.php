<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_productions', function (Blueprint $table) {
            $table->dropColumn('transaction_type');
        });

        Schema::table('receipt_productions', function (Blueprint $table) {
            $table->string('transaction_type', 2);
            $table->unsignedBigInteger('base_id')->nullable();
            $table->string('base_type', 10)->nullable();
            $table->unsignedBigInteger('price_list_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_productions', function (Blueprint $table) {
            //
        });
    }
};
