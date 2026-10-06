<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Models\Task;
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

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Practice Operations';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Legal Tasks & Deadlines';
    protected static ?string $modelLabel = 'Task';
    protected static ?string $pluralModelLabel = 'Tasks & Deadlines';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Task Information & Assignment')
                    ->schema([
                        TextInput::make('title')
                            ->label('Task / Action Item')
                            ->required()
                            ->columnSpanFull(),
                        Grid::make(3)->schema([
                            Select::make('case_id')
                                ->label('Legal Matter')
                                ->relationship('case', 'title')
                                ->searchable()
                                ->preload(),
                            Select::make('stage_id')
                                ->label('Procedural Stage')
                                ->relationship('stage', 'name')
                                ->preload(),
                            Select::make('assigned_to')
                                ->label('Assigned Advocate / Staff')
                                ->relationship('assignee', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        Grid::make(4)->schema([
                            DatePicker::make('due_date')->default(now()->addDays(3))->required(),
                            Select::make('priority')
                                ->options([
                                    'Low' => 'Low',
                                    'Medium' => 'Medium',
                                    'High' => 'High',
                                    'Urgent' => 'Urgent',
                                ])
                                ->default('Medium')
                                ->required(),
                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'in_progress' => 'In Progress',
                                    'review' => 'In Senior Review',
                                    'completed' => 'Completed',
                                ])
                                ->default('pending')
                                ->required(),
                            TextInput::make('progress')
                                ->label('Progress %')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->maxValue(100),
                        ]),
                        Textarea::make('description')->rows(3)->placeholder('Instructions, filing guidelines, research directives'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->weight('bold')->searchable(),
                TextColumn::make('case.case_no')->label('Matter')->searchable(),
                TextColumn::make('assignee.name')->label('Assignee')->searchable(),
                TextColumn::make('due_date')->date()->sortable(),
                TextColumn::make('priority')->badge()->color(fn (string $state): string => match ($state) {
                    'Urgent' => 'danger',
                    'High' => 'warning',
                    'Medium' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'completed' => 'success',
                    'review' => 'warning',
                    'in_progress' => 'info',
                    default => 'gray',
                }),
                TextColumn::make('progress')->label('% Done')->suffix('%')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'review' => 'In Review',
                    'completed' => 'Completed',
                ]),
                SelectFilter::make('priority')->options([
                    'Urgent' => 'Urgent',
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
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
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}
