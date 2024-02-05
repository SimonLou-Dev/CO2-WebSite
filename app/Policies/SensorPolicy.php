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
        return ($user->hasPermissionTo("*") ||
                $user->hasPermissionTo("sensor_delete") ||
                $user->hasPermissionTo("sensor_update") ||
                $user->hasPermissionTo("sensor_create"));
    }

    public function view(): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return ($user->hasPermissionTo("*") ||
            $user->hasPermissionTo("sensor_create"));
    }

    public function update(User $user, Sensor $sensor): bool
    {
        return ($user->hasPermissionTo("*") ||
            $user->hasPermissionTo("sensor_update"));
    }

    public function delete(User $user, Sensor $sensor): bool
    {
        return ($user->hasPermissionTo("*") ||
            $user->hasPermissionTo("sensor_delete"));
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
