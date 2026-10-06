<?php

namespace App\Filament\Pages;

use App\Models\LegalCase;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CauseListDailyBoardPage extends Page
{
    protected static string | UnitEnum | null $navigationGroup = 'Litigation & Cases';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationLabel = 'Daily Board / Cause List';
    protected static ?string $title = 'Court Display Board & Daily Cause List';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTv;

    protected string $view = 'filament.pages.cause-list-daily-board';

    public string $selectedDate = '';
    public string $activeFilter = 'today'; // today, tomorrow, week, awaited

    public function mount(): void
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function setFilter(string $filter): void
    {
        $this->activeFilter = $filter;
        if ($filter === 'today') {
            $this->selectedDate = Carbon::today()->format('Y-m-d');
        } elseif ($filter === 'tomorrow') {
            $this->selectedDate = Carbon::tomorrow()->format('Y-m-d');
        }
    }

    public function getCasesProperty()
    {
        $query = LegalCase::with(['client', 'court', 'stage', 'leadLawyer']);

        if ($this->activeFilter === 'today') {
            return $query->whereDate('next_hearing_date', Carbon::today())->get();
        }

        if ($this->activeFilter === 'tomorrow') {
            return $query->whereDate('next_hearing_date', Carbon::tomorrow())->get();
        }

        if ($this->activeFilter === 'week') {
            return $query->whereBetween('next_hearing_date', [Carbon::today(), Carbon::today()->addDays(7)])
                ->orderBy('next_hearing_date', 'asc')
                ->get();
        }

        if ($this->activeFilter === 'awaited') {
            return $query->whereNull('next_hearing_date')
                ->where('status', '!=', 'Closed')
                ->get();
        }

        return $query->whereDate('next_hearing_date', $this->selectedDate)->get();
    }
}
