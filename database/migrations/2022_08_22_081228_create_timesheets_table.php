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
        Schema::create('timesheet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('date_start')->nullable();
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->time('time_in_lunch')->nullable();
            $table->time('time_out_lunch')->nullable();
            $table->string('narration')->nullable();
            $table->decimal('amount', 13, 4)->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->boolean('billable')->default(false);
            $table->boolean('taxable')->default(false);
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
        Schema::dropIfExists('timesheet');
    }
};
