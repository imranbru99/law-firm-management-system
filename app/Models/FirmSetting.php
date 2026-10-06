<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirmSetting extends Model
{
    protected $guarded = [];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'firm_name' => 'LexVanguard Legal Partners',
            'tagline' => 'Excellence in Jurisprudence & Litigation',
            'email' => 'contact@lexvanguard.law',
            'phone' => '+1 (800) 555-LEGAL',
            'address' => '100 Chancery Lane, Legal Precinct',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'timezone' => 'UTC',
            'date_format' => 'Y-m-d',
        ]);
    }
}
