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
        Schema::create('contact_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('bank_id');
            $table->string('contact_account_name');
            $table->string('contact_account_number');
            $table->string('branch')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('payment_term_id')->nullable();
            $table->unsignedBigInteger('deposit_account_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('shipping_date')->nullable();
            $table->dateTime('receipt_date')->nullable();
            $table->string('tracking_no')->nullable();
            $table->text('notes')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('ship_vie')->nullable();
            $table->string('ship_via')->nullable();
            $table->string('discount_type', 50)->nullable();
            $table->decimal('discount_rate', 13, 4)->default(0);
            $table->decimal('discount_amount', 13, 4)->default(0);
            $table->decimal('shipping_fee', 13, 4)->default(0);
            $table->decimal('deposit', 13, 4)->default(0);
            $table->decimal('balance_due', 13, 4)->default(0);
            $table->string('status')->default('draft');
        });

        Schema::table('vats', function (Blueprint $table) {
            $table->string('code', 5)->change();
            $table->string('status')->default('draft');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_id')->nullable();
        });

        Schema::table('line_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->date('service_date')->nullable();
            $table->decimal('qty', 13, 4)->default(0);
            $table->string('classification')->nullable();
            $table->string('status')->default('draft');
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // general information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 150)->nullable();
            $table->string('zip_code', 50)->nullable();
            $table->string('home_phone', 50)->nullable();
            $table->string('work_phone', 50)->nullable();
            $table->string('mobile_phone', 50)->nullable();
            // payment
            $table->string('payment_method', 50)->nullable();
            // employee details
            $table->string('status', 100)->default('active');
            $table->dateTime('hire_date');
            $table->string('pay_frequency');
            $table->string('pay_schedule_name');
            $table->unsignedBigInteger('work_location_id');
            $table->string('employee_id', 20)->unique();
            // pay type
            $table->string('pay_type');
            $table->decimal('per_hour_rate', 13, 4)->default(0);
            $table->decimal('salary', 13, 4)->default(0);
            $table->decimal('hour_per_day', 5, 2)->default(0);
            $table->decimal('day_per_week', 5, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('work_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pay_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->timestamps();
        });

        Schema::create('employee_pay_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('pay_type_id');
            $table->decimal('rate', 13, 4)->default(0);
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
        Schema::dropIfExists('contact_banks');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('work_locations');
        Schema::dropIfExists('employee_pay_details');
        Schema::dropIfExists('pay_types');
    }
};
