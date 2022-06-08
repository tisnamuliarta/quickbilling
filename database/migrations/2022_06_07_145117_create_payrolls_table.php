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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pay_account_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->date('pay_date');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        Schema::create('payroll_datails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('pay_type_id');
            $table->decimal('amount', 13, 4);
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('payroll_datails');
    }
};
