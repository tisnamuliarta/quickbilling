<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_detail', function (Blueprint $table) {
            $table->bigIncrements('po_detail_id');
            $table->dateTime('due_date');
            $table->decimal('order_qty', 20, 4);
            $table->decimal('unit_price', 20, 4);
            $table->decimal('line_total', 20, 4);
            $table->decimal('received_qty', 20, 4);
            $table->decimal('rejected_qty', 20, 4);
            $table->decimal('stocked_qty', 20, 4);

            $table->foreignId('product_id')->nullable()->constrained("products")
                ->references('product_id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('purchase_order_header_id')->nullable()->constrained("purchase_order_header")
                ->references('purchase_order_header_id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });

        Schema::create('special_offer', function (Blueprint $table) {
            $table->bigIncrements('special_offer_id');
            $table->string('description');
            $table->float('discount_pct', 4, 2);
            $table->string('discount_type');
            $table->string('discount_category');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->float('min_qty', 8, 2)->nullable();
            $table->float('max_qty', 8, 2)->nullable();
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('po_detail');
        Schema::dropIfExists('special_offer');
    }
}
