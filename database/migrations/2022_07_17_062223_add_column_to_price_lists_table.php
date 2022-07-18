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
        Schema::table('price_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('base_id');
        });

        Schema::table('item_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id');
        });

        Schema::table('item_units', function (Blueprint $table) {
            $table->unsignedBigInteger('price_list_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            //
        });
    }
};
