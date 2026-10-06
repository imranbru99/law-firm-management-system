<?php

namespace App\Filament\Resources\Judgments\Pages;

use App\Filament\Resources\Judgments\JudgmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJudgments extends ListRecords
{
    protected static string $resource = JudgmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
