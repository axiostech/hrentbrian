<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name' => 'Malaysia',
            'symbol' => 'MY'
        ]);
        Country::create([
            'name' => 'Singpore',
            'symbol' => 'SG'
        ]);
        Country::create([
            'name' => 'India',
            'symbol' => 'IN'
        ]);
    }
}
