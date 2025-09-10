<?php
namespace App\Domains\Freight;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\Freight\Freight;

class PriceOptimization extends Model {
    protected $fillable = [
        'freight_id',
        'optimized_price',
        'status',
    ];

    public function freight(): BelongsTo
    {
        return $this->belongsTo(Freight::class);
    }}