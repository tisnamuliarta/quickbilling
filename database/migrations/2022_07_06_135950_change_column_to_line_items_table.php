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
        Schema::table('line_items', function (Blueprint $table) {
            $table->removeColumn('classification');
            $table->string('sku', 50)->nullable();
            $table->string('discount_type', 50)->nullable();
            $table->decimal('discount_rate', 13, 2)->nullable();
        });

        Schema::table('document_items', function (Blueprint $table) {
            $table->renameColumn('description', 'narration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_items', function (Blueprint $table) {
            //
        });
    }
};
