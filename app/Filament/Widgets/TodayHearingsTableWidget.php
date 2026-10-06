<?php

namespace App\Filament\Widgets;

use App\Models\LegalCase;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayHearingsTableWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = "Today's Court Appearances & Daily Board";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LegalCase::query()
                    ->whereDate('next_hearing_date', Carbon::today())
                    ->orWhere(function ($q) {
                        $q->whereBetween('next_hearing_date', [Carbon::today(), Carbon::today()->addDays(3)])
                            ->where('priority', 'Urgent');
                    })
                    ->with(['client', 'court', 'stage', 'leadLawyer'])
            )
            ->columns([
                TextColumn::make('case_no')->label('Case No')->weight('bold'),
                TextColumn::make('title')->label('Matter / Cause')->limit(30),
                TextColumn::make('court.name')->label('Forum / Court'),
                TextColumn::make('court.room_number')->label('Room / Bench'),
                TextColumn::make('stage.name')->label('Stage')->badge()->color('primary'),
                TextColumn::make('leadLawyer.name')->label('Appearing Counsel'),
                TextColumn::make('priority')->badge()->color(fn (string $state): string => match ($state) {
                    'Urgent' => 'danger',
                    default => 'warning',
                }),
            ])
            ->emptyStateHeading('No Urgent Hearings Listed Today')
            ->emptyStateDescription('All upcoming court proceedings are scheduled for future dates.');
    }
}
