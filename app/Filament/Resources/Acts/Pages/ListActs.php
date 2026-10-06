<?php

namespace App\Filament\Resources\Acts\Pages;

use App\Filament\Resources\Acts\ActResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActs extends ListRecords
{
    protected static string $resource = ActResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
