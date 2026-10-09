<?php

declare(strict_types=1);

namespace App\Models\Countings;

use App\Models\Auth\Organization;
use App\Models\Concerns\BelongsToOrganization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Toma de inventario físico: agrupador de rondas de conteo (módulo tomfic-field).
 * El core de inventario (stock_audits) no se toca — este módulo es aparte.
 *
 * @property string $status draft | in_progress | closed
 */
class Counting extends Model
{
    use BelongsToOrganization, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'status',
        'created_by',
    ];

    public function rounds(): HasMany
    {
        return $this->hasMany(CountingRound::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
