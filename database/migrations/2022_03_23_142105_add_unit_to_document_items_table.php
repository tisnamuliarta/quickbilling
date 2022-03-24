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
        Schema::table('document_items', function (Blueprint $table) {
            $table->string('unit', 100)->nullable()->after('quantity');
            $table->string('tax_name', 100)->nullable()->after('tax');
            $table->decimal('tax_rate', 15, 4)->nullable()->after('tax_name');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('unit', 100)->nullable()->after('quantity');
            $table->string('tax_name', 100)->nullable()->after('tax');
            $table->decimal('tax_rate', 15, 4)->nullable()->after('tax_name');
        });

        Schema::table('bill_items', function (Blueprint $table) {
            $table->string('unit', 100)->nullable()->after('quantity');
            $table->string('tax_name', 100)->nullable()->after('tax');
            $table->decimal('tax_rate', 15, 4)->nullable()->after('tax_name');
        });
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
