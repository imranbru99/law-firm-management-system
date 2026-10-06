<?php

namespace App\Filament\Resources\CaseCategories\Pages;

use App\Filament\Resources\CaseCategories\CaseCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCaseCategories extends ListRecords
{
    protected static string $resource = CaseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
