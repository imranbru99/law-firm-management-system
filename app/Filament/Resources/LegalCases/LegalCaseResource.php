<?php

namespace App\Filament\Resources\LegalCases;

use App\Filament\Resources\LegalCases\Pages\CreateLegalCase;
use App\Filament\Resources\LegalCases\Pages\EditLegalCase;
use App\Filament\Resources\LegalCases\Pages\ListLegalCases;
use App\Models\LegalCase;
use App\Models\Stage;
use App\Services\LegalAiService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
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

class LegalCaseResource extends Resource
{
    protected static ?string $model = LegalCase::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Litigation & Cases';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Legal Matters / Cases';
    protected static ?string $modelLabel = 'Case';
    protected static ?string $pluralModelLabel = 'Cases';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Core Identification & Matter Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('title')
                                ->label('Case Title / Short Cause')
                                ->placeholder('e.g. Apex Corp v. Zenith Holdings')
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('year')
                                ->numeric()
                                ->default(date('Y')),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('case_no')
                                ->label('Case / Suit Number')
                                ->placeholder('e.g. CS(COMM) 142/2026'),
                            TextInput::make('cnr_number')
                                ->label('CNR / Digital Court Record No')
                                ->placeholder('e.g. DLHC010049212026'),
                            TextInput::make('file_no')
                                ->label('Internal File Reference')
                                ->placeholder('e.g. LF-2026-089'),
                        ]),
                    ]),

                Section::make('Litigants & Legal Actors')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('client_id')
                                ->label('Client (Represented Party)')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('client_role')
                                ->label('Client Legal Role')
                                ->options([
                                    'Plaintiff' => 'Plaintiff / Claimant',
                                    'Petitioner' => 'Petitioner',
                                    'Appellant' => 'Appellant',
                                    'Defendant' => 'Defendant',
                                    'Respondent' => 'Respondent',
                                    'Third Party' => 'Third Party / Intervener',
                                ])
                                ->default('Plaintiff')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('opposite_party_name')
                                ->label('Adverse / Opposite Party Name')
                                ->placeholder('Opposing company or individual'),
                            TextInput::make('opposite_advocate_name')
                                ->label('Opposing Counsel')
                                ->placeholder('Advocate name or firm'),
                        ]),
                    ]),

                Section::make('Forum, Classification & Counsel')
                    ->schema([
                        Grid::make(4)->schema([
                            Select::make('court_id')
                                ->label('Court / Forum')
                                ->relationship('court', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('case_category_id')
                                ->label('Practice Area')
                                ->relationship('category', 'name')
                                ->preload(),
                            Select::make('stage_id')
                                ->label('Procedural Stage')
                                ->relationship('stage', 'name')
                                ->preload(),
                            Select::make('lead_lawyer_id')
                                ->label('Lead Counsel')
                                ->relationship('leadLawyer', 'name')
                                ->preload(),
                        ]),
                    ]),

                Section::make('Critical Dates & Deadlines')
                    ->schema([
                        Grid::make(4)->schema([
                            DatePicker::make('receiving_date')->label('Matter Received'),
                            DatePicker::make('filing_date')->label('Institution / Filing Date'),
                            DatePicker::make('hearing_date')->label('Previous Hearing Date'),
                            DatePicker::make('next_hearing_date')->label('Next Hearing Date'),
                        ]),
                        Grid::make(2)->schema([
                            DatePicker::make('limitation_date')->label('Statute of Limitation Deadline'),
                            DatePicker::make('judgement_date')->label('Judgement / Disposal Date'),
                        ]),
                    ]),

                Section::make('Status & Billing Structure')
                    ->schema([
                        Grid::make(4)->schema([
                            Select::make('status')
                                ->options([
                                    'Open' => 'Open / Active',
                                    'Hearing' => 'In Active Hearing',
                                    'Reserved' => 'Reserved for Judgment',
                                    'Judgement' => 'Disposed / Judgment',
                                    'Closed' => 'Closed',
                                    'Reopened' => 'Reopened',
                                    'Appealed' => 'Appealed',
                                ])
                                ->default('Open')
                                ->required(),
                            Select::make('priority')
                                ->options([
                                    'Low' => 'Low',
                                    'Medium' => 'Medium',
                                    'High' => 'High',
                                    'Urgent' => 'Urgent',
                                ])
                                ->default('Medium')
                                ->required(),
                            TextInput::make('case_charge')
                                ->label('Professional Fee Amount')
                                ->numeric()
                                ->prefix('$'),
                            Select::make('billing_type')
                                ->options([
                                    'fixed' => 'Fixed Fee',
                                    'hourly' => 'Hourly Billable',
                                    'retainer' => 'Retainer',
                                    'contingency' => 'Contingency Fee',
                                ])
                                ->default('fixed'),
                        ]),
                    ]),

                Section::make('Pleadings, Brief & Relief Sought')
                    ->schema([
                        Textarea::make('description')
                            ->label('Factual Summary & Pleadings')
                            ->rows(3),
                        Textarea::make('prayer')
                            ->label('Prayer / Substantive Relief Claimed')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('case_no')
                    ->label('Case No')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('title')
                    ->label('Title / Short Cause')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('court.name')
                    ->label('Court')
                    ->limit(20)
                    ->toggleable(),
                TextColumn::make('stage.name')
                    ->label('Stage')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('next_hearing_date')
                    ->label('Next Hearing')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'info',
                        'Hearing' => 'warning',
                        'Reserved' => 'danger',
                        'Judgement' => 'success',
                        'Closed' => 'gray',
                        default => 'primary',
                    }),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Urgent' => 'danger',
                        'High' => 'warning',
                        'Medium' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Open' => 'Open',
                        'Hearing' => 'In Hearing',
                        'Reserved' => 'Reserved',
                        'Judgement' => 'Judgement',
                        'Closed' => 'Closed',
                    ]),
                SelectFilter::make('priority')
                    ->options([
                        'Urgent' => 'Urgent',
                        'High' => 'High',
                        'Medium' => 'Medium',
                        'Low' => 'Low',
                    ]),
                SelectFilter::make('stage_id')
                    ->relationship('stage', 'name')
                    ->label('Procedural Stage'),
            ])
            ->recordActions([
                Action::make('aiBrief')
                    ->label('AI Brief')
                    ->icon('heroicon-o-sparkles')
                    ->color('warning')
                    ->modalHeading(fn (LegalCase $record) => "AI Executive Brief: {$record->title}")
                    ->modalDescription('Automated 2027 Legal Intelligence Synthesis')
                    ->modalContent(function (LegalCase $record) {
                        $aiService = app(LegalAiService::class);
                        $brief = $aiService->generateCaseBrief($record);
                        return view('filament.components.markdown-preview', ['content' => $brief]);
                    }),

                Action::make('updateNextDate')
                    ->label('Next Date')
                    ->icon('heroicon-o-calendar')
                    ->color('info')
                    ->form([
                        DatePicker::make('next_hearing_date')->required(),
                        Select::make('stage_id')
                            ->label('New Procedural Stage')
                            ->options(Stage::pluck('name', 'id')),
                        TextInput::make('business_conducted')
                            ->label('Hearing Summary / Court Order')
                            ->required(),
                    ])
                    ->action(function (LegalCase $record, array $data): void {
                        $record->hearingDates()->create([
                            'date' => $record->next_hearing_date ?? now(),
                            'stage_id' => $data['stage_id'] ?? $record->stage_id,
                            'business_conducted' => $data['business_conducted'],
                            'next_date' => $data['next_hearing_date'],
                            'status' => 'Completed',
                        ]);

                        $record->update([
                            'hearing_date' => $record->next_hearing_date,
                            'next_hearing_date' => $data['next_hearing_date'],
                            'stage_id' => $data['stage_id'] ?? $record->stage_id,
                        ]);

                        Notification::make()
                            ->title('Hearing Date & Stage Updated')
                            ->success()
                            ->send();
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
            'index' => ListLegalCases::route('/'),
            'create' => CreateLegalCase::route('/create'),
            'edit' => EditLegalCase::route('/{record}/edit'),
        ];
    }
}
