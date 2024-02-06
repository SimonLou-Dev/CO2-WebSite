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


        Permission::create(['name'=> "sensor_delete"]);
        Permission::create(['name'=> "sensor_update"]);
        Permission::create(['name'=> "sensor_create"]);
        Permission::create(['name'=> "update_chirpstack_key"]);
        Permission::create(['name'=> "sensor_viewAll"]);
        Permission::create(['name'=> "user_delete"]);
        Permission::create(['name'=> "user_viewAll"]);
        Permission::create(['name'=> "roles_modify"]);
        Permission::create(['name'=> "roles_create"]);
        Permission::create(['name'=> "room_modify"]);
        Permission::create(['name'=> "*"]);

        $role = Role::create(["name"=> "user"]);
        $role->givePermissionTo();

        $role = Role::create(["name" => "moderator"]);
        $role->givePermissionTo(["sensor_update"]);

        $role = Role::create(["name" => "administrator"]);
        $role->givePermissionTo(["*"]);

    }
}
