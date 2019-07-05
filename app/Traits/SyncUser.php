<?php

namespace App\Traits;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendLoginCredentialEmail;
use App\User;
use App\Role;

trait SyncUser
{
  // use HasRoles;

  public function syncNewUser($data, $role = null)
  {
    $bool_phone_number = isset($data->phone_number);
    $bool_attn_phone_number = isset($data->attn_phone_number);
    $bool_email = isset($data->bool_email);
    $user = null;

    $user = new User();

    if($bool_phone_number) {
      $user = $user->where('phone_number', $data->phone_number);
    }

    if($bool_attn_phone_number) {
      $user = $user->where('phone_number', $data->attn_phone_number);
    }

    if ($bool_email) {
      $user = $user->orWhere('email', $data->email);
    }

    $user = $user->first();

    if(!$user) {
      $password = substr(str_shuffle(MD5(microtime())), 0, 6);

      $user = User::create([
        'name' => $data->name,
        'email' => $data->email,
        'password' => $password,
        'phone_number' => isset($data->phone_number) ? $data->phone_number : $data->attn_phone_number,
        'status' => 1,
        'is_temporary_password' => 1,
        'profile_id' => $data->profile ? $data->profile->id : $data->id
      ]);
      Mail::to($user->email)->send(new SendLoginCredentialEmail($user->id, $password, 1));
    }

    if($role and !$user->hasRole($role)) {
      $roleCollection = Role::where('name', $role)->first();
      $user->attachRole($roleCollection);
    }

    return $user->id;
  }

  public function syncUpdateUser($data)
  {
    if($data->user_id) {
      $user = User::findOrFail($data->user_id);

      $user->name = $data->name;
      $user->email = $data->email;
      $user->name = $data->name;
      $user->phone_number = $data->phone_number;
      $user->save();
    }
  }
}
