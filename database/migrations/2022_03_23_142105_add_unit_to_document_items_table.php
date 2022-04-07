<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitToDocumentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $array = ['document_items', 'invoice_items', 'bill_items'];
        foreach ($array as $item) {
            Schema::table($item, function (Blueprint $table) {
                $table->string('unit', 100)->nullable()->after('quantity');
                $table->string('tax_name', 100)->nullable()->after('tax');
                $table->decimal('tax_rate', 15, 4)->nullable()->after('tax_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_items', function (Blueprint $table) {
            //
        });
    }
}
