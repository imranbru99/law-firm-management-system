<?php

namespace App\Filament\Resources\Staff;

use App\Filament\Resources\Staff\Pages\CreateStaff;
use App\Filament\Resources\Staff\Pages\EditStaff;
use App\Filament\Resources\Staff\Pages\ListStaff;
use App\Models\Staff;
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

class StaffResource extends Resource
{
    protected static ?string $model = Staff::class;

    protected static string | UnitEnum | null $navigationGroup = 'Practice Operations';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Staff & HR Directory';
    protected static ?string $modelLabel = 'Staff Member';
    protected static ?string $pluralModelLabel = 'Staff Members';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Staff Identification & Designation')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('user_id')
                                ->label('User Account')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('employee_id')->label('Employee Code')->placeholder('EMP-001'),
                            TextInput::make('phone')->tel(),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('designation')->default('Paralegal / Legal Associate'),
                            TextInput::make('department')->default('Litigation Support'),
                            Select::make('employment_type')
                                ->options([
                                    'Full Time' => 'Full Time',
                                    'Part Time' => 'Part Time',
                                    'Contract' => 'Retainer / Contract',
                                    'Intern' => 'Law Clerk / Intern',
                                ])
                                ->default('Full Time'),
                        ]),
                        Grid::make(3)->schema([
                            DatePicker::make('date_of_joining')->default(now()),
                            DatePicker::make('date_of_birth'),
                            TextInput::make('basic_salary')->numeric()->prefix('$')->default(3500),
                        ]),
                    ]),

                Section::make('Banking & Compensation')
                    ->schema([
                        Grid::make(4)->schema([
                            TextInput::make('bank_name')->placeholder('JPMorgan / Wells Fargo'),
                            TextInput::make('bank_branch_name')->placeholder('Downtown'),
                            TextInput::make('bank_account_name')->placeholder('Account holder'),
                            TextInput::make('bank_account_no')->placeholder('Account number'),
                        ]),
                        Textarea::make('current_address')->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_id')->label('Code')->weight('bold')->searchable(),
                TextColumn::make('user.name')->label('Staff Name')->searchable()->sortable(),
                TextColumn::make('designation')->badge()->color('info'),
                TextColumn::make('department')->toggleable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('basic_salary')->money('USD')->sortable(),
                TextColumn::make('date_of_joining')->date()->toggleable(),
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
            'index' => ListStaff::route('/'),
            'create' => CreateStaff::route('/create'),
            'edit' => EditStaff::route('/{record}/edit'),
        ];
    }
}
