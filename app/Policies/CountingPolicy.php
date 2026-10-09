<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Countings\Counting;
use App\Models\User;

/**
 * Policy de acceso al módulo Countings (tomfic-field).
 * Los admins gestionan tomas; los capturadores solo ven/su ronda via
 * assignments (policía separada en el controller), así que aquí solo
 * se controla el lado admin con view_countings/create_countings/manage_countings.
 */
class CountingPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, \App\Enums\Permission::VIEW_COUNTINGS->value);
    }

    public function view(User $user, Counting $counting): bool
    {
        return $this->belongsToSameOrganization($user, $counting)
            && $this->hasPermission($user, \App\Enums\Permission::VIEW_COUNTINGS->value);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, \App\Enums\Permission::CREATE_COUNTINGS->value);
    }

    public function manage(User $user, Counting $counting): bool
    {
        return $this->belongsToSameOrganization($user, $counting)
            && $this->hasPermission($user, \App\Enums\Permission::MANAGE_COUNTINGS->value);
    }

    public function capture(User $user, Counting $counting): bool
    {
        // Capturador: usuario con capture_countings que pertenece a la misma org.
        // El acceso por RONDA se valida contra assignments (a él asignada).
        return $this->belongsToSameOrganization($user, $counting)
            && $this->hasPermission($user, \App\Enums\Permission::CAPTURE_COUNTINGS->value);
    }
}
