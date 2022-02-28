<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_detail', function (Blueprint $table) {
            $table->bigIncrements('order_detail_id');
            $table->decimal('order_qty', 20, 4);
            $table->decimal('unit_price', 20, 4);
            $table->decimal('unit_price_discount', 20, 4);
            $table->decimal('line_total', 20, 4);
            $table->string('tax', 50)->nullable();
            $table->string('description')->nullable();

            $table->foreignId('product_id')->nullable()->constrained("products")
                ->references('product_id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('so_header_id')->nullable()->constrained("so_header")
                ->references('so_header_id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();

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
        Schema::dropIfExists('so_detail');
    }
}
