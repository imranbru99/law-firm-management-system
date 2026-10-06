<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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
use UnitEnum;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string | UnitEnum | null $navigationGroup = 'Finance & Trust (IOLTA)';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Trust & Financial Ledger';
    protected static ?string $modelLabel = 'Ledger Transaction';
    protected static ?string $pluralModelLabel = 'Financial Transactions & Ledgers';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ledger Transaction Entry')
                    ->schema([
                        TextInput::make('title')->label('Transaction Title / Description')->required()->columnSpanFull(),
                        Grid::make(3)->schema([
                            Select::make('bank_account_id')
                                ->label('Bank / Trust Account')
                                ->relationship('bankAccount', 'account_name')
                                ->preload()
                                ->required(),
                            Select::make('type')
                                ->label('Flow (In / Out)')
                                ->options([
                                    'in' => 'Money In (Deposit / Fee / Retainer)',
                                    'out' => 'Money Out (Expense / Disbursement / Draw)',
                                ])
                                ->default('in')
                                ->required(),
                            TextInput::make('amount')->numeric()->prefix('$')->required(),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('payment_method')
                                ->options([
                                    'bank_transfer' => 'Bank Wire / ACH',
                                    'credit_card' => 'Credit Card',
                                    'cheque' => 'Cheque',
                                    'cash' => 'Cash',
                                    'trust_transfer' => 'Trust Account Draw',
                                ])
                                ->default('bank_transfer')
                                ->required(),
                            DatePicker::make('transaction_date')->default(now())->required(),
                            TextInput::make('reference_no')->placeholder('Check # or UTR / Wire ID'),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('case_id')
                                ->label('Associated Matter')
                                ->relationship('case', 'title')
                                ->searchable()
                                ->preload(),
                            Select::make('invoice_id')
                                ->label('Associated Invoice')
                                ->relationship('invoice', 'invoice_no')
                                ->searchable()
                                ->preload(),
                        ]),
                        Textarea::make('description')->rows(2)->placeholder('Client authorization notes, receipt details'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')->date()->sortable()->weight('bold'),
                TextColumn::make('title')->searchable()->limit(30),
                TextColumn::make('bankAccount.account_name')->label('Account')->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('amount')->money('USD')->weight('bold')->sortable(),
                TextColumn::make('payment_method')->label('Method')->badge()->color('info'),
                TextColumn::make('case.case_no')->label('Matter')->toggleable(),
                TextColumn::make('reference_no')->label('Ref #')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')->options(['in' => 'Money In', 'out' => 'Money Out']),
                SelectFilter::make('bank_account_id')->relationship('bankAccount', 'account_name')->label('Account'),
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
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }
}
