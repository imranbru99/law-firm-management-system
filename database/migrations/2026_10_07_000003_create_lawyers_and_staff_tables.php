<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Lawyers Directory (both internal advocates and external counsels)
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('bar_council_id')->nullable();
            $table->string('designation')->nullable()->default('Senior Counsel');
            $table->string('specialization')->nullable();
            $table->decimal('hourly_rate', 10, 2)->default(0.00);
            $table->boolean('is_external')->default(false);
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        // Staff Records (HR)
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_id')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('department')->nullable()->default('Legal Practice');
            $table->string('designation')->nullable()->default('Associate');
            $table->string('employment_type')->nullable()->default('Full Time'); // Full Time, Part Time, Contract
            $table->date('date_of_joining')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->string('bank_name')->nullable();
            $table->string('bank_branch_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->timestamps();
        });

        // Staff Documents
        Schema::create('staff_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_documents');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('lawyers');
    }
};
