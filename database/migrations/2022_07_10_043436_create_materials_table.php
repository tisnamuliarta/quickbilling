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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('transaction_no', 50)->unique();
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->enum('transaction_type', ['production', 'sales', 'assembly', 'template']);
            $table->unsignedBigInteger('project_id')->nullable();
            $table->decimal('std_cost', 13, 4)->nullable();
            $table->float('production_size')->nullable();
            $table->float('quantity')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('price_list_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
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
        Schema::dropIfExists('materials');
    }
};
