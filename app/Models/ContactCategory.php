<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactCategory extends Model
{
    protected $guarded = [];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
