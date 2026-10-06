<?php

namespace App\Filament\Resources\Lawyers\Pages;

use App\Filament\Resources\Lawyers\LawyerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLawyers extends ListRecords
{
    protected static string $resource = LawyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
