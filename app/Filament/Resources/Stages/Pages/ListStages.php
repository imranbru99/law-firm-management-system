<?php

namespace App\Filament\Resources\Stages\Pages;

use App\Filament\Resources\Stages\StageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStages extends ListRecords
{
    protected static string $resource = StageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
