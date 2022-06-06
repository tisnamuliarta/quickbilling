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
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('onhand', 13, 4)->nullable();
            $table->date('onhand_date')->nullable();
            $table->decimal('reorder_point', 13, 4)->nullable();
            $table->string('purchase_description')->nullable();
            $table->unsignedBigInteger('classification_id')->nullable();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('classification_id')->nullable();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('classification_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};
