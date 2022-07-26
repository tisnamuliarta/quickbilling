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
        Schema::table('line_items', function (Blueprint $table) {
            $table->string('tax_name', 100)->nullable();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->date('hire_date')->nullable()->change();
            $table->string('pay_frequency')->nullable()->change();
            $table->string('pay_schedule_name')->nullable()->change();
            $table->string('employee_id', 20)->nullable()->change();
            $table->unsignedBigInteger('work_location_id')->nullable()->change();
            $table->decimal('per_hour_rate', 13, 4)->nullable()->change();
            $table->decimal('salary', 13, 4)->nullable()->change();
            $table->decimal('hour_per_day', 8, 4)->nullable()->change();
            $table->decimal('day_per_week', 8, 4)->nullable()->change();
            $table->string('nickname')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('line_items', function (Blueprint $table) {
            //
        });
    }
};
