<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Daily Staff Attendance
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->string('status')->default('Present'); // Present, Absent, Late, Half Day, Holiday, Leave
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'date']);
        });

        // Leave Types (Annual, Casual, Sick, Court Vacation)
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days_allowed')->default(12);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Leave Requests
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 4, 1)->default(1.0);
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // Payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->string('month'); // e.g. "October"
            $table->integer('year'); // e.g. 2026
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->decimal('earnings', 12, 2)->default(0.00); // bonuses, incentives
            $table->decimal('deductions', 12, 2)->default(0.00); // tax, provident, unpaid leave
            $table->decimal('gross_salary', 12, 2)->default(0.00);
            $table->decimal('net_salary', 12, 2)->default(0.00);
            $table->string('status')->default('draft'); // draft, generated, paid
            $table->date('payment_date')->nullable();
            $table->string('payment_mode')->nullable()->default('bank_transfer');
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['staff_id', 'month', 'year']);
        });

        // Tasks (Action items linked to cases and legal stages)
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->nullable()->constrained('cases')->cascadeOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('stages')->nullOnDelete();
            $table->string('title');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('due_date')->nullable();
            $table->string('priority')->default('Medium'); // Low, Medium, High, Urgent
            $table->integer('progress')->default(0); // 0-100%
            $table->string('status')->default('pending'); // pending, in_progress, review, completed
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        // Personal To-Dos
        Schema::create('to_dos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->date('due_date')->nullable();
            $table->string('priority')->default('Normal'); // Low, Normal, High
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('to_dos');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('attendances');
    }
};
