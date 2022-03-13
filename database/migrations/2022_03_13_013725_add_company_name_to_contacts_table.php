<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyNameToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('user_id');
            $table->text('shipping_address')->nullable()->after('address');
            $table->string('fax', 50)->nullable()->after('phone');
            $table->string('identify_by', 50)->nullable()->after('fax');
            $table->string('identify_number', 100)->nullable()->after('identify_by');
            $table->string('identify', 100)->nullable()->after('identify_by');
            $table->string('first_name', 100)->nullable()->after('identify');
            $table->string('middle_name', 100)->nullable()->after('first_name');
            $table->string('last_name', 100)->nullable()->after('middle_name');
            $table->unsignedBigInteger('payment_term_id')->nullable()->after('payable_account_id');
            $table->decimal('max_payable', 14, 4)->default(0)->after('payment_term_id');
            $table->boolean('active_max_payable')->default(false)->after('max_payable');
        });

        Schema::create('contact_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->string('email', 200);
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
        Schema::table('contacts', function (Blueprint $table) {
            //
        });

        Schema::dropIfExists('contact_emails');
    }
}
