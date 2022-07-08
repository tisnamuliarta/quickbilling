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
        Schema::dropIfExists('bill_histories');
        Schema::dropIfExists('bill_item_taxes');
        Schema::dropIfExists('bill_items');
        Schema::dropIfExists('bill_totals');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('invoice_histories');
        Schema::dropIfExists('invoice_item_taxes');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_statuses');
        Schema::dropIfExists('invoice_totals');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('taxes');

        Schema::table('document_items', function (Blueprint $table) {
            $table->renameColumn('tax', 'vat_id');
            $table->renameColumn('total', 'amount');
            $table->date('service_date')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('classification_id')->nullable();
            $table->string('status')->default('draft');
        });

        Schema::table('line_items', function (Blueprint $table) {
            $table->renameColumn('tax_id', 'vat_id');
            $table->removeColumn('classification');
            $table->unsignedBigInteger('classification_id')->nullable();
            $table->decimal('price', 15, 4)->nullable();
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
};
