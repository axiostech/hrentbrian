<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Traits\SyncUser;
use App\Profile;
use App\Role;
use App\User;
use Carbon\Carbon;
use DB;


class ProfileController extends Controller
{
    use SyncUser;

    // auth access
    public function __construct()
    {
        $this->middleware('auth');
    }

    // return profile index page
    public function index()
    {
        return view('profile.index');
    }

    // get all profiles api
    public function getAllProfilesApi()
    {
        $profiles = DB::table('profiles')
            ->select(
                'profiles.id', 'profiles.name', 'profiles.roc', 'profiles.attn_name', 'profiles.attn_phone_number', 'profiles.address',
                'profiles.postcode', 'profiles.city', 'profiles.state', 'profiles.domain_name', 'profiles.logo_url', 'profiles.theme_color',
                'profiles.prefix', 'profiles.email', 'profiles.tenancy_running_num', 'profiles.user_id'
            )
            ->orderBy('properties.name')
            ->get();

        return $profiles;
    }

    // retrieve profiles by given filter
    public function getProfilesApi()
    {
        $perpage = request('perpage');

        $profiles = DB::table('profiles')
            ->select(
                'profiles.id', 'profiles.name', 'profiles.roc', 'profiles.attn_name', 'profiles.attn_phone_number',
                'profiles.address', 'profiles.postcode', 'profiles.city', 'profiles.state', 'profiles.domain_name',
                'profiles.logo_url', 'profiles.theme_color', 'profiles.is_superprofile', 'profiles.prefix', 'profiles.email',
                'profiles.tenancy_running_num', 'profiles.user_id'
            );

        $profiles = $this-> filterProfilesApi($profiles);

        // dd($profiles->get()->toArray());

        if($perpage != 'All') {
            $profiles = $profiles->paginate($perpage);
        }else {
            $profiles = $profiles->get();
        }
        return $profiles;
    }

    // store or update new individual profile
    public function storeUpdateProfileApi()
    {
        // dd(request('image'), request('image')->getClientOriginalName());


        $fieldsArr = [
            'name' => request('name'),
            'roc' => request('roc'),
            'attn_name' => request('attn_name'),
            'attn_phone_number' => request('attn_phone_number'),
            'address' => request('address'),
            'postcode' => request('postcode'),
            'city' => request('city'),
            'state' => request('state'),
            'domain_name' => request('domain_name'),
            'logo_url' => request('logo_url'),
            'theme_color' => request('theme_color'),
            'is_superprofile' => request('is_superprofile') ? 1 : 0,
            'prefix' => request('prefix'),
            'email' => request('email')
        ];

        // dd(request('id'),$fieldsArr);

        if(request('id')) {
            $this->validate(request(), [
                'name' => 'required',
                'attn_name' => 'required',
                // 'attn_phone_number' => 'required|unique:profiles,attn_phone_number,'.request('id'),
                'email' => 'required|unique:profiles,email,'.request('id'),
                'prefix' => 'required'
            ]);
            $profile = Profile::findOrFail(request('id'));
            $profile->update($fieldsArr);
        }else {
            $this->validate(request(), [
                'name' => 'required',
                'attn_name' => 'required',
                'attn_phone_number' => 'required|unique:profiles',
                'email' => 'required|unique:profiles',
                'prefix' => 'required'
            ]);
            $profile = Profile::create($fieldsArr);
        }

        $image = request('image');
        if ($image) {
            File::delete(public_path() . $profile->logo_url);
            $name = (Carbon::now()->format('dmYHi')) . $image->getClientOriginalName();
            $image->move('profile/logo/' . $profile->id . '/', $name);
            $profile->logo_url = '/profile/logo/' . $profile->id . '/' . $name;
            $profile->save();
        }

        $profile_register_user = request('user_id');
        if($profile_register_user and !$profile->user_id) {
            $profile->user_id = $this->syncNewUser($profile, 'company');
            $profile->save();

            $user = User::findOrFail($profile->user_id);
            $agent_role = Role::where('name', 'agency')->first();
            $user->attachRole($agent_role);
        }
    }

    // deactivate single profile api(int profile_id)
    public function deactivateSingleProfileApi($profile_id)
    {
        $profile = Profile::findOrFail($profile_id);
        $profile->status = 0;
        $profile->save();

        $profile->user->status = 1;
        $profile->user->save();
    }

    // profiles api filter(Query query)
    private function filterProfilesApi($query)
    {
        $name = request('name');
        $attn_name = request('attn_name');
        $attn_phone_number = request('attn_phone_number');
        $domain_name = request('domain_name');
        $sortkey = request('sortkey');
        $reverse = request('reverse');

        if($name) {
            $query = $query->where('profiles.name', 'LIKE', '%'.$name.'%');
        }
        if($attn_name) {
            $query = $query->where('profiles.attn_name', 'LIKE', '%'.$attn_name.'%');
        }
        if($attn_phone_number) {
            $query = $query->where('profiles.attn_phone_number', 'LIKE', '%'.$attn_phone_number.'%');
        }
        if($domain_name) {
            $query = $query->where('profiles.domain_name', 'LIKE', '%'.$domain_name.'%');
        }
        if($sortkey) {
            $query = $query->orderBy($sortkey, $reverse == 'true' ? 'asc' : 'desc');
        }else{
            $query = $query->orderBy('profiles.name');
        }

        return $query;
    }
}
