<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RolePermissionController extends Controller
{
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return rolepermission index page
    public function index()
    {
        return view('rolepermission.index');
    }

    // get all roles api
    public function getAllRolesApi()
    {
        $data = DB::table('roles')
            ->select(
                'roles.id', 'roles.name', 'roles.display_name'
            )
            ->orderBy('roles.name')
            ->get();

        return $data;
    }

    // get all permissions api
    public function getAllPermissionsApi()
    {
        $data = DB::table('permissions')
            ->select(
                'permissions.id', 'permissions.name', 'permissions.display_name'
            )
            ->orderBy('permissions.table_name')
            ->get();

        return $data;
    }

    // retrieve roles by given filter
    public function getRolesApi()
    {
        $perpage = request('perpage');

        $data = DB::table('roles')
            ->select(
                'roles.id', 'roles.name', 'roles.display_name'
            );

        $data = $this->filterRolesApi($data);

        if($perpage != 'All') {
            $data = $data->paginate($perpage);
        }else {
            $data = $data->get();
        }
        return $data;
    }

    // retrieve permissions by given filter
    public function getPermissionsApi()
    {
        $perpage = request('perpage');

        $data = DB::table('permissions')
            ->select(
                'permissions.id', 'permissions.name', 'permissions.display_name', 'permissions.table_name'
            );

        $data = $this->filterPermissionsApi($data);

        if($perpage != 'All') {
            $data = $data->paginate($perpage);
        }else {
            $data = $data->get();
        }
        return $data;
    }

    // store or update new individual role
    public function storeUpdateRoleApi()
    {
        $this->validate(request(), [
            'name' => 'required'
        ]);

        $fieldsArr = [
            'name' => request('name')
        ];

        if(request('id')) {
            $data = Role::findOrFail(request('id'));
            $data->update($fieldsArr);
        }else {
            Role::create($fieldsArr);
        }
    }

    // store or update new individual permission
    public function storeUpdatePermissionApi()
    {
        $this->validate(request(), [
            'name' => 'required'
        ]);

        $name = request('name');
        $lowercase_name = strtolower($name);

        foreach(config('constant_crud.cruds') as $crud) {
            Permission::create([
                'name' => $crud['name'].' '.$lowercase_name,
                'table_name' => $lowercase_name,
                'display_name' => $crud['label']
            ]);
        }
/*
        if(request('id')) {
            $data = Permission::findOrFail(request('id'));
            $data->update($fieldsArr);
        }else {
            Permission::create($fieldsArr);
        } */
    }

    // remove user role api(int user_id)
    public function userRemoveRoleApi($user_id, $role_id)
    {
        $user = User::findOrFail($user_id);
        $role = Role::findOrFail($role_id);
        $user->removeRole($role->name);
    }

    // remove user role api(int user_id)
    public function roleRemovePermissionApi($role_id, $permission_id)
    {
        $role = Role::findOrFail($role_id);
        $permission = Permission::findOrFail($permission_id);
        $role->revokePermissionTo($permission);
    }

    // toggle role api($role_id)
/*
    public function toggleSingleRoleApi($role_id)
    {
        $data = Role::findOrFail($role_id);
        if($data->status == 1) {
            $data->status = 99;
        }else {
            $data->status = 1;
        }
        $data->save();
    } */

    // deactivate permission api($permission_id)
/*
    public function toggleSinglePermissionApi($permission_id)
    {
        // dd($permission_id);
        $data = Permission::findOrFail($permission_id);
        if($data->status == 1) {
            $data->status = 99;
        }else {
            $data->status = 1;
        }
        $data->save();
    } */

    // roles api filter(Query query)
    private function filterRolesApi($query)
    {
        $name = request('name');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('roles.name', 'LIKE', '%'.$name.'%');
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('roles.name');
        }

        return $query;
    }

    // permissions api filter(Query query)
    private function filterPermissionsApi($query)
    {
        $name = request('name');
        // $status = request('status');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('permissions.name', 'LIKE', '%'.$name.'%');
        }
/*
        if($status) {
            $query = $query->where('permissions.status', $status);
        } */
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('permissions.table_name');
        }

        return $query;
    }
}
