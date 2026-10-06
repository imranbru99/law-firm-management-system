<?php

namespace App\Filament\Resources\LegalCases\Pages;

use App\Filament\Resources\LegalCases\LegalCaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLegalCase extends EditRecord
{
    protected static string $resource = LegalCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
