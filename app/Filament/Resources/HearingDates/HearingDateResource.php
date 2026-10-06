<?php

namespace App\Filament\Resources\HearingDates;

use App\Filament\Resources\HearingDates\Pages\CreateHearingDate;
use App\Filament\Resources\HearingDates\Pages\EditHearingDate;
use App\Filament\Resources\HearingDates\Pages\ListHearingDates;
use App\Models\HearingDate;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HearingDateResource extends Resource
{
    protected static ?string $model = HearingDate::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Litigation & Cases';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Cause List & Hearings';
    protected static ?string $modelLabel = 'Hearing Proceeding';
    protected static ?string $pluralModelLabel = 'Hearings & Proceedings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hearing Schedule & Court Room')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('case_id')
                                ->label('Legal Matter / Case')
                                ->relationship('case', 'title')
                                ->searchable()
                                ->preload()
                                ->required(),
                            DatePicker::make('date')
                                ->label('Date of Hearing')
                                ->default(now())
                                ->required(),
                            Select::make('stage_id')
                                ->label('Procedural Stage')
                                ->relationship('stage', 'name')
                                ->preload(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('court_room')->label('Court Room No')->placeholder('Room 402'),
                            TextInput::make('judge_name')->label('Presiding Judge / Bench')->placeholder('Hon. Justice ...'),
                            TextInput::make('advocate_appeared')->label('Advocate Appeared')->placeholder('Lead / Associate'),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->options([
                                    'Scheduled' => 'Scheduled / Listed',
                                    'Completed' => 'Completed / Heard',
                                    'Adjourned' => 'Adjourned',
                                    'Passed Over' => 'Passed Over',
                                ])
                                ->default('Scheduled')
                                ->required(),
                            DatePicker::make('next_date')->label('Next Hearing Date Assigned'),
                        ]),
                        Textarea::make('business_conducted')
                            ->label('Court Proceedings / Orders Passed')
                            ->rows(3),
                        Textarea::make('action_required')
                            ->label('Action Required Before Next Date')
                            ->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Hearing Date')
                    ->date()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('case.case_no')
                    ->label('Case No')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('case.title')
                    ->label('Matter')
                    ->limit(25)
                    ->searchable(),
                TextColumn::make('court_room')
                    ->label('Room')
                    ->toggleable(),
                TextColumn::make('judge_name')
                    ->label('Judge')
                    ->toggleable(),
                TextColumn::make('stage.name')
                    ->label('Stage')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Scheduled' => 'info',
                        'Completed' => 'success',
                        'Adjourned' => 'warning',
                        'Passed Over' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('next_date')
                    ->label('Next Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Scheduled' => 'Scheduled',
                        'Completed' => 'Completed',
                        'Adjourned' => 'Adjourned',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHearingDates::route('/'),
            'create' => CreateHearingDate::route('/create'),
            'edit' => EditHearingDate::route('/{record}/edit'),
        ];
    }
}
