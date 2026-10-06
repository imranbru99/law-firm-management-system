<?php

namespace App\Filament\Resources\Acts;

use App\Filament\Resources\Acts\Pages\CreateAct;
use App\Filament\Resources\Acts\Pages\EditAct;
use App\Filament\Resources\Acts\Pages\ListActs;
use App\Models\Act;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ActResource extends Resource
{
    protected static ?string $model = Act::class;

    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Bare Acts & Statutes';
    protected static ?string $modelLabel = 'Bare Act / Statute';
    protected static ?string $pluralModelLabel = 'Bare Acts & Substantive Laws';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Statutory Law Reference')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')->label('Act Full Title')->placeholder('Code of Civil Procedure')->required()->columnSpan(2),
                            TextInput::make('year')->numeric()->default(1908),
                        ]),
                        TextInput::make('code')->label('Short Citation / Acronym')->placeholder('CPC / IPC / CrPC'),
                        Textarea::make('description')->label('Preamble & Statutory Scope')->rows(4),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->weight('bold')->searchable(),
                TextColumn::make('code')->badge()->color('info'),
                TextColumn::make('year')->sortable(),
                TextColumn::make('description')->limit(60),
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
            'index' => ListActs::route('/'),
            'create' => CreateAct::route('/create'),
            'edit' => EditAct::route('/{record}/edit'),
        ];
    }
}
