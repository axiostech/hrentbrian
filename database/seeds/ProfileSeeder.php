<?php

use Illuminate\Database\Seeder;
use App\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
/*
        Profile::create([
            'name' => 'Happy Rent Sdn Bhd',
            'roc' => '1266133-M',
            'attn_name' => 'Lee Seng Hee',
            'attn_phone_number' => '+60178250908',
            'address' => '#19-17, Austin 18, Jalan Austin Perdana 1, Taman Austin Perdana, 81100 Johor Bahru',
            'postcode' => '81100',
            'city' => 'JB',
            'state' => 'JHR',
            'is_superprofile' => 1,
            'email' => 'info@happyrent.com.my'
        ]); */
        Profile::create([
            'name' => 'Happy Rent Sdn Bhd',
            'roc' => '1266133-M',
            'attn_name' => 'Lee Hong Jie',
            'attn_phone_number' => '+60167350589',
            'address' => '#19-17, Austin 18, Jalan Austin Perdana 1, Taman Austin Perdana, 81100 Johor Bahru',
            'postcode' => '81100',
            'city' => 'JB',
            'state' => 'JHR',
            'is_superprofile' => 1,
            'email' => 'leehongjie91@gmail.com',
            'prefix' => 'HJ',
            'user_id' => 1
        ]);
    }
}
