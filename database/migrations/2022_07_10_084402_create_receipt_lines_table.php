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
        Schema::create('receipt_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('item_id');
            $table->float('quantity')->nullable();
            $table->float('open_qty')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->decimal('price', 13, 4)->nullable();
            $table->decimal('amount', 13, 4)->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipt_lines');
    }
};
