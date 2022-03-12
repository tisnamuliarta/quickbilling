<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_phone', 'bank_address']);
            $table->unsignedBigInteger('category_id')->after('company_id');
            $table->unsignedBigInteger('bank_id')->after('category_id');
            $table->string('details', 100)->default('None')->after('enabled');
            $table->unsignedBigInteger('related_id')->nullable()->after('details');
            $table->text('descriptions')->nullable()->after('related_id');
        });

        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('code', 10);
            $table->string('swift_code', 30)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 200)->nullable();
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
        Schema::dropIfExists('banks');
    }
}
