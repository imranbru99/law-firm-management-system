<?php

namespace App\Filament\Resources\LegalCases\Pages;

use App\Filament\Resources\LegalCases\LegalCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLegalCases extends ListRecords
{
    protected static string $resource = LegalCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
