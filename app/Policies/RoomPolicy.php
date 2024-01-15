<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Room $room): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        if ($user->hasPermissionTo("*") || $user->hasPermissionTo("room_modify")) return true;
        return false;
    }

    public function update(User $user, Room $room): bool
    {
        if ($user->hasPermissionTo("*") || $user->hasPermissionTo("room_modify")) return true;
        return false;
    }

    public function delete(User $user, Room $room): bool
    {
        if ($user->hasPermissionTo("*") || $user->hasPermissionTo("room_modify")) return true;
        return false;
    }

    public function restore(User $user, Room $room): bool
    {
        if ($user->hasPermissionTo("*") || $user->hasPermissionTo("room_modify")) return true;
        return false;
    }

    public function forceDelete(User $user, Room $room): bool
    {
        return true;
    }
}
