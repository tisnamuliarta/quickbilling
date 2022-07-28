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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('warehouse_id')->nullable();
        });
        Schema::create('item_warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('on_hand_qty', 13, 4)->nullable();
            $table->decimal('ordered_qty', 13, 4)->nullable();
            $table->decimal('committed_qty', 13, 4)->nullable();
            $table->decimal('item_cost', 13, 4)->nullable();
            $table->decimal('max_inventory', 13, 4)->nullable();
            $table->decimal('min_inventory', 13, 4)->nullable();
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
        Schema::dropIfExists('item_warehouses');
    }
};
