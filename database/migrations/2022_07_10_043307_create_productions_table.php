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
        Schema::table('items', function (Blueprint $table) {
            $table->float('on_hand_qty')->default(0)->nullable();
            $table->float('committed_qty')->default(0)->nullable();
            $table->float('ordered_qty')->default(0)->nullable();
            $table->string('sale_uom', 50)->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->enum('issue_method', ['backflush', 'manual'])->default('backflush');
            $table->enum('material_type', ['production', 'sales', 'assembly', 'template']);
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('base_num', 20)->nullable();
            $table->string('base_type', 2)->nullable();
            $table->renameColumn('type', 'transaction_type');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('base_num', 20)->nullable();
            $table->string('base_type', 2)->nullable();
            $table->string('contact_address')->nullable();
        });

        Schema::table('line_items', function (Blueprint $table) {
            $table->unsignedBigInteger('warehouse_id')->nullable();
        });

        Schema::table('document_items', function (Blueprint $table) {
            $table->unsignedBigInteger('warehouse_id')->nullable();
        });

        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('transaction_no', 50)->unique();
            $table->date('transaction_date');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('transaction_type', 50);
            $table->string('narration');
            $table->enum('status', ['planned', 'released', 'closed', 'cancel'])->default('planned');
            // sale order
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->float('planned_qty')->default(1)->nullable();
            $table->float('complete_qty')->default(1)->nullable();
            $table->float('rejected_qty')->default(1)->nullable();
            $table->string('uom', 50)->nullable();
            $table->string('notes', 500)->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('base_id')->nullable();
            $table->string('base_num', 20)->nullable();
            $table->string('base_type', 2)->nullable();
            $table->decimal('component_cost', 13, 4)->nullable();
            $table->decimal('product_cost', 13, 4)->nullable();
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
        Schema::dropIfExists('productions');
    }
};
