<?php

namespace App\Filament\Pages;

use App\Models\AiRiskAnalysis;
use App\Services\LegalAiService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AiContractRiskAnalyzerPage extends Page
{
    protected static string | UnitEnum | null $navigationGroup = '2027 AI Legal Suite';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Contract Risk & Clause AI';
    protected static ?string $title = 'Contract & Clause Risk Analyzer';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected string $view = 'filament.pages.ai-contract-risk-analyzer';

    public string $documentTitle = 'Master Professional Services Agreement';
    public string $contractText = '';
    public int $riskScore = 0;
    public array $detectedClauses = [];
    public string $recommendations = '';
    public bool $hasAnalyzed = false;

    public function mount(): void
    {
        $this->contractText = <<<CONTRACT
1. INDEMNIFICATION: The Service Provider shall indemnify, defend and hold harmless the Client, its affiliates, directors and agents without limitation from and against any and all claims, liabilities, losses, damages, costs and expenses arising out of or related to this Agreement.
2. TERMINATION: The Client may terminate this Agreement at convenience and with immediate effect without cause upon email notification.
3. GOVERNING LAW: This Agreement shall be governed by applicable laws, and courts shall have non-exclusive jurisdiction.
CONTRACT;
    }

    public function analyze(): void
    {
        if (empty(trim($this->contractText))) {
            Notification::make()->title('Please paste contract clauses to analyze')->warning()->send();
            return;
        }

        $aiService = app(LegalAiService::class);
        $result = $aiService->analyzeContractRisk($this->contractText);

        $this->riskScore = $result['risk_score'];
        $this->detectedClauses = $result['detected_clauses'];
        $this->recommendations = $result['recommendations'];
        $this->hasAnalyzed = true;

        AiRiskAnalysis::create([
            'user_id' => auth()->id(),
            'title' => $this->documentTitle,
            'source_text' => $this->contractText,
            'risk_score' => $this->riskScore,
            'detected_clauses' => $this->detectedClauses,
            'recommendations' => $this->recommendations,
        ]);

        Notification::make()
            ->title('Risk Assessment Complete')
            ->body("Evaluated risk score: {$this->riskScore}/100")
            ->success()
            ->send();
    }
}
