<?php

use Illuminate\Database\Seeder;
use App\Race;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Race::create([
            'name' => 'Chinese'
        ]);

        Race::create([
            'name' => 'Malay'
        ]);

        Race::create([
            'name' => 'Indian'
        ]);

        Race::create([
            'name' => 'Others'
        ]);
    }
}
