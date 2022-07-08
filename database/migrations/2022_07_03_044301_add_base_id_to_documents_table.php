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
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('base_id')->nullable();
            $table->renameColumn('issued_at', 'transaction_date');
            $table->renameColumn('due_at', 'due_date');
            $table->renameColumn('amount', 'main_account_amount');
            $table->renameColumn('footer', 'narration');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('base_id')->nullable();
            $table->string('reference_no', 150)->nullable();
        });

        Schema::table('line_items', function (Blueprint $table) {
            $table->unsignedBigInteger('base_line_id')->nullable();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('base_line_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
};
