<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $guarded = [];

    public function cases(): HasMany
    {
        return $this->hasMany(LegalCase::class, 'stage_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'stage_id');
    }
}
