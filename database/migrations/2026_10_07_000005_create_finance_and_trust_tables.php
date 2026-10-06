<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Legal Services Catalog
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->decimal('default_rate', 12, 2)->default(0.00);
            $table->string('billing_unit')->default('hour'); // hour, appearance, document, fixed
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Taxes
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rate', 5, 2)->default(0.00); // percentage e.g. 18.00 or 5.00
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Bank Accounts & Client Trust Accounts (IOLTA compliant)
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->string('account_type')->default('Operating Account'); // Operating Account, Client Trust Account (IOLTA), Escrow Account
            $table->string('bank_name');
            $table->string('account_no');
            $table->string('branch')->nullable();
            $table->string('currency')->default('USD');
            $table->decimal('balance', 14, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('currency_symbol', 10)->default('$');
            $table->decimal('sub_total', 14, 2)->default(0.00);
            $table->decimal('discount', 14, 2)->default(0.00);
            $table->string('discount_type')->default('fixed'); // fixed, percentage
            $table->decimal('discount_amount', 14, 2)->default(0.00);
            $table->decimal('net_total', 14, 2)->default(0.00);
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete();
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->decimal('tax_amount', 14, 2)->default(0.00);
            $table->decimal('grand_total', 14, 2)->default(0.00);
            $table->decimal('paid', 14, 2)->default(0.00);
            $table->decimal('due', 14, 2)->default(0.00);
            $table->string('payment_status')->default('due')->index(); // draft, due, partially_paid, paid, overdue
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Invoice Line Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->text('description');
            $table->decimal('qty', 10, 2)->default(1.00);
            $table->decimal('rate', 14, 2)->default(0.00);
            $table->decimal('total', 14, 2)->default(0.00);
            $table->timestamps();
        });

        // Financial Ledger & Payments (Money In / Money Out)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('bank_account_id')->constrained('bank_accounts')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->string('type')->default('in'); // in (payment received), out (disbursement/expense)
            $table->string('payment_method')->default('bank_transfer'); // bank_transfer, cash, credit_card, cheque, trust_transfer
            $table->decimal('amount', 14, 2)->default(0.00);
            $table->date('transaction_date');
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Billable Time Tracking
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('hours', 6, 2)->default(0.00);
            $table->decimal('hourly_rate', 10, 2)->default(0.00);
            $table->decimal('billable_amount', 14, 2)->default(0.00);
            $table->boolean('is_billed')->default(false);
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_entries');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('taxes');
        Schema::dropIfExists('services');
    }
};
