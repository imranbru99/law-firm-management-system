<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add legal profile columns to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('lawyer')->after('email'); // super_admin, partner, lawyer, paralegal, accountant, client
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('bar_registration_no')->nullable()->after('avatar');
            $table->string('specialization')->nullable()->after('bar_registration_no');
            $table->decimal('hourly_rate', 10, 2)->default(0.00)->after('specialization');
            $table->boolean('is_active')->default(true)->after('hourly_rate');
        });

        // Client Categories
        Schema::create('client_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Clients
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // for client portal login
            $table->foreignId('client_category_id')->nullable()->constrained('client_categories')->nullOnDelete();
            $table->string('name');
            $table->string('type')->default('individual'); // individual, corporate
            $table->string('company_name')->nullable();
            $table->string('tax_vat_number')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('gender', 20)->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->text('address')->nullable();
            $table->longText('description')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });

        // Contact Categories (witnesses, opposing counsel, experts, process servers, etc.)
        Schema::create('contact_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_category_id')->nullable()->constrained('contact_categories')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('organization')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
            $table->foreignId('lawyer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('appointment_date');
            $table->text('motive')->nullable();
            $table->string('location_or_link')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, rescheduled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('contact_categories');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('client_categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'avatar',
                'bar_registration_no',
                'specialization',
                'hourly_rate',
                'is_active',
            ]);
        });
    }
};
