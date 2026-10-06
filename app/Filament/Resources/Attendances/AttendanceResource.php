<?php

namespace App\Filament\Resources\Attendances;

use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Models\Attendance;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string | UnitEnum | null $navigationGroup = 'Practice Operations';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Staff Attendance';
    protected static ?string $modelLabel = 'Attendance Record';
    protected static ?string $pluralModelLabel = 'Staff Attendance Log';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendance Record')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('user_id')
                                ->label('Staff Member')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            DatePicker::make('date')->default(now())->required(),
                            Select::make('status')
                                ->options([
                                    'Present' => 'Present',
                                    'Absent' => 'Absent',
                                    'Half Day' => 'Half Day',
                                    'Leave' => 'Approved Leave',
                                    'Holiday' => 'Court Holiday',
                                ])
                                ->default('Present')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TimePicker::make('clock_in')->default('09:00:00'),
                            TimePicker::make('clock_out')->default('18:00:00'),
                        ]),
                        Textarea::make('note')->rows(2)->placeholder('Remarks, remote work justification, court attendance note'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->date()->sortable()->weight('bold'),
                TextColumn::make('user.name')->label('Staff Name')->searchable()->sortable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'Present' => 'success',
                    'Absent' => 'danger',
                    'Half Day' => 'warning',
                    'Leave' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('clock_in')->time(),
                TextColumn::make('clock_out')->time(),
                TextColumn::make('note')->limit(25),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'Present' => 'Present',
                    'Absent' => 'Absent',
                    'Leave' => 'Leave',
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendance::route('/create'),
            'edit' => EditAttendance::route('/{record}/edit'),
        ];
    }
}
