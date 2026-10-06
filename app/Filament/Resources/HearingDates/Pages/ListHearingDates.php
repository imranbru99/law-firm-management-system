<?php

namespace App\Filament\Resources\HearingDates\Pages;

use App\Filament\Resources\HearingDates\HearingDateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHearingDates extends ListRecords
{
    protected static string $resource = HearingDateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
