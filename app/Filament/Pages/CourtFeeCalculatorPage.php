<?php

namespace App\Filament\Pages;

use App\Models\CourtFeeCalculation;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CourtFeeCalculatorPage extends Page
{
    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Court Fee Calculator';
    protected static ?string $title = 'Statutory Court Fee Calculator';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    protected string $view = 'filament.pages.court-fee-calculator';

    public string $suitType = 'Money Suit for Recovery';
    public float $claimAmount = 250000.00;
    public float $calculatedFee = 0.00;
    public string $formulaApplied = '';
    public bool $hasCalculated = false;

    public function mount(): void
    {
        $this->calculate();
    }

    public function calculate(): void
    {
        $val = max(0, $this->claimAmount);

        switch ($this->suitType) {
            case 'Money Suit for Recovery':
            case 'Suit for Damages / Breach of Contract':
                // Progressive ad-valorem schedule
                if ($val <= 10000) {
                    $fee = $val * 0.075;
                    $formula = '7.5% on value up to $10,000';
                } elseif ($val <= 50000) {
                    $fee = 750 + (($val - 10000) * 0.05);
                    $formula = '$750 + 5.0% on excess over $10,000 up to $50,000';
                } elseif ($val <= 200000) {
                    $fee = 2750 + (($val - 50000) * 0.025);
                    $formula = '$2,750 + 2.5% on excess over $50,000 up to $200,000';
                } else {
                    $fee = 6500 + (($val - 200000) * 0.015);
                    $formula = '$6,500 + 1.5% on excess over $200,000 (Subject to statutory caps)';
                }
                break;

            case 'Suit for Permanent Injunction':
                $fee = 250.00;
                $formula = 'Fixed statutory injunction court fee per relief';
                break;

            case 'Declaratory Decree without Consequential Relief':
                $fee = 350.00;
                $formula = 'Fixed statutory declaration fee';
                break;

            case 'Partition Suit':
                $fee = 500.00 + ($val * 0.01);
                $formula = '$500 fixed + 1% ad-valorem on plaintiff’s undivided share';
                break;

            default:
                $fee = $val * 0.02;
                $formula = 'Standard 2% ad-valorem rate';
                break;
        }

        $this->calculatedFee = round($fee, 2);
        $this->formulaApplied = $formula;
        $this->hasCalculated = true;

        CourtFeeCalculation::create([
            'suit_type' => $this->suitType,
            'claim_amount' => $this->claimAmount,
            'calculated_fee' => $this->calculatedFee,
            'formula_applied' => $this->formulaApplied,
        ]);
    }
}
