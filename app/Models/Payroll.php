<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'basic_salary' => 'decimal:2',
            'earnings' => 'decimal:2',
            'deductions' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'net_salary' => 'decimal:2',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
