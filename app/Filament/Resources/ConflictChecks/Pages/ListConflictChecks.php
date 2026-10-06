<?php

namespace App\Filament\Resources\ConflictChecks\Pages;

use App\Filament\Resources\ConflictChecks\ConflictCheckResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConflictChecks extends ListRecords
{
    protected static string $resource = ConflictCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
