<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Case Categories
        Schema::create('case_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Cases (Core Litigation Record)
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('case_no')->nullable()->index();
            $table->string('file_no')->nullable()->index();
            $table->string('cnr_number')->nullable()->index(); // Modern Court Case Record Number
            $table->integer('year')->nullable();

            // Client & Parties
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('client_role')->default('Plaintiff'); // Plaintiff, Petitioner, Appellant, Defendant, Respondent
            $table->string('opposite_party_name')->nullable();
            $table->foreignId('opposite_client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('opposite_advocate_name')->nullable();

            // Legal Classification & Court
            $table->foreignId('case_category_id')->nullable()->constrained('case_categories')->nullOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('stages')->nullOnDelete();
            $table->foreignId('court_id')->nullable()->constrained('courts')->nullOnDelete();
            $table->foreignId('lead_lawyer_id')->nullable()->constrained('lawyers')->nullOnDelete();

            // Critical Legal Dates
            $table->date('receiving_date')->nullable();
            $table->date('filing_date')->nullable();
            $table->date('hearing_date')->nullable();
            $table->date('next_hearing_date')->nullable()->index();
            $table->date('judgement_date')->nullable();
            $table->date('limitation_date')->nullable(); // Statute of limitation deadline

            // Financials & Jurisdiction
            $table->string('jurisdiction')->nullable()->default('Federal / Commercial');
            $table->string('currency', 10)->default('USD');
            $table->string('currency_symbol', 10)->default('$');
            $table->decimal('case_charge', 14, 2)->default(0.00);
            $table->string('billing_type')->default('fixed'); // fixed, hourly, contingency, retainer

            // Status & Priority
            $table->string('status')->default('Open')->index(); // Open, Hearing, Reserved, Judgement, Closed, Reopened, Appealed
            $table->string('priority')->default('Medium'); // Low, Medium, High, Urgent

            // References
            $table->string('ref_name')->nullable();
            $table->string('ref_mobile')->nullable();

            // Narrative & Pleadings
            $table->longText('description')->nullable();
            $table->longText('prayer')->nullable(); // Relief sought
            $table->longText('judgement')->nullable();
            $table->string('judgement_status')->default('Pending');

            $table->timestamps();
        });

        // Assigned Lawyers Pivot
        Schema::create('case_lawyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('lawyer_id')->constrained('lawyers')->cascadeOnDelete();
            $table->string('role')->default('Co-Counsel'); // Lead Counsel, Co-Counsel, Briefing Counsel
            $table->timestamps();
        });

        // Bare Acts & Sections Applied
        Schema::create('case_acts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('act_id')->constrained('acts')->cascadeOnDelete();
            $table->string('sections')->nullable(); // e.g., "Section 138, 142"
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Connected Matters (Linked Suits / Companion Petitions / Appeals)
        Schema::create('connected_matters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('connected_case_id')->constrained('cases')->cascadeOnDelete();
            $table->string('relationship_type')->default('Companion Matter'); // Cross Petition, Companion Matter, Appeal, Review
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Hearing Dates History & Proceedings Log
        Schema::create('hearing_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('stages')->nullOnDelete();
            $table->date('date');
            $table->string('court_room')->nullable();
            $table->string('judge_name')->nullable();
            $table->string('advocate_appeared')->nullable();
            $table->longText('business_conducted')->nullable();
            $table->date('next_date')->nullable();
            $table->text('action_required')->nullable();
            $table->string('status')->default('Completed'); // Scheduled, Completed, Adjourned, Passed Over
            $table->timestamps();
        });

        // Judgments & Orders
        Schema::create('judgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->date('judgment_date');
            $table->string('judge_name')->nullable();
            $table->string('disposition')->default('Decreed'); // Decreed, Dismissed, Partly Allowed, Settled, Remanded
            $table->text('order_summary')->nullable();
            $table->longText('full_order_text')->nullable();
            $table->string('order_file')->nullable();
            $table->boolean('is_appeal_recommended')->default(false);
            $table->timestamps();
        });

        // Case Notes (Internal Advocate Notes)
        Schema::create('case_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->longText('body');
            $table->boolean('is_private')->default(false);
            $table->string('tags')->nullable();
            $table->timestamps();
        });

        // Case Documents & Evidence Vault
        Schema::create('case_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->cascadeOnDelete();
            $table->foreignId('hearing_date_id')->nullable()->constrained('hearing_dates')->nullOnDelete();
            $table->string('title');
            $table->string('document_type')->default('Pleadings'); // Pleadings, Evidence, Order, Vakalatnama, Notice, Brief
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->longText('ocr_text')->nullable(); // Extracted OCR content for full-text search & AI
            $table->boolean('is_confidential')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_documents');
        Schema::dropIfExists('case_notes');
        Schema::dropIfExists('judgments');
        Schema::dropIfExists('hearing_dates');
        Schema::dropIfExists('connected_matters');
        Schema::dropIfExists('case_acts');
        Schema::dropIfExists('case_lawyer');
        Schema::dropIfExists('cases');
        Schema::dropIfExists('case_categories');
    }
};
