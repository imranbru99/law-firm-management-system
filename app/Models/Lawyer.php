<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lawyer extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_external' => 'boolean',
            'hourly_rate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(LegalCase::class, 'case_lawyer', 'lawyer_id', 'case_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function leadCases(): HasMany
    {
        return $this->hasMany(LegalCase::class, 'lead_lawyer_id');
    }
}
