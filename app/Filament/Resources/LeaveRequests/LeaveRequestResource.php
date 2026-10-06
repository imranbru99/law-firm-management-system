<?php

namespace App\Filament\Resources\LeaveRequests;

use App\Filament\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Models\LeaveRequest;
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

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string | UnitEnum | null $navigationGroup = 'Practice Operations';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Leave Management';
    protected static ?string $modelLabel = 'Leave Request';
    protected static ?string $pluralModelLabel = 'Leave Requests & Approvals';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSun;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Leave Application')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('user_id')
                                ->label('Applicant')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('leave_type_id')
                                ->label('Leave Type')
                                ->relationship('leaveType', 'name')
                                ->preload()
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending Approval',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                ])
                                ->default('pending')
                                ->required(),
                        ]),
                        Grid::make(3)->schema([
                            DatePicker::make('start_date')->required(),
                            DatePicker::make('end_date')->required(),
                            TextInput::make('total_days')->numeric()->default(1.0)->required(),
                        ]),
                        Textarea::make('reason')->label('Reason / Vacation Request')->required()->rows(2),
                        Textarea::make('rejection_reason')->label('Managing Partner Remarks (if rejected)')->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Applicant')->weight('bold')->searchable(),
                TextColumn::make('leaveType.name')->label('Type')->badge()->color('primary'),
                TextColumn::make('start_date')->date(),
                TextColumn::make('end_date')->date(),
                TextColumn::make('total_days')->label('Days')->sortable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                }),
                TextColumn::make('reason')->limit(20),
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
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
