<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceIncludeTaxToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('price_include_tax')->default(false)->after('temp_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('price_include_tax')->default(false)->after('temp_id');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->boolean('price_include_tax')->default(false)->after('temp_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
}
