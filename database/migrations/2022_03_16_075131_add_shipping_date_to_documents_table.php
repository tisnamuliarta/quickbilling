<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingDateToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dateTime('shipping_date')->nullable()->after('parent_id');
            $table->string('shipping_via', 150)->nullable()->after('shipping_date');
            $table->string('tracking_code', 150)->nullable()->after('shipping_via');
            $table->string('reference_no', 150)->nullable()->after('tracking_code');
            $table->unsignedBigInteger('payment_term_id')->nullable()->after('tracking_code');
            $table->unsignedBigInteger('warehouse_id')->nullable()->after('payment_term_id');
            $table->boolean('shipping_info')->default(false)->nullable()->after('payment_term_id');

            $table->decimal('sub_total', 14, 4)->nullable()->after('due_at');
            $table->string('discount_type', 20)->nullable()->after('sub_total');
            $table->decimal('discount_per_line', 14, 4)->nullable()->after('discount_type');
            $table->decimal('discount_rate', 8, 4)->nullable()->after('discount_per_line');
            $table->decimal('discount_amount', 14, 4)->nullable()->after('discount_rate');
            $table->decimal('shipping_fee', 14, 4)->nullable()->after('discount_amount');
            $table->boolean('withholding_info')->default(false)->nullable()->after('shipping_fee');
            $table->string('withholding_type', 20)->nullable()->after('withholding_info');
            $table->decimal('withholding_rate', 8, 4)->nullable()->after('withholding_type');
            $table->decimal('withholding_amount', 14, 4)->nullable()->after('withholding_rate');
            $table->boolean('deposit_info')->default(false)->nullable()->after('withholding_amount');
            $table->unsignedBigInteger('deposit_account_id')->nullable()->after('deposit_info');
            $table->decimal('deposit_amount', 14, 4)->nullable()->after('deposit_account_id');
            $table->decimal('payment_paid', 14, 4)->nullable()->after('deposit_amount');
            $table->decimal('balance_due', 14, 4)->nullable()->after('payment_paid');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dateTime('shipping_date')->nullable()->after('parent_id');
            $table->string('shipping_via', 150)->nullable()->after('shipping_date');
            $table->string('tracking_code', 150)->nullable()->after('shipping_via');
            $table->string('reference_no', 150)->nullable()->after('tracking_code');
            $table->unsignedBigInteger('payment_term_id')->nullable()->after('tracking_code');
            $table->unsignedBigInteger('warehouse_id')->nullable()->after('payment_term_id');
            $table->boolean('shipping_info')->default(false)->nullable()->after('payment_term_id');

            $table->decimal('sub_total', 14, 4)->nullable()->after('due_at');
            $table->string('discount_type', 20)->nullable()->after('sub_total');
            $table->decimal('discount_per_line', 14, 4)->nullable()->after('discount_type');
            $table->decimal('discount_rate', 8, 4)->nullable()->after('discount_per_line');
            $table->decimal('discount_amount', 14, 4)->nullable()->after('discount_rate');
            $table->decimal('shipping_fee', 14, 4)->nullable()->after('discount_amount');
            $table->boolean('withholding_info')->default(false)->nullable()->after('shipping_fee');
            $table->string('withholding_type', 20)->nullable()->after('withholding_info');
            $table->decimal('withholding_rate', 8, 4)->nullable()->after('withholding_type');
            $table->decimal('withholding_amount', 14, 4)->nullable()->after('withholding_rate');
            $table->boolean('deposit_info')->default(false)->nullable()->after('withholding_amount');
            $table->unsignedBigInteger('deposit_account_id')->nullable()->after('deposit_info');
            $table->decimal('deposit_amount', 14, 4)->nullable()->after('deposit_account_id');
            $table->decimal('payment_paid', 14, 4)->nullable()->after('deposit_amount');
            $table->decimal('balance_due', 14, 4)->nullable()->after('payment_paid');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->dateTime('shipping_date')->nullable()->after('parent_id');
            $table->string('shipping_via', 150)->nullable()->after('shipping_date');
            $table->string('tracking_code', 150)->nullable()->after('shipping_via');
            $table->string('reference_no', 150)->nullable()->after('tracking_code');
            $table->unsignedBigInteger('payment_term_id')->nullable()->after('tracking_code');
            $table->unsignedBigInteger('warehouse_id')->nullable()->after('payment_term_id');
            $table->boolean('shipping_info')->default(false)->nullable()->after('payment_term_id');

            $table->decimal('sub_total', 14, 4)->nullable()->after('due_at');
            $table->string('discount_type', 20)->nullable()->after('sub_total');
            $table->decimal('discount_per_line', 14, 4)->nullable()->after('discount_type');
            $table->decimal('discount_rate', 8, 4)->nullable()->after('discount_per_line');
            $table->decimal('discount_amount', 14, 4)->nullable()->after('discount_rate');
            $table->decimal('shipping_fee', 14, 4)->nullable()->after('discount_amount');
            $table->boolean('withholding_info')->default(false)->nullable()->after('shipping_fee');
            $table->string('withholding_type', 20)->nullable()->after('withholding_info');
            $table->decimal('withholding_rate', 8, 4)->nullable()->after('withholding_type');
            $table->decimal('withholding_amount', 14, 4)->nullable()->after('withholding_rate');
            $table->boolean('deposit_info')->default(false)->nullable()->after('withholding_amount');
            $table->unsignedBigInteger('deposit_account_id')->nullable()->after('deposit_info');
            $table->decimal('deposit_amount', 14, 4)->nullable()->after('deposit_account_id');
            $table->decimal('payment_paid', 14, 4)->nullable()->after('deposit_amount');
            $table->decimal('balance_due', 14, 4)->nullable()->after('payment_paid');
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouses');
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
}
