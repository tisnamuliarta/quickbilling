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
        Schema::rename('deductions', 'loans');

        Schema::table('loans', function (Blueprint $table) {
            $table->string('reference_no', 150)->nullable();
            $table->string('load_type')->nullable();
            $table->string('interest_type')->nullable();
            $table->string('interest_rate')->nullable();
            $table->decimal('outstanding_loan')->default(0);
            $table->decimal('admin_charge')->default(0);
            $table->float('installment_amount')->nullable();
            $table->date('installment_start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
