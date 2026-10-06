<?php

namespace App\Filament\Resources\ConflictChecks\Pages;

use App\Filament\Resources\ConflictChecks\ConflictCheckResource;
use App\Services\LegalAiService;
use Filament\Resources\Pages\CreateRecord;

class CreateConflictCheck extends CreateRecord
{
    protected static string $resource = ConflictCheckResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $aiService = app(LegalAiService::class);
        $result = $aiService->checkConflict($data['party_name']);

        $data['verdict'] = $result['verdict'];
        $data['potential_matches'] = $result['matches'];
        $data['searched_by'] = auth()->id();

        if (empty($data['notes']) && count($result['matches']) > 0) {
            $notes = "Automated AI Scan found {$result['total_matches']} potential match(es):\n";
            foreach ($result['matches'] as $m) {
                $notes .= "- [{$m['severity']}] {$m['name']} ({$m['type']}) - {$m['details']}\n";
            }
            $data['notes'] = $notes;
        }

        return $data;
    }
}
