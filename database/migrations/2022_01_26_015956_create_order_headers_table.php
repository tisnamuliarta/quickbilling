<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->string('currency_code', 10)->unique();
            $table->string('currency_name')->nullable();
            $table->timestamps();
        });

        Schema::create('currency_rate', function (Blueprint $table) {
            $table->bigIncrements('currency_rate_id');
            $table->dateTime('currency_rate_date')->nullable();
            $table->string('From_currency_code')->nullable();
            $table->string('To_currency_code')->nullable();
            $table->decimal('average_rate', 20, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('so_status', function (Blueprint $table) {
            $table->bigIncrements('so_status_id');
            $table->string('status_name')->nullable();
            $table->timestamps();
        });

        Schema::create('bp', function (Blueprint $table) {
            $table->bigIncrements('bp_id');
            $table->string('bp_type', 10)->nullable();
            $table->string('bp_name')->nullable();
            $table->string('bp_title')->nullable();
            $table->string('bp_email')->nullable();
            $table->string('bp_phone_number')->nullable();
            $table->string('bp_company_name')->nullable();
            $table->string('bp_company_phone')->nullable();
            $table->string('bp_company_fax')->nullable();
            $table->string('bp_company_tax_number')->nullable();
            $table->text('bp_company_billing_address')->nullable();
            $table->text('bp_company_shipping_address')->nullable();
            $table->unsignedBigInteger('account_receivable')->nullable();
            $table->unsignedBigInteger('account_payable')->nullable();
            $table->string('max_payable', 10)->nullable();
            $table->string('default_payment_term')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_persons', function (Blueprint $table) {
            $table->bigIncrements('sales_person_id');
            $table->decimal('sales_quota', 20)->nullable();
            $table->decimal('bonus', 20)->nullable();
            $table->decimal('commission_pct', 20)->nullable();
            $table->decimal('sales_ytd', 20)->nullable();
            $table->decimal('sales_last_year', 20)->nullable();
            $table->timestamps();
        });


        Schema::create('so_header', function (Blueprint $table) {
            $table->bigIncrements('so_header_id');
            $table->unsignedSmallInteger('revision_number')->default(0);
            $table->dateTime('order_date');
            $table->dateTime('due_date');
            $table->dateTime('ship_date');
            $table->string('so_number', 50)->unique();
            $table->string('po_number', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->bigInteger('so_status_id')->nullable();
            $table->bigInteger('sales_person_id')->nullable();
            $table->bigInteger('bill_to_address_id')->nullable();
            $table->bigInteger('ship_method_id')->nullable();
            $table->bigInteger('credit_card_id')->nullable();
            $table->string('credit_card_approval_code')->nullable();
            $table->bigInteger('currency_rate_id')->nullable();
            $table->decimal('sub_total', 20, 4)->default(0);
            $table->decimal('tax_amt', 10, 4)->default(0);
            $table->decimal('freight', 10, 4)->default(0);
            $table->decimal('total_due', 10, 4)->default(0);
            $table->string('bp_ref')->nullable();
            $table->text('memo')->nullable();
            $table->string('payment_term')->nullable();

            $table->bigInteger('bp_id')->nullable()->constrained("bp")
                ->references('bp_id')->cascadeOnUpdate()->nullOnDelete();
//            $table->foreignId('ProductId')->nullable()->constrained("table_name")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::dropIfExists('currency');
        Schema::dropIfExists('currency_rate');
        Schema::dropIfExists('so_status');
        Schema::dropIfExists('bp');
        Schema::dropIfExists('sales_persons');
        Schema::dropIfExists('so_header');
    }
}
