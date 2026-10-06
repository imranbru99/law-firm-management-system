<?php

namespace App\Filament\Resources\HearingDates\Pages;

use App\Filament\Resources\HearingDates\HearingDateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHearingDate extends EditRecord
{
    protected static string $resource = HearingDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
