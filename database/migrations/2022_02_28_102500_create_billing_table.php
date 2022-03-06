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
        // Accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('number');
            $table->string('currency_code');
            $table->decimal('opening_balance', 15, 4)->default('0.0000');
            $table->string('bank_name')->nullable();
            $table->string('bank_phone')->nullable();
            $table->text('bank_address')->nullable();
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Bills
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('bill_number');
            $table->string('order_number')->nullable();
            $table->string('status');
            $table->dateTime('billed_at');
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
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'bill_number', 'deleted_at']);
        });

        Schema::create('bill_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bill_id');
            $table->string('status');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->decimal('quantity', 8, 4);
            $table->decimal('price', 15, 4);
            $table->decimal('total', 15, 4);
            $table->float('tax', 15, 4)->default('0.0000');
            $table->decimal('discount_rate', 15, 4)->default('0.0000');
            $table->string('discount_type')->default('normal');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('bill_item_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('name');
            $table->decimal('amount', 15, 4)->default('0.0000');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('bill_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('bill_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->decimal('amount', 15, 4);
            $table->unsignedBigInteger('sort_order');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('type');
            $table->string('color');
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

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

        // Currencies
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('code');
            $table->decimal('rate', 15, 8);
            $table->string('precision')->nullable();
            $table->string('symbol')->nullable();
            $table->unsignedBigInteger('symbol_first')->default(1);
            $table->string('decimal_mark')->nullable();
            $table->string('thousands_separator')->nullable();
            $table->tinyInteger('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'code', 'deleted_at']);
        });

        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->dateTime('currency_rate_date')->nullable();
            $table->string('From_currency_code')->nullable();
            $table->string('To_currency_code')->nullable();
            $table->decimal('average_rate', 20, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('invoice_number');
            $table->string('order_number')->nullable();
            $table->string('status');
            $table->dateTime('invoiced_at');
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
            $table->text('notes')->nullable();
            $table->text('footer')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'invoice_number', 'deleted_at']);
        });

        Schema::create('invoice_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('status_code');
            $table->boolean('notify');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->decimal('quantity', 7, 3);
            $table->decimal('price', 15, 4);
            $table->decimal('total', 15, 4);
            $table->decimal('tax', 15, 4)->default('0.0000');
            $table->decimal('discount_rate', 15, 4)->default('0.0000');
            $table->string('discount_type')->default('normal');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('invoice_item_id');
            $table->unsignedBigInteger('tax_id');
            $table->string('name');
            $table->decimal('amount', 15, 4)->default('0.0000');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('code');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        Schema::create('invoice_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('invoice_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->decimal('amount', 15, 4);
            $table->unsignedBigInteger('sort_order');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Items
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->decimal('sale_price', 15, 4);
            $table->decimal('purchase_price', 15, 4);
            $table->unsignedBigInteger('quantity')->default(1);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('buy_account_id')->nullable();
            $table->unsignedBigInteger('sell_account_id')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Modules
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('alias');
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'alias', 'deleted_at']);
        });

        Schema::create('module_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('module_id');
            $table->string('category');
            $table->string('version');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'module_id']);
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Reconciliations
        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('account_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->decimal('closing_balance', 15, 4)->default('0.0000');
            $table->boolean('reconciled');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Recurring
        Schema::create('recurring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->morphs('recurable');
            $table->string('frequency');
            $table->unsignedBigInteger('interval')->default(1);
            $table->dateTime('started_at');
            $table->unsignedBigInteger('count')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('key');
            $table->string('types');
            $table->text('value')->nullable();

            $table->index('company_id');
            $table->unique(['company_id', 'key']);
        });

        // Taxes
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->decimal('rate', 15, 4);
            $table->string('type')->default('normal');
            $table->boolean('enabled')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('sell_account')->nullable();
            $table->unsignedBigInteger('buy_account')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Transfers
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('expense_transaction_id');
            $table->unsignedBigInteger('income_transaction_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });


        Schema::create('user_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('company_id')->unsigned();
            $table->string('user_type');

            $table->primary(['user_id', 'company_id', 'user_type']);
        });

        // Dashboards
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id']);
        });

        Schema::create('user_dashboards', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->unsignedBigInteger('dashboard_id')->unsigned();
            $table->string('user_type', 20);

            $table->primary(['user_id', 'dashboard_id', 'user_type']);
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('dashboard_id');
            $table->string('class');
            $table->string('name');
            $table->unsignedBigInteger('sort')->default(0);
            $table->text('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'dashboard_id']);
        });

        // Email templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('alias');
            $table->string('class');
            $table->string('name');
            $table->string('subject');
            $table->text('body');
            $table->text('params')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->unique(['company_id', 'alias', 'deleted_at']);
        });

        // Firewall
        Schema::create('firewall_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
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
            $table->string('ip');
            $table->string('level')->default('medium');
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
            $table->unsignedBigInteger('company_id');
            $table->string('class');
            $table->string('name');
            $table->text('description');
            $table->text('settings')->nullable();
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
        });

        // Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('type');
            $table->dateTime('paid_at');
            $table->decimal('amount', 15, 4);
            $table->string('currency_code', 3);
            $table->decimal('currency_rate', 15, 8);
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('category_id')->default(1);
            $table->text('description')->nullable();
            $table->string('payment_method');
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->boolean('reconciled')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index('account_id');
            $table->index('category_id');
            $table->index('contact_id');
            $table->index('document_id');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('enabled')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->morphs('taggable');
        });

        Schema::create('account_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
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
        Schema::drop('accounts');
        Schema::drop('bills');
        Schema::drop('bill_histories');
        Schema::drop('bill_items');
        Schema::drop('bill_item_taxes');
        Schema::drop('bill_totals');
        Schema::drop('categories');
        Schema::drop('companies');
        Schema::drop('currencies');
        Schema::drop('currency_rates');
        Schema::drop('invoices');
        Schema::drop('invoice_histories');
        Schema::drop('invoice_items');
        Schema::drop('invoice_item_taxes');
        Schema::drop('invoice_totals');
        Schema::drop('items');
        Schema::drop('modules');
        Schema::drop('module_histories');
        Schema::drop('notifications');


        Schema::drop('reconciliations');
        Schema::drop('recurring');
        Schema::drop('taxes');
        Schema::drop('transfers');
        Schema::drop('user_companies');

        Schema::drop('dashboards');
        Schema::drop('user_dashboards');
        Schema::drop('widgets');
        Schema::drop('email_templates');
        Schema::drop('firewall_ips');
        Schema::drop('firewall_logs');
        Schema::drop('reports');
        Schema::drop('transactions');

        Schema::drop('payment_methods');
        Schema::drop('payment_terms');
        Schema::drop('product_units');
        Schema::drop('tags');
        Schema::dropIfExists('taggables');
        Schema::drop('account_mappings');
    }
};
