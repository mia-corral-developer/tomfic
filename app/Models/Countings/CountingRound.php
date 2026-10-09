<?php

declare(strict_types=1);

namespace App\Models\Countings;

use App\Models\Inventory\ProductLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ronda de conteo de una toma: c1 | c2 (doble conteo a ciegas) o c3 (árbitro).
 *
 * @property string $round_type c1 | c2 | c3
 * @property string $status pending | open | closed
 */
class CountingRound extends Model
{
    protected $fillable = [
        'counting_id',
        'product_location_id',
        'round_type',
        'status',
    ];

    public function counting(): BelongsTo
    {
        return $this->belongsTo(Counting::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(ProductLocation::class, 'product_location_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CountingRoundAssignment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CountingItem::class);
    }

    /** C1 y C2 son a ciegas: este ronda no expone sus items a la otra. */
    public function scopeForCapturador($query, int $userId)
    {
        return $query->whereHas('assignments', fn ($q) => $q->where('user_id', $userId));
    }
}
