<?php

declare(strict_types=1);

namespace App\Models\Countings;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Capturador asignado a una ronda de conteo.
 */
class CountingRoundAssignment extends Model
{
    protected $fillable = [
        'counting_round_id',
        'user_id',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(CountingRound::class, 'counting_round_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
