<?php

namespace App\Filament\Resources\ConflictChecks;

use App\Filament\Resources\ConflictChecks\Pages\CreateConflictCheck;
use App\Filament\Resources\ConflictChecks\Pages\EditConflictCheck;
use App\Filament\Resources\ConflictChecks\Pages\ListConflictChecks;
use App\Models\ConflictCheck;
use App\Services\LegalAiService;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConflictCheckResource extends Resource
{
    protected static ?string $model = ConflictCheck::class;

    protected static string | \UnitEnum | null $navigationGroup = '2027 AI Legal Suite';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Conflict of Interest AI';
    protected static ?string $modelLabel = 'Conflict Check';
    protected static ?string $pluralModelLabel = 'Conflict of Interest Audits';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Adverse Entity & Conflict Scan')
                    ->description('Scans existing clients, opposing parties in active litigation, witnesses, and opposing counsels to protect ethical compliance.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('party_name')
                                ->label('Party / Entity / Individual Name to Screen')
                                ->placeholder('e.g. Acme Corporation or John Doe')
                                ->required(),
                            Select::make('verdict')
                                ->options([
                                    'Cleared' => 'Cleared - No Conflict',
                                    'Potential Conflict' => 'Potential Conflict - Disclose',
                                    'Direct Conflict' => 'Direct Conflict - Do Not Retain',
                                ])
                                ->default('Cleared')
                                ->required(),
                        ]),
                        Textarea::make('matter_description')
                            ->label('Prospective Matter Description & Scope')
                            ->rows(2),
                        Textarea::make('notes')
                            ->label('Ethics Committee Notes / Mitigation')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('party_name')
                    ->label('Screened Party')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('verdict')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Cleared' => 'success',
                        'Potential Conflict' => 'warning',
                        'Direct Conflict' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')
                    ->label('Audited By')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Audit Date')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ListConflictChecks::route('/'),
            'create' => CreateConflictCheck::route('/create'),
            'edit' => EditConflictCheck::route('/{record}/edit'),
        ];
    }
}
