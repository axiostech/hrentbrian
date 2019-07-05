<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\FilterPhoneNumber;
use App\User;
use DB;

class UserController extends Controller
{
    use FilterPhoneNumber;
    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return user index page
    public function index()
    {
        return view('user.index');
    }

    // get all users api
    public function getAllUsersApi()
    {
        $users = User::select(
                'users.name', 'users.email', 'users.phone_number', 'users.status', 'users.id', 'users.last_login_at'
            )
            ->filterProfile()
            ->orderBy('users.name')
            ->get();

        return $users;
    }

    // retrieve users by given filter
    public function getUsersApi()
    {
        $perpage = request('perpage');

        $data = DB::table('users')
            ->select(
                'users.name', 'users.email', 'users.phone_number', 'users.status', 'users.id', 'users.last_login_at'
            );

        $data = $this->filterUsersApi($data);

        if($perpage != 'All') {
            $data = $data->paginate($perpage);
        }else {
            $data = $data->get();
        }
        return $data;
    }

    // store or update new individual user
    public function storeUpdateUserApi()
    {
        $fieldsArr = [
            'name' => request('name'),
            'email' => request('email'),
            'phone_number' => $this->filterMalaysiaPhoneNumber(request('phone_number')),
            'profile_id' => auth()->user()->profile->id,
            'is_temporary_password' => request('is_temporary_password') ? request('is_temporary_password') : 1
        ];

        if(request('id')) {
            if(request()->has('password')) {
                $fieldsArr['password'] = request('password');
            }
            $this->validate(request(), [
                'name' => 'required',
                'email' => 'required|unique:users,email,'.request('id'),
                'phone_number' => 'required|unique:users,phone_number,'.request('id'),
                'password' => 'confirmed'
            ]);

            $data = User::findOrFail(request('id'));
            $data->update($fieldsArr);
        }else {
            $fieldsArr['password'] = request('password');
            $this->validate(request(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'phone_number' => 'required|unique:users',
                'password' => 'required|confirmed'
            ]);
            User::create([$fieldsArr]);
        }
    }

    // delete single user api(int user_id)
    public function deactivateSingleUserApi($user_id)
    {
        $data = User::findOrFail($user_id);
        $data->status = 0;
        $data->save();
    }

    // return self user account index($user_id)
    public function userAccountIndex($user_id)
    {
        return view('user.account', compact('user_id'));
    }

    // retrieve single user api (int user_id)
    public function getSingleUserApi($user_id)
    {
        $user = User::findOrFail($user_id);

        return $user;
    }

    // users api filter(Query query)
    private function filterUsersApi($query)
    {
        $name = request('name');
        $phone_number = request('phone_number');
        $email = request('email');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('users.name', 'LIKE', '%'.$name.'%');
        }
        if($phone_number) {
            $query = $query->where('users.phone_number', 'LIKE', '%'.$phone_number.'%');
        }
        if($email) {
            $query = $query->where('users.email', 'LIKE', '%'.$email.'%');
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('users.name');
        }

        return $query;
    }
}
