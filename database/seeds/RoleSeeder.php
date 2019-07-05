<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create([
            'name' => 'superadmin'
        ]);

        $allPermissionsArr = [];

        foreach(Permission::all() as $permission) {
            array_push($allPermissionsArr, $permission->id);
        }

        $superadmin->attachPermissions($allPermissionsArr);

        Role::create([
            'name' => 'agency'
        ]);

        Role::create([
            'name' => 'admin'
        ]);

        Role::create([
            'name' => 'agent'
        ]);

        Role::create([
            'name' => 'tenant'
        ]);

        Role::create([
            'name' => 'beneficiary'
        ]);
    }
}
