<?php

namespace App\Filament\Resources\Lawyers\Pages;

use App\Filament\Resources\Lawyers\LawyerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLawyer extends EditRecord
{
    protected static string $resource = LawyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
