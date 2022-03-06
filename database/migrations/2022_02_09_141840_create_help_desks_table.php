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
        Schema::create('master_task_category', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('master_sub_category', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });


        Schema::create('task_priority', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('background')->nullable();
            $table->timestamps();
        });

        Schema::create('task_section', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->smallInteger('order_line')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('department');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->smallInteger('order_line')->default(0);
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('priority_id')->nullable();
            $table->unsignedBigInteger('board_id')->nullable();
            $table->timestamps();
        });

        Schema::create('task_sub_category', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('task_id');
        });

        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->timestamps();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
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
        Schema::dropIfExists('master_task_category');
        Schema::dropIfExists('master_sub_category');
        Schema::dropIfExists('task_priority');
        Schema::dropIfExists('task_section');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_sub_category');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('menus');
    }
};
