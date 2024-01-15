<?php

namespace Tests\Utils;

use App\Models\User;
use Laravel\Sanctum\Sanctum;

class UserTestTools
{

    public static function getTestUser(string $role = "user"):User
    {
        if (User::where("name", "Testing")->count() == 0){
            $user = User::factory()->make();
            $user->name = "Testing";
            $user->save();
        }else{
            $user = User::where("name","Testing")->first();
        }

        $roles = $user->getRoleNames();
        foreach ($roles as $rolee){
            $user->removeRole($rolee);
        }
        $user->assignRole($role);

        return $user;
    }

}
