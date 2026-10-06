<?php

namespace App\Filament\Resources\Judgments\Pages;

use App\Filament\Resources\Judgments\JudgmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJudgment extends EditRecord
{
    protected static string $resource = JudgmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
