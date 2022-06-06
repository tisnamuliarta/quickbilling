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
        // Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->nullable();
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Accounts
        // Schema::create('accounts', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('entity_id');
        //     $table->string('name', 200);
        //     $table->string('number', 20);
        //     $table->string('currency_code', 5);
        //     $table->decimal('opening_balance', 15, 4)->default('0.0000');
        //     $table->string('bank_name', 150)->nullable();
        //     $table->string('bank_phone', 15)->nullable();
        //     $table->text('bank_address')->nullable();
        //     $table->boolean('enabled')->default(1);
        //     $table->unsignedBigInteger('tax_id')->nullable();
        //     $table->unsignedBigInteger('created_by')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->index('entity_id');
        // });

        // Bills
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('bill_number', 50);
            $table->string('order_number', 50)->nullable();
            $table->string('status', 50);
            $table->dateTime('billed_at');
            $table->dateTime('due_at');
            $table->decimal('amount', 15, 4);
            $table->string('currency_code', 5);
            $table->decimal('currency_rate', 15, 4);
            $table->unsignedBigInteger('category_id')->default(1);
            $table->unsignedBigInteger('contact_id');
            $table->string('contact_name', 200);
            $table->string('contact_email', 150)->nullable();
            $table->string('contact_tax_number', 16)->nullable();
            $table->string('contact_phone', 15)->nullable();
            $table->text('contact_address')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
            $table->unique(['entity_id', 'bill_number', 'deleted_at']);
        });

        Schema::create('bill_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('bill_id');
            $table->string('status', 50);
            $table->boolean('notify')->default(false);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name', 200);
            $table->string('sku')->nullable();
            $table->decimal('quantity', 8, 4);
            $table->decimal('price', 15, 4);
            $table->decimal('total', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->decimal('discount_rate', 15, 4)->default('0.0000');
            $table->string('discount_type', 100)->default('normal');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('bill_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('bill_item_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('name', 150);
            $table->decimal('amount', 15, 4)->default('0.0000');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Schema::create('bill_statuses', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('entity_id');
        //     $table->string('name', 150);
        //     $table->string('code', 50);
        //     $table->unsignedBigInteger('created_by')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();

        //     $table->index('entity_id');
        // });

        Schema::create('bill_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('bill_id');
            $table->string('code', 50)->nullable();
            $table->string('name', 150);
            $table->decimal('amount', 15, 4);
            $table->unsignedBigInteger('sort_order');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Categories
        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('name', 150);
            $table->string('code', 20)->unique();
            $table->string('type', 50);
            $table->string('color', 50);
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Currencies
        Schema::create('old_urrencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('name', 150);
            $table->string('code', 5);
            $table->decimal('rate', 15, 8);
            $table->string('precision', 50)->nullable();
            $table->string('symbol', 10)->nullable();
            $table->unsignedBigInteger('symbol_first')->default(1);
            $table->string('decimal_mark', 50)->nullable();
            $table->string('thousands_separator', 50)->nullable();
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
            $table->unique(['entity_id', 'code', 'deleted_at']);
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->dateTime('currency_rate_date')->nullable();
            $table->string('from_currency_code', 10)->nullable();
            $table->string('to_currency_code', 10)->nullable();
            $table->decimal('average_rate', 20, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('invoice_number', 50);
            $table->string('order_number', 50)->nullable();
            $table->string('status', 20);
            $table->dateTime('invoiced_at');
            $table->dateTime('due_at');
            $table->decimal('amount', 15, 4);
            $table->string('currency_code', 10);
            $table->decimal('currency_rate', 15, 4);
            $table->unsignedBigInteger('category_id')->default(1);
            $table->unsignedBigInteger('contact_id');
            $table->string('contact_name', 200);
            $table->string('contact_email', 150)->nullable();
            $table->string('contact_tax_number', 100)->nullable();
            $table->string('contact_phone', 16)->nullable();
            $table->text('contact_address')->nullable();
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
            $table->unique(['entity_id', 'invoice_number', 'deleted_at']);
        });

        Schema::create('invoice_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('status_code', 20);
            $table->boolean('notify')->default(false);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name', 200);
            $table->string('sku', 150)->nullable();
            $table->decimal('quantity', 7, 3);
            $table->decimal('price', 15, 4);
            $table->decimal('total', 15, 4);
            $table->decimal('tax', 15, 4)->default('0.0000');
            $table->decimal('discount_rate', 15, 4)->default('0.0000');
            $table->string('discount_type', 50)->default('normal');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('invoice_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('invoice_item_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('name', 200);
            $table->decimal('amount', 15, 4)->default('0.0000');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('name', 200);
            $table->string('code', 50);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('invoice_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('code', 50)->nullable();
            $table->string('name', 150);
            $table->decimal('amount', 15, 4);
            $table->unsignedBigInteger('sort_order');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Items

        Schema::create('item_groups', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name', 200);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('item_group_id');
            $table->string('code', 50)->unique();
            $table->string('name', 200);
            $table->string('unit', 100);
            $table->string('image', 200)->nullable();
            $table->text('description')->nullable();
            $table->decimal('sale_price', 15, 4)->nullable();
            $table->decimal('purchase_price', 15, 4)->nullable();
            $table->float('quantity', 12, 4)->default(0);
            $table->decimal('minimum_stock', 14, 4)->default(0);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('buy_tax_id')->nullable();
            $table->unsignedBigInteger('sell_tax_id')->nullable();
            $table->unsignedBigInteger('buy_account_id')->nullable();
            $table->unsignedBigInteger('sell_account_id')->nullable();
            $table->boolean('tract_stock')->default(false);
            $table->unsignedBigInteger('inventory_account')->nullable();
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('temp_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('item_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Modules
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('alias', 150);
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
            $table->unique(['entity_id', 'alias', 'deleted_at']);
        });

        Schema::create('module_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('module_id');
            $table->string('category', 200);
            $table->string('version', 10);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['entity_id', 'module_id']);
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 200);
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Reconciliations
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('account_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->decimal('closing_balance', 15, 4)->default('0.0000');
            $table->boolean('reconciled');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Recurring
        Schema::create('recurring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->morphs('recurable');
            $table->string('frequency', 100);
            $table->unsignedBigInteger('interval')->default(1);
            $table->dateTime('started_at');
            $table->unsignedBigInteger('count')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('key', 200);
            $table->string('types', 150);
            $table->text('value')->nullable();

            $table->index('entity_id');
            $table->unique(['entity_id', 'key']);
        });

        // Taxes
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('name', 150);
            $table->decimal('rate', 15, 4);
            $table->string('type')->default('normal');
            $table->boolean('enabled')->default(1);
            $table->boolean('withholding')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('sell_account')->nullable();
            $table->unsignedBigInteger('buy_account')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        // Transfers
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('expense_transaction_id');
            $table->unsignedBigInteger('income_transaction_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });


        Schema::create('user_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('entity_id')->unsigned();
            $table->string('user_type');

            $table->primary(['user_id', 'entity_id', 'user_type']);
        });

        // Dashboards
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('name', 200);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['entity_id']);
        });

        Schema::create('user_dashboards', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('dashboard_id')->unsigned();
            $table->string('user_type', 20);

            $table->primary(['user_id', 'dashboard_id', 'user_type']);
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('dashboard_id');
            $table->string('class', 200);
            $table->string('name', 150);
            $table->unsignedBigInteger('sort')->default(0);
            $table->text('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['entity_id', 'dashboard_id']);
        });

        // Email templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('alias', 200);
            $table->string('class', 150);
            $table->string('name', 150);
            $table->string('subject', 200);
            $table->text('body');
            $table->text('params')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
            $table->unique(['entity_id', 'alias', 'deleted_at']);
        });

        // Firewall
        Schema::create('firewall_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 100);
            $table->unsignedBigInteger('log_id')->nullable();
            $table->boolean('blocked')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('ip');
            $table->unique(['ip', 'deleted_at']);
        });

        Schema::create('firewall_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 100);
            $table->string('level', 100)->default('medium');
            $table->string('middleware');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('url')->nullable();
            $table->string('referrer')->nullable();
            $table->text('request')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('ip');
        });

        // Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entity_id');
            $table->string('class', 200);
            $table->string('name', 150);
            $table->text('description');
            $table->text('settings')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('entity_id');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->smallInteger('value')->default(0);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('account_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('type')->nullable();
            $table->string('label')->nullable();
            $table->unsignedBigInteger('account_id');
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
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('bill_histories');
        Schema::dropIfExists('bill_items');
        Schema::dropIfExists('bill_item_taxes');
        Schema::dropIfExists('bill_totals');
        Schema::dropIfExists('item_categories');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('old_urrencies');
        Schema::dropIfExists('currency_rates');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_histories');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_item_taxes');
        Schema::dropIfExists('invoice_totals');
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_units');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('module_histories');
        Schema::dropIfExists('notifications');


        Schema::dropIfExists('reconciliations');
        Schema::dropIfExists('recurring');
        Schema::dropIfExists('taxes');
        Schema::dropIfExists('transfers');
        Schema::dropIfExists('user_companies');

        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('user_dashboards');
        Schema::dropIfExists('widgets');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('firewall_ips');
        Schema::dropIfExists('firewall_logs');
        Schema::dropIfExists('reports');

        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payment_terms');
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('account_mappings');
    }
};
