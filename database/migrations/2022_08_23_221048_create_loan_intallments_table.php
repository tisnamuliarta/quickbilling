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
        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('loan_id');
            $table->date('transaction_date');
            $table->decimal('installment', 14, 2);
            $table->decimal('reminder_amount', 14, 2);
            $table->decimal('base_amount', 14, 2);
            $table->decimal('interest', 14, 2);
            $table->string('status', 100)->nullable();
            $table->timestamps();
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->renameColumn('load_type', 'loan_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_intallments');
    }
};
