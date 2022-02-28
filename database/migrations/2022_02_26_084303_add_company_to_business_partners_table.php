<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bp_banks', function (Blueprint $table) {
            $table->bigIncrements('bp_bank_id');
            $table->unsignedBigInteger('bp_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('bp_account_name');
            $table->string('bp_account_number');
            $table->timestamps();
        });

        Schema::create('price_lists', function (Blueprint $table) {
            $table->bigIncrements('price_list_id');
            $table->string('price_list_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('price_list_criteria');
            $table->decimal('price_list_amount', 10, 5);
            $table->timestamps();
        });

        Schema::create('price_list_bp', function (Blueprint $table) {
            $table->bigIncrements('price_list_bp_id');
            $table->unsignedBigInteger('price_list_id');
            $table->unsignedBigInteger('bp_id');
            $table->timestamps();
        });

        Schema::create('price_list_products', function (Blueprint $table) {
            $table->bigIncrements('price_list_product_id');
            $table->unsignedBigInteger('price_list_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('buy_price', 20, 5)->nullable();
            $table->decimal('sell_price', 20, 5)->nullable();
            $table->decimal('end_price', 20, 5)->nullable();
            $table->timestamps();
        });

        Schema::create('sq_header', function (Blueprint $table) {
            $table->bigIncrements('sq_id');
            $table->unsignedBigInteger('bp_id');
            $table->dateTime('doc_date');
            $table->dateTime('due_date');
            $table->string('sq_number')->unique();
            $table->text('billing_address');
            $table->string('payment_term');
            $table->text('memo');
            $table->text('notes');
            $table->decimal('sub_total', 20, 5);
            $table->decimal('tax_amt', 20, 5);
            $table->decimal('freight', 20, 5);
            $table->decimal('total', 20, 5);
            $table->timestamps();
        });

        Schema::create('sq_detail', function (Blueprint $table) {
            $table->bigIncrements('sq_detail_id');
            $table->unsignedBigInteger('sq_id');
            $table->unsignedBigInteger('product_id');
            $table->float('qty', 10, 5);
            $table->string('description')->nullable();
            $table->string('uom', 50)->nullable();
            $table->string('discount', 50)->nullable();
            $table->string('tax', 50);
            $table->decimal('sub_total', 20, 5);
            $table->timestamps();
        });

        Schema::create('expense_header', function (Blueprint $table) {
            $table->bigIncrements('expense_id');
            $table->unsignedBigInteger('bp_id');
            $table->dateTime('doc_date');
            $table->string('payment_term');
            $table->string('expense_number')->unique();
            $table->text('billing_address');
            $table->text('memo');
            $table->text('notes');
            $table->decimal('sub_total', 20, 5);
            $table->decimal('tax_amt', 20, 5);
            $table->decimal('freight', 20, 5);
            $table->decimal('total', 20, 5);
            $table->unsignedBigInteger('expense_account')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_detail', function (Blueprint $table) {
            $table->bigIncrements('expense_detail_id');
            $table->unsignedBigInteger('expense_id');
            $table->unsignedBigInteger('expense_account')->nullable();
            $table->string('description')->nullable();
            $table->string('tax', 50)->nullable();
            $table->decimal('sub_total', 20, 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bp_banks');
        Schema::dropIfExists('price_lists');
        Schema::dropIfExists('price_list_bp');
        Schema::dropIfExists('price_list_products');
        Schema::dropIfExists('sq_header');
        Schema::dropIfExists('sq_detail');
        Schema::dropIfExists('expense_header');
        Schema::dropIfExists('expense_detail');
    }
};
