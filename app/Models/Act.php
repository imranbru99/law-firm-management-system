<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Act extends Model
{
    protected $guarded = [];

    public function cases(): BelongsToMany
    {
        return $this->belongsToMany(LegalCase::class, 'case_acts', 'act_id', 'case_id')
            ->withPivot('sections', 'notes')
            ->withTimestamps();
    }
}
