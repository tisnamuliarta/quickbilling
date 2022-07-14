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
        Schema::create('production_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('production_id');
            $table->unsignedBigInteger('item_id');
            $table->enum('item_type', ['item', 'resource', 'text']);
            $table->string('item_name', 200)->nullable();
            $table->string('uom', 20)->nullable();
            $table->float('base_qty')->default(0)->nullable();
            $table->float('planned_qty')->default(0)->nullable();
            $table->float('issued_qty')->default(0)->nullable();
            $table->unsignedBigInteger('warehouse_id');
            $table->enum('issue_method', ['backflush', 'manual']);
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('narration')->nullable();
            $table->float('additional_qty')->default(0)->nullable();
            $table->enum('pick_status', ['picked', 'not picked', 'release for picking', 'partially picked'])
                ->default('not picked');
            $table->float('pick_qty')->nullable();
            $table->float('release_qty')->nullable();
            $table->enum('resource_allocation', [
                'on start date', 'on end date', 'start date forwards', 'end date backwards',
            ])
                ->nullable();
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
        Schema::dropIfExists('production_items');
    }
};
