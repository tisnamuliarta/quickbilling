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
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('price_list_id');
            $table->decimal('price', 13, 4);
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->enum('item_type', ['purchase', 'sales', 'inventory'])->nullable();
            $table->unsignedBigInteger('resource_id')->nullable();
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_prices');
    }
};
