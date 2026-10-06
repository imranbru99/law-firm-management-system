<?php

namespace App\Filament\Resources\Judgments;

use App\Filament\Resources\Judgments\Pages\CreateJudgment;
use App\Filament\Resources\Judgments\Pages\EditJudgment;
use App\Filament\Resources\Judgments\Pages\ListJudgments;
use App\Models\Judgment;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class JudgmentResource extends Resource
{
    protected static ?string $model = Judgment::class;

    protected static string | UnitEnum | null $navigationGroup = 'Litigation & Cases';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Judgments & Orders';
    protected static ?string $modelLabel = 'Judgment / Order';
    protected static ?string $pluralModelLabel = 'Judgments & Final Orders';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Disposal Record & Order Details')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('case_id')
                                ->label('Legal Matter / Suit')
                                ->relationship('case', 'title')
                                ->searchable()
                                ->preload()
                                ->required(),
                            DatePicker::make('judgment_date')->default(now())->required(),
                            Select::make('disposition')
                                ->options([
                                    'Decreed In Favor' => 'Decreed In Favor (Won)',
                                    'Partly Allowed' => 'Partly Allowed',
                                    'Dismissed' => 'Dismissed / Rejected',
                                    'Settled / Compromise' => 'Settled / Compromise Decree',
                                    'Remanded' => 'Remanded for Rehearing',
                                ])
                                ->default('Decreed In Favor')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('judge_name')->placeholder('Hon. Justice ...'),
                            Toggle::make('is_appeal_recommended')->label('Appeal Recommended to Senior Bench / Appellate Court'),
                        ]),
                        Textarea::make('order_summary')->label('Executive Summary of Judgment / Ratio Decidendi')->rows(3),
                        Textarea::make('full_order_text')->label('Operative Portion of the Decree')->rows(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judgment_date')->date()->sortable()->weight('bold'),
                TextColumn::make('case.case_no')->label('Case No')->searchable(),
                TextColumn::make('case.title')->label('Matter Title')->limit(30)->searchable(),
                TextColumn::make('disposition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Decreed In Favor' => 'success',
                        'Partly Allowed' => 'warning',
                        'Dismissed' => 'danger',
                        'Settled / Compromise' => 'info',
                        default => 'primary',
                    }),
                TextColumn::make('judge_name')->label('Presiding Judge')->toggleable(),
                IconColumn::make('is_appeal_recommended')->boolean()->label('Appeal Rec.'),
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
            'index' => ListJudgments::route('/'),
            'create' => CreateJudgment::route('/create'),
            'edit' => EditJudgment::route('/{record}/edit'),
        ];
    }
}
