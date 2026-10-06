<?php

namespace App\Filament\Resources\BankAccounts;

use App\Filament\Resources\BankAccounts\Pages\CreateBankAccount;
use App\Filament\Resources\BankAccounts\Pages\EditBankAccount;
use App\Filament\Resources\BankAccounts\Pages\ListBankAccounts;
use App\Models\BankAccount;
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

class BankAccountResource extends Resource
{
    protected static ?string $model = BankAccount::class;

    protected static string | UnitEnum | null $navigationGroup = 'Finance & Trust (IOLTA)';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Bank & Trust Accounts';
    protected static ?string $modelLabel = 'Bank / Trust Account';
    protected static ?string $pluralModelLabel = 'Bank & Trust Accounts (IOLTA)';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Financial Institution & Account Details')
                    ->description('Support for Operating Accounts, Escrow, and IOLTA compliant Client Trust Accounts.')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('account_name')->label('Account Label / Name')->required(),
                            Select::make('account_type')
                                ->options([
                                    'Operating Account' => 'Operating Account (Firm Funds)',
                                    'Client Trust Account (IOLTA)' => 'Client Trust Account (IOLTA)',
                                    'Escrow Account' => 'Escrow / Settlement Account',
                                ])
                                ->default('Client Trust Account (IOLTA)')
                                ->required(),
                            TextInput::make('bank_name')->label('Bank / Depository Institution')->required(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('account_no')->label('Account / IBAN Number')->required(),
                            TextInput::make('branch')->label('Routing / Branch Code'),
                            TextInput::make('balance')->label('Current Balance')->numeric()->prefix('$')->default(0),
                        ]),
                        Textarea::make('description')->rows(2)->placeholder('Trust accounting guidelines, fiduciary designations'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('account_name')->weight('bold')->searchable(),
                TextColumn::make('account_type')->badge()->color(fn (string $state): string => match ($state) {
                    'Client Trust Account (IOLTA)' => 'warning',
                    'Escrow Account' => 'info',
                    default => 'success',
                }),
                TextColumn::make('bank_name')->searchable(),
                TextColumn::make('account_no')->label('Account #')->searchable(),
                TextColumn::make('balance')->money('USD')->weight('bold')->sortable(),
                TextColumn::make('transactions_count')->counts('transactions')->label('Ledger Entries')->badge(),
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
            'index' => ListBankAccounts::route('/'),
            'create' => CreateBankAccount::route('/create'),
            'edit' => EditBankAccount::route('/{record}/edit'),
        ];
    }
}
