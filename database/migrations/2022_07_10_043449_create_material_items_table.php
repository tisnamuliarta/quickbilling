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
        Schema::create('material_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->enum('item_type', ['item', 'resource', 'text']);
            $table->unsignedBigInteger('item_id');
            $table->decimal('price', 13, 4)->default(0)->nullable();
            $table->float('quantity')->default(0)->nullable();
            $table->float('additional_qty')->default(0)->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->enum('issue_method', ['backflush', 'manual']);
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
        Schema::dropIfExists('material_items');
    }
};
