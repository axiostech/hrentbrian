<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Brian Lee',
            'email' => 'leehongjie91@gmail.com',
            'phone_number' => '+60167350589',
            'password' => 'brian',
            'profile_id' => 1
        ]);


    }
}
