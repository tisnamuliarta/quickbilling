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
        Schema::table('receipt_productions', function (Blueprint $table) {
            $table->string('status', 50)->default('open');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->renameColumn('pay_account_id', 'account_id');
            $table->string('transaction_no', 100);
            $table->date('transaction_date');
            $table->string('status')->default('draft');
            $table->decimal('main_account_amount', 13, 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_productions', function (Blueprint $table) {
            //
        });
    }
};
