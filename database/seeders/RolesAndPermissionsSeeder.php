<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name'=> "post message"]);
        Permission::create(['name'=> "update all messages"]);
        Permission::create(['name'=> "see message"]);
        Permission::create(['name'=> "delete message"]);
        Permission::create(['name'=> "*"]);

        $role = Role::create(["name"=> "user"]);
        $role->givePermissionTo("see message", "post message");

        $role = Role::create(["name" => "moderator"]);
        $role->givePermissionTo(["see message", "update all messages", "post message"]);

        $role = Role::create(["name" => "administrator"]);
        $role->givePermissionTo(["*"]);



    }
}
