<?php

namespace App\Filament\Resources\Payrolls;

use App\Filament\Resources\Payrolls\Pages\CreatePayroll;
use App\Filament\Resources\Payrolls\Pages\EditPayroll;
use App\Filament\Resources\Payrolls\Pages\ListPayrolls;
use App\Models\Payroll;
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
use Filament\Tables\Table;
use UnitEnum;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static string | UnitEnum | null $navigationGroup = 'Practice Operations';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Staff Payroll & Salaries';
    protected static ?string $modelLabel = 'Salary Slip';
    protected static ?string $pluralModelLabel = 'Staff Payroll & Compensation';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Salary Generation & Compensation')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('staff_id')
                                ->label('Staff Member')
                                ->relationship('staff.user', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('month')
                                ->options([
                                    'January' => 'January', 'February' => 'February', 'March' => 'March',
                                    'April' => 'April', 'May' => 'May', 'June' => 'June',
                                    'July' => 'July', 'August' => 'August', 'September' => 'September',
                                    'October' => 'October', 'November' => 'November', 'December' => 'December',
                                ])
                                ->default(date('F'))
                                ->required(),
                            TextInput::make('year')->numeric()->default(date('Y'))->required(),
                        ]),
                        Grid::make(4)->schema([
                            TextInput::make('basic_salary')->numeric()->prefix('$')->required()->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('net_salary', ($state + $get('earnings')) - $get('deductions'))),
                            TextInput::make('earnings')->label('Incentives / Bonus')->numeric()->prefix('$')->default(0)->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('net_salary', ($get('basic_salary') + $state) - $get('deductions'))),
                            TextInput::make('deductions')->label('Tax / Deductions')->numeric()->prefix('$')->default(0)->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('net_salary', ($get('basic_salary') + $get('earnings')) - $state)),
                            TextInput::make('net_salary')->numeric()->prefix('$')->disabled()->dehydrated()->required(),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'generated' => 'Generated / Approved',
                                    'paid' => 'Disbursed / Paid',
                                ])
                                ->default('draft')
                                ->required(),
                            DatePicker::make('payment_date'),
                            TextInput::make('transaction_reference')->placeholder('Payroll Wire UTR / Check #'),
                        ]),
                        Textarea::make('notes')->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('staff.user.name')->label('Staff Name')->weight('bold')->searchable(),
                TextColumn::make('month')->badge()->color('primary'),
                TextColumn::make('year'),
                TextColumn::make('basic_salary')->money('USD'),
                TextColumn::make('earnings')->money('USD')->color('success'),
                TextColumn::make('deductions')->money('USD')->color('danger'),
                TextColumn::make('net_salary')->money('USD')->weight('bold'),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'paid' => 'success',
                    'generated' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('payment_date')->date(),
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
            'index' => ListPayrolls::route('/'),
            'create' => CreatePayroll::route('/create'),
            'edit' => EditPayroll::route('/{record}/edit'),
        ];
    }
}
