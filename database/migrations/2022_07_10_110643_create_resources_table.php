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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('resource_type', ['machine', 'labor', 'other', 'item'])->default('machine');
            $table->string('uom', 50)->nullable();
            $table->time('resource_time')->nullable();
            $table->tinyInteger('resource_unit')->nullable();
            $table->enum('issue_method', ['backflush', 'manual'])->default('backflush');
            $table->decimal('standard_cost', 13, 4)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->float('internal_capacity')->nullable();
            $table->float('committed_capacity')->nullable();
            $table->float('available_capacity')->nullable();
            $table->float('consumed_capacity')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
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
        Schema::dropIfExists('resources');
    }
};
