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
        Schema::table('categories', function (Blueprint $table) {
            $table->text('descriptions')->nullable()->after('name');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->date('opening_balance_date')->nullable();
            $table->decimal('opening_balance_amount', 13, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
