<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtFeeCalculation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'claim_amount' => 'decimal:2',
            'calculated_fee' => 'decimal:2',
        ];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }
}
