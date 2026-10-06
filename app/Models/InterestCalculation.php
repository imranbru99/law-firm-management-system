<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestCalculation extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'principal_amount' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'accrued_interest' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }
}
