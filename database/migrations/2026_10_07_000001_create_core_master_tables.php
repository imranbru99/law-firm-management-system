<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Firm Settings
        Schema::create('firm_settings', function (Blueprint $table) {
            $table->id();
            $table->string('firm_name')->default('LexVanguard Legal Partners');
            $table->string('tagline')->nullable()->default('Excellence in Jurisprudence & Litigation');
            $table->string('email')->nullable()->default('contact@lexvanguard.law');
            $table->string('phone')->nullable()->default('+1 (800) 555-LEGAL');
            $table->text('address')->nullable()->default('100 Chancery Lane, Legal Precinct');
            $table->string('registration_no')->nullable()->default('LLP-2024-9842');
            $table->string('tax_vat_number')->nullable()->default('VAT-US-992104');
            $table->string('currency')->default('USD');
            $table->string('currency_symbol')->default('$');
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('Y-m-d');
            $table->string('logo_url')->nullable();
            $table->string('ai_model')->default('gemini-1.5-pro');
            $table->text('invoice_footer')->nullable()->default('Thank you for choosing our legal counsel.');
            $table->timestamps();
        });

        // Countries
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 5)->nullable();
            $table->string('phonecode', 10)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('currency_symbol', 10)->default('$');
            $table->string('flag', 10)->nullable();
            $table->string('region')->nullable();
            $table->timestamps();
        });

        // States
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        // Cities / Districts
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        // Court Categories
        Schema::create('court_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Courts
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('court_category_id')->nullable()->constrained('court_categories')->nullOnDelete();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('bench')->nullable();
            $table->string('room_number')->nullable();
            $table->string('location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Bare Acts / Statutes
        Schema::create('acts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->integer('year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Case Stages
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->string('color')->default('primary');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stages');
        Schema::dropIfExists('acts');
        Schema::dropIfExists('courts');
        Schema::dropIfExists('court_categories');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('firm_settings');
    }
};
