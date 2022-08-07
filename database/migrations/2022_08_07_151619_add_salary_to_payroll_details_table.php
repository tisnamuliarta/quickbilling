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
        Schema::table('payroll_datails', function (Blueprint $table) {
            $table->decimal('salary', 13, 4)->nullable();
        });

        Schema::rename('payroll_datails', 'payroll_details');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_datails', function (Blueprint $table) {
            //
        });
    }
};
