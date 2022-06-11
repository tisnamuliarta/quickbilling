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
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->after('id');
            $table->string('national_id', 25)->unique()->nullable()->after('zip_code');
            $table->string('gender', 25)->nullable()->after('national_id');
            $table->unsignedBigInteger('bank_id')->after('payment_method')->nullable();
            $table->string('bank_account_name', 200)->nullable()->after('bank_id');
            $table->string('bank_account_number', 30)->nullable()->after('bank_account_name');
        });

        Schema::table('work_locations', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->after('id');
            $table->string('notes')->after('name');
        });

        Schema::table('payroll_datails', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->after('id');
        });

        Schema::table('pay_types', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->after('id');
        });

        Schema::table('employee_pay_details', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
