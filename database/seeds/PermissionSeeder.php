<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $moduleArr = [
            'profiles', 'users', 'roles', 'permissions', 'tenants', 'beneficiaries', 'properties', 'units', 'tenancies', 'insurances'
        ];

        foreach($moduleArr as $module) {
            foreach(config('constant_crud.cruds') as $crud) {
                Permission::create([
                    'name' => $crud['name'].' '.$module,
                    'table_name' => $module,
                    'display_name' => $crud['label']
                ]);
            }
        }

    }
}
