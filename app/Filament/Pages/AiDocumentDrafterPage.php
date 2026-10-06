<?php

namespace App\Filament\Pages;

use App\Models\AiDraft;
use App\Models\LegalCase;
use App\Services\LegalAiService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AiDocumentDrafterPage extends Page
{
    protected static string | UnitEnum | null $navigationGroup = '2027 AI Legal Suite';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'AI Document Drafter';
    protected static ?string $title = 'AI Legal Document & Notice Drafter';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected string $view = 'filament.pages.ai-document-drafter';

    public string $templateType = 'Legal Notice';
    public ?int $caseId = null;
    public string $promptInput = '';
    public string $generatedDraft = '';
    public array $extractedClauses = [];
    public bool $isGenerating = false;

    public function mount(): void
    {
        $this->promptInput = 'Default in loan repayment of $120,000 under promissory note dated Jan 15, 2025. Demand repayment within 15 days failing which suit for recovery and criminal breach of trust will be filed.';
    }

    public function generate(): void
    {
        if (empty(trim($this->promptInput))) {
            Notification::make()->title('Please enter factual details and prompt instructions')->warning()->send();
            return;
        }

        $this->isGenerating = true;

        $case = $this->caseId ? LegalCase::find($this->caseId) : null;
        $aiService = app(LegalAiService::class);
        $result = $aiService->draftDocument($this->templateType, $this->promptInput, $case);

        $this->generatedDraft = $result['content'];
        $this->extractedClauses = $result['clauses'];

        // Persist to ai_drafts
        AiDraft::create([
            'user_id' => auth()->id(),
            'case_id' => $this->caseId,
            'template_type' => $this->templateType,
            'prompt_input' => $this->promptInput,
            'generated_content' => $this->generatedDraft,
            'key_clauses' => $this->extractedClauses,
            'status' => 'reviewed',
        ]);

        $this->isGenerating = false;

        Notification::make()
            ->title('AI Legal Draft Generated Successfully')
            ->body('Draft parsed and saved to firm archives.')
            ->success()
            ->send();
    }
}
