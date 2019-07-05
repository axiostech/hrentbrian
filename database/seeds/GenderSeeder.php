<?php

use Illuminate\Database\Seeder;
use App\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gender::create([
            'name' => 'Male'
        ]);

        Gender::create([
            'name' => 'Female'
        ]);

        Gender::create([
            'name' => 'Others'
        ]);
    }
}
