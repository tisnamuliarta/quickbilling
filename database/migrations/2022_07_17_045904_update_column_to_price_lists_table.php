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
            $table->decimal('factor')->default(1);
            $table->boolean('enabled')->default(true);
            $table->enum('rounding_method', ['Closest', 'Up', 'Down'])->nullable();
            $table->unsignedSmallInteger('group_code')->nullable();
        });

        Schema::table('contacts', function (Blueprint $table) {
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
        Schema::table('price_lists', function (Blueprint $table) {
            //
        });
    }
};
