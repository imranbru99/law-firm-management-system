<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\Pages\CreateInvoice;
use App\Filament\Resources\Invoices\Pages\EditInvoice;
use App\Filament\Resources\Invoices\Pages\ListInvoices;
use App\Models\BankAccount;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Tax;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Finance & Trust (IOLTA)';
    protected static ?int $navigationSort = 1;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Header & Matter')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('invoice_no')
                                ->label('Invoice Number')
                                ->default(fn () => 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT))
                                ->required(),
                            Select::make('client_id')
                                ->label('Billed Client')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('case_id')
                                ->label('Associated Matter / Case')
                                ->relationship('case', 'title')
                                ->searchable()
                                ->preload(),
                            Select::make('payment_status')
                                ->options([
                                    'draft' => 'Draft',
                                    'due' => 'Unpaid / Due',
                                    'partially_paid' => 'Partially Paid',
                                    'paid' => 'Fully Paid',
                                    'overdue' => 'Overdue',
                                ])
                                ->default('due')
                                ->required(),
                        ]),
                        Grid::make(3)->schema([
                            DatePicker::make('invoice_date')->default(now())->required(),
                            DatePicker::make('due_date')->default(now()->addDays(15))->required(),
                            Select::make('tax_id')
                                ->label('Applicable Tax / VAT')
                                ->relationship('tax', 'name')
                                ->preload(),
                        ]),
                    ]),

                Section::make('Line Items & Professional Services')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Select::make('service_id')
                                    ->label('Service Catalog')
                                    ->options(Service::pluck('name', 'id'))
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($service = Service::find($state)) {
                                            $set('description', $service->name . ' - ' . $service->description);
                                            $set('rate', $service->default_rate);
                                            $set('total', $service->default_rate);
                                        }
                                    }),
                                TextInput::make('description')->required()->columnSpan(2),
                                TextInput::make('qty')->numeric()->default(1)->reactive()
                                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('total', $state * $get('rate'))),
                                TextInput::make('rate')->numeric()->prefix('$')->reactive()
                                    ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('total', $state * $get('qty'))),
                                TextInput::make('total')->numeric()->prefix('$')->disabled()->dehydrated(),
                            ])
                            ->columns(6)
                            ->defaultItems(1),
                    ]),

                Section::make('Adjustments & Notes')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('discount_type')
                                ->options(['fixed' => 'Fixed Amount ($)', 'percentage' => 'Percentage (%)'])
                                ->default('fixed'),
                            TextInput::make('discount')->numeric()->default(0),
                            TextInput::make('paid')->numeric()->prefix('$')->default(0),
                        ]),
                        Textarea::make('notes')->rows(2)->placeholder('Bank transfer details or payment terms'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_no')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('case.case_no')
                    ->label('Matter Ref')
                    ->toggleable(),
                TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->money('USD')
                    ->weight('bold'),
                TextColumn::make('paid')
                    ->money('USD')
                    ->toggleable(),
                TextColumn::make('due')
                    ->money('USD')
                    ->color('danger')
                    ->weight('bold'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partially_paid' => 'warning',
                        'overdue' => 'danger',
                        'draft' => 'gray',
                        default => 'info',
                    }),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->options([
                        'due' => 'Due',
                        'partially_paid' => 'Partially Paid',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ]),
            ])
            ->recordActions([
                Action::make('recordPayment')
                    ->label('Record Payment')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->form([
                        Select::make('bank_account_id')
                            ->label('Destination Bank / Trust Account')
                            ->options(BankAccount::pluck('account_name', 'id'))
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(fn (Invoice $record) => $record->due)
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'bank_transfer' => 'Bank Transfer / Wire',
                                'credit_card' => 'Credit / Debit Card',
                                'cheque' => 'Cheque',
                                'cash' => 'Cash',
                                'trust_transfer' => 'Client Trust (IOLTA) Draw',
                            ])
                            ->default('bank_transfer')
                            ->required(),
                        DatePicker::make('transaction_date')->default(now())->required(),
                        TextInput::make('reference_no')->placeholder('UTR / Check / Transaction ID'),
                    ])
                    ->action(function (Invoice $record, array $data): void {
                        $record->transactions()->create([
                            'title' => "Payment for Invoice #{$record->invoice_no}",
                            'bank_account_id' => $data['bank_account_id'],
                            'case_id' => $record->case_id,
                            'type' => 'in',
                            'payment_method' => $data['payment_method'],
                            'amount' => $data['amount'],
                            'transaction_date' => $data['transaction_date'],
                            'reference_no' => $data['reference_no'] ?? null,
                            'created_by' => auth()->id(),
                        ]);

                        // Update account balance
                        $account = BankAccount::find($data['bank_account_id']);
                        if ($account) {
                            $account->increment('balance', $data['amount']);
                        }

                        // Recalculate invoice
                        $record->recalculate();

                        Notification::make()
                            ->title('Payment Recorded Successfully')
                            ->success()
                            ->send();
                    }),

                Action::make('downloadPdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Invoice $record) {
                        $record->loadMissing(['client', 'case', 'items', 'tax']);
                        $pdf = Pdf::loadView('filament.invoices.pdf', ['invoice' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            "invoice-{$record->invoice_no}.pdf"
                        );
                    }),

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
            'index' => ListInvoices::route('/'),
            'create' => CreateInvoice::route('/create'),
            'edit' => EditInvoice::route('/{record}/edit'),
        ];
    }
}
