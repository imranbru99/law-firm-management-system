<?php

namespace App\Filament\Resources\ConflictChecks\Pages;

use App\Filament\Resources\ConflictChecks\ConflictCheckResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConflictCheck extends EditRecord
{
    protected static string $resource = ConflictCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
