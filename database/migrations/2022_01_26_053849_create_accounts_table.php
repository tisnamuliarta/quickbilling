<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('account_id');
            $table->string('account_code')->unique();
            $table->string('parent_account');
            $table->string('parent_account_code');
            $table->string('account_desc');
            $table->string('account_type');
            $table->string('account_tax')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->decimal('start_balance', 20, 5)->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
