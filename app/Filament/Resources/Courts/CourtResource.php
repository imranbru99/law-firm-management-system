<?php

namespace App\Filament\Resources\Courts;

use App\Filament\Resources\Courts\Pages\CreateCourt;
use App\Filament\Resources\Courts\Pages\EditCourt;
use App\Filament\Resources\Courts\Pages\ListCourts;
use App\Models\Court;
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

class CourtResource extends Resource
{
    protected static ?string $model = Court::class;

    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Courts & Tribunals';
    protected static ?string $modelLabel = 'Court / Tribunal';
    protected static ?string $pluralModelLabel = 'Courts, Benches & Tribunals';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Court Details & Jurisdiction')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')->label('Court Full Title')->required(),
                            Select::make('court_category_id')
                                ->label('Hierarchy / Forum Type')
                                ->relationship('category', 'name')
                                ->preload()
                                ->required(),
                            TextInput::make('bench')->label('Bench Division')->placeholder('Single Bench / Division Bench'),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('room_number')->placeholder('Court Room No 12'),
                            TextInput::make('location')->placeholder('High Court Complex'),
                            TextInput::make('phone')->tel(),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('country_id')->relationship('country', 'name')->preload(),
                            Select::make('state_id')->relationship('state', 'name')->preload(),
                            Select::make('city_id')->relationship('city', 'name')->preload(),
                        ]),
                        Textarea::make('description')->rows(2)->placeholder('E-filing portal details, registry timings'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->weight('bold')->searchable(),
                TextColumn::make('category.name')->label('Category')->badge()->color('primary'),
                TextColumn::make('bench')->toggleable(),
                TextColumn::make('room_number')->label('Room')->toggleable(),
                TextColumn::make('location')->limit(25),
                TextColumn::make('cases_count')->counts('cases')->label('Active Matters')->badge()->color('success'),
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
            'index' => ListCourts::route('/'),
            'create' => CreateCourt::route('/create'),
            'edit' => EditCourt::route('/{record}/edit'),
        ];
    }
}
