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
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 10)->nullable()->default('en');
        });
        Schema::table('line_items', function (Blueprint $table) {
            $table->renameColumn('qty', 'sub_total');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('ship_vie');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
