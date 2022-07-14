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
        Schema::create('receipt_productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('transaction_no', 50)->unique();
            $table->date('transaction_date');
            $table->enum('transaction_type', ['receipt', 'issue']);
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->decimal('main_account_amount', 13, 4)->nullable();
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
        Schema::dropIfExists('receipt_productions');
    }
};
