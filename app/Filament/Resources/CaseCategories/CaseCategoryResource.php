<?php

namespace App\Filament\Resources\CaseCategories;

use App\Filament\Resources\CaseCategories\Pages\CreateCaseCategory;
use App\Filament\Resources\CaseCategories\Pages\EditCaseCategory;
use App\Filament\Resources\CaseCategories\Pages\ListCaseCategories;
use App\Models\CaseCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CaseCategoryResource extends Resource
{
    protected static ?string $model = CaseCategory::class;

    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Practice Areas';
    protected static ?string $modelLabel = 'Practice Area';
    protected static ?string $pluralModelLabel = 'Practice Areas & Specializations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Practice Area')
                    ->schema([
                        TextInput::make('name')->required(),
                        Textarea::make('description')->rows(3)->placeholder('Domain description, scope of practice'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->weight('bold')->searchable(),
                TextColumn::make('description')->limit(50),
                TextColumn::make('cases_count')->counts('cases')->label('Matters Count')->badge()->color('primary'),
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
            'index' => ListCaseCategories::route('/'),
            'create' => CreateCaseCategory::route('/create'),
            'edit' => EditCaseCategory::route('/{record}/edit'),
        ];
    }
}
