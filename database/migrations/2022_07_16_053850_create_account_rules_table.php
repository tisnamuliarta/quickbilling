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
        Schema::create('account_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_mapping_id');
            $table->string('rules_name');
            $table->unsignedBigInteger('account_id');
            $table->timestamps();
        });

        Schema::table('account_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('reporting_period_id');
        });

        Schema::table('price_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
        });

        Schema::table('item_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
        });

        Schema::table('items', function (Blueprint $table) {
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
        Schema::dropIfExists('account_rules');
    }
};
