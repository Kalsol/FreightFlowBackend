<?php

namespace App\Domains\Financials\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'base_currency',
        'quote_currency',
        'rate',
        'effective_at',
        'source',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'rate' => 'decimal:6',
        'effective_at' => 'datetime',
    ];
}


