<?php

namespace App\Filament\Pages;

use App\Models\LegalCase;
use App\Services\LegalAiService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AiCopilotPage extends Page
{
    protected static string | UnitEnum | null $navigationGroup = '2027 AI Legal Suite';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'AI Legal Copilot 2027';
    protected static ?string $title = 'Autonomous Legal Copilot & Judicial Intelligence';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected string $view = 'filament.pages.ai-copilot';

    public string $mode = 'research';
    public string $jurisdiction = 'US Federal / Delaware Chancery Court';
    public ?int $caseId = null;
    public string $query = '';
    public string $targetWitness = '';
    public string $adverseTheory = '';
    public string $responseMarkdown = '';
    public bool $isProcessing = false;

    public array $jurisdictionOptions = [
        'US Federal / Delaware Chancery Court' => '🇺🇸 United States (Delaware Court of Chancery & Federal SDNY)',
        'UK Commercial Court & English High Court' => '🇬🇧 United Kingdom (Business & Property Courts / English Common Law)',
        'UAE & DIFC Courts (Dubai International Financial Centre)' => '🇦🇪 United Arab Emirates (DIFC Courts & ADGM Common Law)',
        'Singapore SICC & SIAC International Arbitration' => '🇸🇬 Singapore (Singapore International Commercial Court & SIAC)',
        'Supreme Court & High Courts of India' => '🇮🇳 India (Supreme Court & High Court Commercial Division)',
        'Federal Court of Australia & NSW Commercial List' => '🇦🇺 Australia (Federal Court & NSW Commercial Division)',
        'Ontario Superior Court of Justice / Commercial List' => '🇨🇦 Canada (Ontario Superior Court & Federal Court)',
        'EU Cross-Border Commercial & Trade Court' => '🇪🇺 European Union (CJEU & Cross-Border Commercial Regulations)',
        'ICC International Court of Arbitration (Paris / Geneva)' => '🌐 Global (ICC / LCIA / UNCITRAL Institutional Arbitration)',
    ];

    public function mount(): void
    {
        $this->query = 'Whether failure to deliver audited cloud infrastructure metrics within 30 days constitutes an incurable material breach justifying immediate termination under Delaware commercial jurisprudence.';
    }

    public function setMode(string $newMode): void
    {
        $this->mode = $newMode;
        if ($newMode === 'cross_exam' && empty($this->targetWitness)) {
            $this->targetWitness = 'Chief Technology Officer (Adverse Party)';
            $this->adverseTheory = 'Witness claims system downtime was caused by third-party force majeure cloud failure rather than internal negligence.';
            $this->query = 'Inquire into access logs, vendor SLA escalation emails, and delayed executive notifications.';
        } elseif ($newMode === 'objections') {
            $this->query = 'Opposing counsel introducing unauthenticated WhatsApp chat screenshots and asking witness what their contractor meant.';
        } elseif ($newMode === 'precedents') {
            $this->query = 'Liquidated damages versus penalty clause enforceability in multi-jurisdictional SaaS enterprise contracts.';
        }
    }

    public function askCopilot(): void
    {
        if (empty(trim($this->query))) {
            Notification::make()->title('Please enter your research inquiry or factual proposition')->warning()->send();
            return;
        }

        $this->isProcessing = true;

        $case = $this->caseId ? LegalCase::find($this->caseId) : null;
        $aiService = app(LegalAiService::class);

        $enhancedQuery = $this->query;
        if ($this->mode === 'cross_exam') {
            $enhancedQuery .= " | Witness: {$this->targetWitness} | Adverse Claim: {$this->adverseTheory}";
        }

        $result = $aiService->runLegalCopilot($enhancedQuery, $this->mode, $this->jurisdiction, $case);

        $this->responseMarkdown = $result;
        $this->isProcessing = false;

        Notification::make()
            ->title('AI Legal Intelligence Synthesized')
            ->body("Analysis generated for {$this->jurisdiction}")
            ->success()
            ->send();
    }

    public function getCasesProperty()
    {
        return LegalCase::latest()->limit(50)->get(['id', 'case_number', 'title']);
    }
}
