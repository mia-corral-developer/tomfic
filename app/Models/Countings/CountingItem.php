<?php

declare(strict_types=1);

namespace App\Models\Countings;

use App\Models\Inventory\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Captura de un producto en una ronda.
 * unique(counting_round_id, product_id): re-escanear el mismo producto
 * REEMPLAZA la cantidad (upsert), nunca duplica.
 */
class CountingItem extends Model
{
    protected $fillable = [
        'counting_round_id',
        'product_id',
        'units',
        'boxes',
        'photo_path',
        'counted_by',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(CountingRound::class, 'counting_round_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }
}
