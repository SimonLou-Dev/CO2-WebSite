<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

        return $user->hasPermissionTo("see message");

    }

    public function view(User $user, Message $message): bool
    {

        return $user->hasPermissionTo("see message");

    }

    public function create(User $user): bool
    {

        return $user->hasPermissionTo("post message");
    }

    public function update(User $user, Message $message): bool
    {
        if($message->sender_id == $user->id) return $user->hasPermissionTo("post message");

        return $user->hasPermissionTo("update all message");

    }

    public function delete(User $user, Message $message): bool
    {

        return $user->hasPermissionTo("delete message");

    }

    public function restore(User $user, Message $message): bool
    {

        return false;

    }

    public function forceDelete(User $user, Message $message): bool
    {

        return false;

    }
}
