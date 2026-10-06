<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseDocument extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_confidential' => 'boolean',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }

    public function hearingDate(): BelongsTo
    {
        return $this->belongsTo(HearingDate::class);
    }
}
