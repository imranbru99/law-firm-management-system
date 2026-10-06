<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
        ];
    }
}
