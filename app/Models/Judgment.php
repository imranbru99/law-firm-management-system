<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Judgment extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'judgment_date' => 'date',
            'is_appeal_recommended' => 'boolean',
        ];
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class, 'case_id');
    }
}
