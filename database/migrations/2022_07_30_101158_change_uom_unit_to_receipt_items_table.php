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
        Schema::table('receipt_items', function (Blueprint $table) {
            $table->renameColumn('uom', 'unit');
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->renameColumn('uom', 'unit');
        });

        Schema::table('production_items', function (Blueprint $table) {
            $table->renameColumn('uom', 'unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_items', function (Blueprint $table) {
            //
        });
    }
};
