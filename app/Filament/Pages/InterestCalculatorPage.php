<?php

namespace App\Filament\Pages;

use App\Models\InterestCalculation;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class InterestCalculatorPage extends Page
{
    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Decree Interest Calculator';
    protected static ?string $title = 'Decree & Claim Interest Calculator';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected string $view = 'filament.pages.interest-calculator';

    public float $principalAmount = 100000.00;
    public float $interestRate = 12.00; // % per annum
    public string $startDate = '';
    public string $endDate = '';
    public string $interestType = 'simple'; // simple, compound_quarterly, compound_annually
    public float $accruedInterest = 0.00;
    public float $totalAmount = 0.00;
    public int $totalDays = 0;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->subYear()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->calculate();
    }

    public function calculate(): void
    {
        $p = max(0, $this->principalAmount);
        $r = max(0, $this->interestRate) / 100;
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        if ($end->lessThan($start)) {
            $end = $start->copy();
        }

        $this->totalDays = $start->diffInDays($end);
        $t = $this->totalDays / 365.25;

        if ($this->interestType === 'simple') {
            $interest = $p * $r * $t;
        } elseif ($this->interestType === 'compound_quarterly') {
            $interest = ($p * pow((1 + ($r / 4)), (4 * $t))) - $p;
        } else {
            // annually
            $interest = ($p * pow((1 + $r), $t)) - $p;
        }

        $this->accruedInterest = round($interest, 2);
        $this->totalAmount = round($p + $interest, 2);

        InterestCalculation::create([
            'principal_amount' => $this->principalAmount,
            'interest_rate' => $this->interestRate,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'interest_type' => $this->interestType,
            'accrued_interest' => $this->accruedInterest,
            'total_amount' => $this->totalAmount,
        ]);
    }
}
