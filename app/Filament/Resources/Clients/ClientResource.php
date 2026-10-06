<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Models\Client;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Clients & Contacts';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Identity & Classification')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Client Full Name / Entity')
                                ->required(),
                            Select::make('type')
                                ->options([
                                    'individual' => 'Individual',
                                    'corporate' => 'Corporate Entity / Company',
                                    'institution' => 'Institution / NGO',
                                ])
                                ->default('individual')
                                ->required(),
                            Select::make('client_category_id')
                                ->label('Client Category')
                                ->relationship('category', 'name')
                                ->preload(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('company_name')
                                ->label('Organization / Trade Name')
                                ->placeholder('Applicable if corporate'),
                            TextInput::make('tax_vat_number')
                                ->label('Tax ID / GSTIN / VAT Number')
                                ->placeholder('Corporate identification'),
                        ]),
                    ]),

                Section::make('Communication & Portal Access')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('email')->email(),
                            TextInput::make('mobile')->tel(),
                            Select::make('gender')
                                ->options([
                                    'Male' => 'Male',
                                    'Female' => 'Female',
                                    'Other' => 'Other / Non-Binary',
                                ]),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('country_id')
                                ->relationship('country', 'name')
                                ->preload(),
                            Select::make('state_id')
                                ->relationship('state', 'name')
                                ->preload(),
                            Select::make('city_id')
                                ->relationship('city', 'name')
                                ->preload(),
                        ]),
                        Textarea::make('address')->rows(2),
                        Select::make('user_id')
                            ->label('Linked User (Client Portal Access)')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Assigning a user account enables Client Portal access.'),
                        Textarea::make('description')->label('Internal Notes / KYC Profile')->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Client Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('company_name')
                    ->label('Organization')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'corporate' => 'info',
                        'institution' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('mobile')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('cases_count')
                    ->counts('cases')
                    ->label('Active Matters')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                SelectFilter::make('client_category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),
                SelectFilter::make('type')
                    ->options([
                        'individual' => 'Individual',
                        'corporate' => 'Corporate',
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
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }
}
