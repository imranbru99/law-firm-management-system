<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRiskAnalysis extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'risk_score' => 'integer',
            'detected_clauses' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
