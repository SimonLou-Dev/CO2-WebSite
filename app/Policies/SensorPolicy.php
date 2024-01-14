<?php

namespace App\Policies;

use App\Models\Sensor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SensorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Sensor $sensor): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Sensor $sensor): bool
    {
        return true;
    }

    public function delete(User $user, Sensor $sensor): bool
    {
        return true;
    }

    public function restore(User $user, Sensor $sensor): bool
    {
        return true;
    }

    public function forceDelete(User $user, Sensor $sensor): bool
    {
        return true;
    }
}
