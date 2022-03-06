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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('type');
            $table->string('document_number');
            $table->string('order_number')->nullable();
            $table->string('status');
            $table->dateTime('issued_at');
            $table->dateTime('due_at');
            $table->decimal('amount', 15, 4);
            $table->string('currency_code');
            $table->decimal('currency_rate', 15, 4);
            $table->unsignedBigInteger('category_id')->default(1);
            $table->unsignedBigInteger('contact_id');
            $table->string('contact_name');
            $table->string('contact_email')->nullable();
            $table->string('contact_tax_number')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('contact_country')->nullable();
            $table->string('contact_state')->nullable();
            $table->string('contact_zip_code')->nullable();
            $table->string('contact_city')->nullable();
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->unique(['document_number', 'deleted_at', 'company_id', 'type']);
        });

        Schema::create('document_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('type');
            $table->unsignedBigInteger('document_id');
            $table->string('status');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('document_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('type');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->nullable();
            $table->decimal('quantity', 7, 3);
            $table->decimal('price', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->string('discount_type')->default('normal');
            $table->decimal('discount_rate', 15, 4)->default('0.0000');
            $table->decimal('total', 15, 4);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('document_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('type');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('document_item_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('name');
            $table->decimal('amount', 15, 4)->default('0.0000');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('document_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('type');
            $table->unsignedBigInteger('document_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->decimal('amount', 15, 4);
            $table->integer('sort_order');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('type');
            $table->index('document_id');
        });

        Schema::create('item_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('item_id');
            $table->integer('tax_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'item_id']);
        });

        // Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('type');
            $table->string('name');
            $table->string('email')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('website')->nullable();
            $table->string('currency_code', 3);
            $table->boolean('enabled')->default(1);
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('receivable_account_id')->nullable();
            $table->unsignedBigInteger('payable_account_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->unique(['company_id', 'type', 'email', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documents');
        Schema::drop('document_histories');
        Schema::drop('document_items');
        Schema::drop('document_item_taxes');
        Schema::drop('document_totals');
        Schema::drop('item_taxes');
    }
};
