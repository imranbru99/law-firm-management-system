<?php

namespace App\Filament\Resources\TimeEntries;

use App\Filament\Resources\TimeEntries\Pages\CreateTimeEntry;
use App\Filament\Resources\TimeEntries\Pages\EditTimeEntry;
use App\Filament\Resources\TimeEntries\Pages\ListTimeEntries;
use App\Models\TimeEntry;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TimeEntryResource extends Resource
{
    protected static ?string $model = TimeEntry::class;

    protected static string | UnitEnum | null $navigationGroup = 'Finance & Trust (IOLTA)';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Billable Time Tracking';
    protected static ?string $modelLabel = 'Time Entry';
    protected static ?string $pluralModelLabel = 'Billable Hours & Time Tracking';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Billable Time Log')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('case_id')
                                ->label('Legal Matter')
                                ->relationship('case', 'title')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('user_id')
                                ->label('Timekeeper / Advocate')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->default(fn () => auth()->id())
                                ->required(),
                            DatePicker::make('date')->default(now())->required(),
                        ]),
                        Grid::make(4)->schema([
                            TextInput::make('hours')->numeric()->default(1.0)->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('billable_amount', $state * $get('hourly_rate'))),
                            TextInput::make('hourly_rate')->numeric()->prefix('$')->default(250)->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('billable_amount', $state * $get('hours'))),
                            TextInput::make('billable_amount')->numeric()->prefix('$')->disabled()->dehydrated(),
                            Toggle::make('is_billed')->label('Billed to Invoice')->default(false),
                        ]),
                        Textarea::make('description')
                            ->label('Activity Description (Legal Research, Drafting, Court Appearance)')
                            ->required()
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->date()->sortable()->weight('bold'),
                TextColumn::make('case.case_no')->label('Matter')->searchable(),
                TextColumn::make('user.name')->label('Timekeeper')->searchable(),
                TextColumn::make('hours')->suffix(' hrs')->sortable(),
                TextColumn::make('hourly_rate')->money('USD'),
                TextColumn::make('billable_amount')->money('USD')->weight('bold')->sortable(),
                IconColumn::make('is_billed')->boolean()->label('Invoiced'),
                TextColumn::make('description')->limit(30),
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
            'index' => ListTimeEntries::route('/'),
            'create' => CreateTimeEntry::route('/create'),
            'edit' => EditTimeEntry::route('/{record}/edit'),
        ];
    }
}
