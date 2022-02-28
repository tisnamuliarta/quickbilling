<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_header', function (Blueprint $table) {
            $table->bigIncrements('po_header_id');
            $table->unsignedSmallInteger('revision_number')->default(0);
            $table->bigInteger('employee_id')->nullable();
            $table->bigInteger('bp_id')->nullable();
            $table->bigInteger('ship_method_id')->nullable();
            $table->dateTime('order_date');
            $table->dateTime('ship_date');
            $table->decimal('sub_total', 20, 5);
            $table->decimal('tax_amt', 20, 5);
            $table->decimal('freight', 20, 5);
            $table->decimal('total_due', 20, 5);

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
        Schema::dropIfExists('po_header');
    }
}
