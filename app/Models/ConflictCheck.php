<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConflictCheck extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'potential_matches' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'searched_by');
    }
}
