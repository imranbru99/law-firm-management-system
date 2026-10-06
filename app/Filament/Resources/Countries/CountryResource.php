<?php

namespace App\Filament\Resources\Countries;

use App\Filament\Resources\Countries\Pages\CreateCountry;
use App\Filament\Resources\Countries\Pages\EditCountry;
use App\Filament\Resources\Countries\Pages\ListCountries;
use App\Models\Country;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static string | UnitEnum | null $navigationGroup = 'Statutes & Jurisdiction';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Countries & Currencies';
    protected static ?string $modelLabel = 'Country & Jurisdiction';
    protected static ?string $pluralModelLabel = 'Countries & Global Jurisdictions';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Global Jurisdiction & Currency Profile')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')->label('Country Name')->required()->placeholder('United States'),
                            TextInput::make('code')->label('ISO Alpha-2 Code')->maxLength(5)->placeholder('US')->required(),
                            TextInput::make('phonecode')->label('Dialing Code')->placeholder('+1'),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('currency')->label('Currency Code')->placeholder('USD')->required(),
                            TextInput::make('currency_symbol')->label('Currency Symbol')->placeholder('$')->required(),
                            TextInput::make('flag')->label('Flag Icon / Emoji')->placeholder('🇺🇸'),
                        ]),
                        TextInput::make('region')->label('Geographic / Jurisprudential Region')->placeholder('North America / Common Law'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('flag')->label('Flag')->badge(),
                TextColumn::make('name')->label('Country')->weight('bold')->searchable()->sortable(),
                TextColumn::make('code')->label('ISO Code')->badge()->color('info'),
                TextColumn::make('currency')->label('Currency')->weight('semibold')->sortable(),
                TextColumn::make('currency_symbol')->label('Symbol'),
                TextColumn::make('phonecode')->label('Phone Code'),
                TextColumn::make('region')->label('Region')->sortable(),
                TextColumn::make('states_count')->label('Divisions / States')->counts('states')->badge()->color('success'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
            'create' => CreateCountry::route('/create'),
            'edit' => EditCountry::route('/{record}/edit'),
        ];
    }
}
