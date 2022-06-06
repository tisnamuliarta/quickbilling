<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('ifrs.table_prefix') . 'users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verify_at')->nullable();
            $table->string('password');
            $table->boolean('enabled')->default(1);
            $table->string('avatar')->nullable();
            $table->string('user_type')->nullable();
            $table->rememberToken();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists(config('ifrs.table_prefix') .'users');
    }
}
