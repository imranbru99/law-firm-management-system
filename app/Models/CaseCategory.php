<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseCategory extends Model
{
    protected $guarded = [];

    public function cases(): HasMany
    {
        return $this->hasMany(LegalCase::class, 'case_category_id');
    }
}
