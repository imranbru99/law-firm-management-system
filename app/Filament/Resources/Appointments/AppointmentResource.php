<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Resources\Appointments\Pages\CreateAppointment;
use App\Filament\Resources\Appointments\Pages\EditAppointment;
use App\Filament\Resources\Appointments\Pages\ListAppointments;
use App\Models\Appointment;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string | UnitEnum | null $navigationGroup = 'Clients & Contacts';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Client Consultations';
    protected static ?string $modelLabel = 'Consultation';
    protected static ?string $pluralModelLabel = 'Client Consultations & Meetings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Meeting Details & Scheduling')
                    ->schema([
                        TextInput::make('title')->label('Subject / Purpose')->required()->columnSpanFull(),
                        Grid::make(3)->schema([
                            Select::make('client_id')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('lawyer_user_id')
                                ->label('Consulting Counsel')
                                ->relationship('lawyer', 'name')
                                ->searchable()
                                ->preload(),
                            DateTimePicker::make('appointment_date')->default(now()->addDay())->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('location_or_link')->placeholder('Conference Room 2 or Zoom / Teams Link'),
                            Select::make('status')
                                ->options([
                                    'scheduled' => 'Scheduled',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                    'rescheduled' => 'Rescheduled',
                                ])
                                ->default('scheduled')
                                ->required(),
                        ]),
                        Textarea::make('motive')->label('Meeting Agenda & Preparation Notes')->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->weight('bold')->searchable(),
                TextColumn::make('client.name')->label('Client')->searchable(),
                TextColumn::make('lawyer.name')->label('Counsel')->searchable(),
                TextColumn::make('appointment_date')->dateTime()->sortable(),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'scheduled' => 'info',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    default => 'warning',
                }),
                TextColumn::make('location_or_link')->limit(20)->toggleable(),
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
            'index' => ListAppointments::route('/'),
            'create' => CreateAppointment::route('/create'),
            'edit' => EditAppointment::route('/{record}/edit'),
        ];
    }
}
