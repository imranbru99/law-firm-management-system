<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtCategory extends Model
{
    protected $guarded = [];

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }
}
