<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->string('category_name');
            $table->text('category_desc')->nullable();
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('product_brands', function (Blueprint $table) {
            $table->bigIncrements('product_brand_id');
            $table->string('brand_name');
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('unit_measure', function (Blueprint $table) {
            $table->bigIncrements('unit_measure_id');
            $table->string('measure_name');
            $table->string('measure_code', 20)->nullable();
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('features', function (Blueprint $table) {
            $table->bigIncrements('feature_id');
            $table->string('feature_name');
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('product_name');
            $table->string('product_model');
            $table->string('product_number', 20)->nullable();
            $table->string('color')->nullable();
            $table->string('product_type')->nullable();
            $table->text('product_desc');
            $table->float('reorder_point')->default(0);
            $table->unsignedBigInteger('unit_measure')->nullable();
            $table->decimal('buy_price', 20, 5)->nullable();
            $table->unsignedBigInteger('buy_account_id')->nullable();
            $table->string('buy_default_tax')->nullable();
            $table->decimal('sell_price', 20, 5)->nullable();
            $table->unsignedBigInteger('sell_account_id')->nullable();
            $table->string('sell_default_tax')->nullable();
            $table->dateTime('sel_start_date')->nullable();

            $table->foreignId('product_brand_id')->nullable()->constrained("product_brands")
                ->references('product_brand_id')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained("product_category")
                ->references('category_id')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained("users")
                ->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('product_features', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->constrained("products")
                ->references('product_id')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('feature_id')->nullable()->constrained("features")
                ->references('feature_id')
                ->cascadeOnUpdate()->nullOnDelete();
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->bigIncrements('product_review_id');
            $table->string('reviewer_name');
            $table->dateTime('review_date');
            $table->dateTime('email_address');
            $table->unsignedSmallInteger('rating');
            $table->text('comments');
            $table->timestamps();

            $table->foreignId('product_id')->nullable()->constrained("products")
                ->references('product_id')
                ->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_category');
        Schema::dropIfExists('product_brands');
        Schema::dropIfExists('features');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_features');
        Schema::dropIfExists('product_reviews');
    }
}
