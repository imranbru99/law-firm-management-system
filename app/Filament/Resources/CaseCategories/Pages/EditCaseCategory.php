<?php

namespace App\Filament\Resources\CaseCategories\Pages;

use App\Filament\Resources\CaseCategories\CaseCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCaseCategory extends EditRecord
{
    protected static string $resource = CaseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
