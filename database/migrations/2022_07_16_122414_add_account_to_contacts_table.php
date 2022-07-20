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
        Schema::table('account_mappings', function (Blueprint $table) {
            $table->boolean('enabled')->default(true);
            $table->string('account_type')->nullable();
            $table->boolean('is_clearing')->default(false);
        });

        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('std_cost_account_1')->nullable();
            $table->unsignedBigInteger('std_cost_account_2')->nullable();
            $table->unsignedBigInteger('std_cost_account_3')->nullable();
            $table->unsignedBigInteger('std_cost_account_4')->nullable();
            $table->unsignedBigInteger('std_cost_account_5')->nullable();
            $table->unsignedBigInteger('std_cost_account_6')->nullable();
            $table->unsignedBigInteger('std_cost_account_7')->nullable();
            $table->unsignedBigInteger('std_cost_account_8')->nullable();
            $table->unsignedBigInteger('std_cost_account_9')->nullable();
            $table->unsignedBigInteger('std_cost_account_10')->nullable();
        });

        Schema::table('account_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('reporting_period_id')->nullable();
            $table->unsignedTinyInteger('priority')->default(0)->nullable();
            $table->string('code', 20)->unique()->nullable();
            $table->string('item_group', 50)->nullable();
            $table->string('item_code', 50)->nullable();
            $table->string('whs_code', 50)->nullable();
            $table->string('contact_group', 50)->nullable();
            $table->string('shipment_to', 50)->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('contact_type', 50)->nullable();
            $table->string('tax_code', 50)->nullable();
            $table->string('contact_code', 50)->nullable();
            $table->string('description')->nullable();
            $table->boolean('enabled')->default(true);
            $table->unsignedBigInteger('inventory_account_id')->nullable();
            $table->unsignedBigInteger('cogs_account_id')->nullable();
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('sales_payment_id')->nullable();
            $table->unsignedBigInteger('allocation_account_id')->nullable();
            $table->unsignedBigInteger('price_diff_account_id')->nullable();
            $table->unsignedBigInteger('purchase_credit_id')->nullable();
            $table->unsignedBigInteger('sales_credit_id')->nullable();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_payment_id')->nullable();
            $table->unsignedBigInteger('sales_payment_id')->nullable();
        });

        Schema::table('item_groups', function (Blueprint $table) {
            $table->renameColumn('account_id', 'inventory_account_id');
            $table->unsignedBigInteger('cogs_account_id')->nullable();
            $table->unsignedBigInteger('allocation_account_id')->nullable();
            $table->unsignedBigInteger('price_diff_account_id')->nullable();
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->renameColumn('account_id', 'inventory_account_id');
            $table->unsignedBigInteger('cogs_account_id')->nullable();
            $table->unsignedBigInteger('allocation_account_id')->nullable();
            $table->unsignedBigInteger('price_diff_account_id')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('buy_account_id', 'expense_account_id');
            $table->renameColumn('sell_account_id', 'revenue_account_id');
            $table->unsignedBigInteger('purchase_credit_id')->nullable();
            $table->unsignedBigInteger('sales_credit_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
        });
    }
};
