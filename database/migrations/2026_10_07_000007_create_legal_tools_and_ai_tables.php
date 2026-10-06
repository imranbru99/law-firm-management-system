<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Conflict of Interest Checker
        Schema::create('conflict_checks', function (Blueprint $table) {
            $table->id();
            $table->string('party_name')->index();
            $table->text('matter_description')->nullable();
            $table->foreignId('searched_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('verdict')->default('Cleared'); // Cleared, Potential Conflict, Direct Conflict
            $table->json('potential_matches')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Statutory Court Fee Calculator History
        Schema::create('court_fee_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('court_id')->nullable()->constrained('courts')->nullOnDelete();
            $table->string('suit_type'); // Money Suit, Injunction, Partition, Declaratory, Eviction, Arbitration
            $table->decimal('claim_amount', 14, 2)->default(0.00);
            $table->decimal('calculated_fee', 14, 2)->default(0.00);
            $table->string('formula_applied')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Decree & Claim Interest Calculator History (Section 34 CPC / Commercial rates)
        Schema::create('interest_calculations', function (Blueprint $table) {
            $table->id();
            $table->decimal('principal_amount', 14, 2);
            $table->decimal('interest_rate', 5, 2); // % per annum
            $table->date('start_date');
            $table->date('end_date');
            $table->string('interest_type')->default('simple'); // simple, compound_quarterly, compound_annually
            $table->decimal('accrued_interest', 14, 2);
            $table->decimal('total_amount', 14, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2027 AI Legal Document Drafts
        Schema::create('ai_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('case_id')->nullable()->constrained('cases')->nullOnDelete();
            $table->string('template_type'); // Legal Notice, Bail Application, Non-Disclosure Agreement, Vakalatnama, Plaint, Written Statement, Demand Letter
            $table->text('prompt_input');
            $table->longText('generated_content');
            $table->json('key_clauses')->nullable();
            $table->string('status')->default('draft'); // draft, reviewed, finalized
            $table->timestamps();
        });

        // 2027 AI Contract & Clause Risk Analyzer
        Schema::create('ai_risk_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('document_type')->default('Commercial Agreement');
            $table->longText('source_text');
            $table->integer('risk_score')->default(0); // 0-100 (0 low risk, 100 high risk)
            $table->json('detected_clauses')->nullable(); // indemnity, jurisdiction, limitation of liability, arbitration
            $table->longText('recommendations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_risk_analyses');
        Schema::dropIfExists('ai_drafts');
        Schema::dropIfExists('interest_calculations');
        Schema::dropIfExists('court_fee_calculations');
        Schema::dropIfExists('conflict_checks');
    }
};
