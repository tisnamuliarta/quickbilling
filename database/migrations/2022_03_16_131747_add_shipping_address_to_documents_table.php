<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingAddressToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('shipping_info');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('shipping_info');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('shipping_info');
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
