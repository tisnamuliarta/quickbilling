<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('contact_account_name');
            $table->string('contact_account_number');
            $table->timestamps();
        });

        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('price_list_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('price_list_criteria');
            $table->decimal('price_list_amount', 10, 5);
            $table->timestamps();
        });

        Schema::create('price_list_bp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_id');
            $table->unsignedBigInteger('bp_id');
            $table->timestamps();
        });

        Schema::create('price_list_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('price_list_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('buy_price', 20, 5)->nullable();
            $table->decimal('sell_price', 20, 5)->nullable();
            $table->decimal('end_price', 20, 5)->nullable();
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
        Schema::dropIfExists('bp_banks');
        Schema::dropIfExists('price_lists');
        Schema::dropIfExists('price_list_bp');
        Schema::dropIfExists('price_list_products');
    }
};
