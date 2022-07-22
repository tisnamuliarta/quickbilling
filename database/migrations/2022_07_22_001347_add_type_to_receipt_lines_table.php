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
        Schema::rename('receipt_lines', 'receipt_items');

        Schema::table('receipt_items', function (Blueprint $table) {
            $table->string('item_type', 100);
            $table->string('item_name', 200);
            $table->string('uom', 100);
            $table->unsignedSmallInteger('line_num')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
