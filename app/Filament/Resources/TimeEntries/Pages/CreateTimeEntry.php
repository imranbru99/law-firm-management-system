<?php

namespace App\Filament\Resources\TimeEntries\Pages;

use App\Filament\Resources\TimeEntries\TimeEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimeEntry extends CreateRecord
{
    protected static string $resource = TimeEntryResource::class;
}
