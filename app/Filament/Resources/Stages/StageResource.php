<?php

namespace App\Filament\Resources\Stages;

use App\Filament\Resources\Stages\Pages\CreateStage;
use App\Filament\Resources\Stages\Pages\EditStage;
use App\Filament\Resources\Stages\Pages\ListStages;
use App\Models\Stage;
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
use UnitEnum;

class StageResource extends Resource
{
    protected static ?string $model = Stage::class;

    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Litigation Stages';
    protected static ?string $modelLabel = 'Procedural Stage';
    protected static ?string $pluralModelLabel = 'Litigation Stages & Workflow';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Procedural Stage Definition')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')->required(),
                            TextInput::make('order')->numeric()->default(1)->required(),
                            Select::make('color')
                                ->options([
                                    'primary' => 'Blue (Primary)',
                                    'success' => 'Green (Success)',
                                    'warning' => 'Yellow / Amber (Warning)',
                                    'danger' => 'Red (Urgent / Danger)',
                                    'info' => 'Cyan (Info)',
                                    'gray' => 'Gray (Neutral)',
                                ])
                                ->default('primary'),
                        ]),
                        Textarea::make('description')->rows(2)->placeholder('Court expectations, evidence checklist, statutory time limits'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')->sortable()->weight('bold'),
                TextColumn::make('name')->badge()->color(fn (Stage $record) => $record->color ?? 'primary')->searchable(),
                TextColumn::make('description')->limit(40),
                TextColumn::make('cases_count')->counts('cases')->label('Cases at Stage')->badge()->color('success'),
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
            'index' => ListStages::route('/'),
            'create' => CreateStage::route('/create'),
            'edit' => EditStage::route('/{record}/edit'),
        ];
    }
}
