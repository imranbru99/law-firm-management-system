<?php

namespace App\Filament\Resources\Contacts;

use App\Filament\Resources\Contacts\Pages\CreateContact;
use App\Filament\Resources\Contacts\Pages\EditContact;
use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Models\Contact;
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

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string | UnitEnum | null $navigationGroup = 'Clients & Contacts';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Legal Contacts & Experts';
    protected static ?string $modelLabel = 'Legal Contact';
    protected static ?string $pluralModelLabel = 'Witnesses, Experts & Counsels';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact Details & Legal Classification')
                    ->description('Directory for Witnesses, Expert Witnesses, Opposing Counsels, Process Servers, Notaries, and Arbitrators.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')->required(),
                            Select::make('contact_category_id')
                                ->label('Classification')
                                ->relationship('category', 'name')
                                ->preload()
                                ->required(),
                            TextInput::make('organization')->placeholder('Firm, Agency, or Institution'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('email')->email(),
                            TextInput::make('mobile')->tel(),
                        ]),
                        Textarea::make('address')->rows(2),
                        Textarea::make('notes')->rows(2)->placeholder('Professional credentials, testimony history, or court remarks'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->weight('bold')->searchable(),
                TextColumn::make('category.name')->label('Category')->badge()->color('primary'),
                TextColumn::make('organization')->searchable(),
                TextColumn::make('mobile')->searchable(),
                TextColumn::make('email')->searchable(),
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
            'index' => ListContacts::route('/'),
            'create' => CreateContact::route('/create'),
            'edit' => EditContact::route('/{record}/edit'),
        ];
    }
}
