<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('name');
            $table->char('final_status', 30)->default('pending');
            $table->morphs('document');
            $table->text('callback')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('approval_id');
            $table->string('name');
            $table->string('operator');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('approval_id');
            $table->unsignedSmallInteger('user_id');
            $table->char('status', 30)->default('pending'); // pending, approve, reject
            $table->string('notes')->nullable();
            $table->dateTime('response_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('approval_stages');
        Schema::dropIfExists('approval_rules');
    }
}
