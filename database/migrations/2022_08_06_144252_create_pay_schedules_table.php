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
        Schema::create('pay_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('pay_frequency');
            $table->string('name');
            $table->date('pay_date');
            $table->date('end_pay_period');
            $table->timestamps();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('pay_schedule_id')->nullable();
            $table->dateTime('hire_date')->nullable()->change();
            $table->string('pay_frequency')->nullable()->change();
            $table->string('pay_schedule_name')->nullable()->change();
            $table->unsignedBigInteger('work_location_id')->nullable()->change();
            $table->string('employee_id', 20)->nullable()->change();
            $table->string('pay_type')->nullable()->change();
            $table->decimal('per_hour_rate', 13, 4)->nullable()->change();
            $table->decimal('salary', 13, 4)->nullable()->change();
            $table->decimal('hour_per_day', 5, 2)->nullable()->change();
            $table->decimal('day_per_week', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pay_schedules');
    }
};
