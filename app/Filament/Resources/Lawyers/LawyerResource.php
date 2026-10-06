<?php

namespace App\Filament\Resources\Lawyers;

use App\Filament\Resources\Lawyers\Pages\CreateLawyer;
use App\Filament\Resources\Lawyers\Pages\EditLawyer;
use App\Filament\Resources\Lawyers\Pages\ListLawyers;
use App\Models\Lawyer;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class LawyerResource extends Resource
{
    protected static ?string $model = Lawyer::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Practice Operations';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Advocates & Counsels';
    protected static ?string $modelLabel = 'Advocate';
    protected static ?string $pluralModelLabel = 'Advocates & Counsels';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Advocate Profile & Credentials')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')->required(),
                            TextInput::make('designation')->default('Senior Advocate / Partner'),
                            TextInput::make('bar_council_id')->label('Bar Enrollment / Bar No'),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('email')->email(),
                            TextInput::make('mobile_no')->tel(),
                            TextInput::make('hourly_rate')->numeric()->prefix('$')->default(250),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('specialization')->placeholder('e.g. Constitutional Law, White Collar Crime, Corporate M&A'),
                            Toggle::make('is_external')->label('External / Retained Senior Counsel')->default(false),
                        ]),
                        Select::make('user_id')
                            ->label('Linked System User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        Textarea::make('description')->label('Biography & Notable Judgments')->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->weight('bold')->searchable()->sortable(),
                TextColumn::make('designation')->badge()->color('primary'),
                TextColumn::make('bar_council_id')->label('Bar No')->toggleable(),
                TextColumn::make('specialization')->limit(25),
                TextColumn::make('hourly_rate')->money('USD'),
                IconColumn::make('is_external')->boolean()->label('External'),
                TextColumn::make('lead_cases_count')->counts('leadCases')->label('Lead Matters')->badge()->color('success'),
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
            'index' => ListLawyers::route('/'),
            'create' => CreateLawyer::route('/create'),
            'edit' => EditLawyer::route('/{record}/edit'),
        ];
    }
}
