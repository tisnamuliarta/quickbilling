<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTempIdToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('temp_id')->nullable()->after('reference_no');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('temp_id')->nullable()->after('reference_no');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('temp_id')->nullable()->after('reference_no');
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
}
