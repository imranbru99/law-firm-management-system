<?php

namespace App\Filament\Resources\Acts\Pages;

use App\Filament\Resources\Acts\ActResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAct extends EditRecord
{
    protected static string $resource = ActResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
