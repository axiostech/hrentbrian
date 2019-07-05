<?php

use Illuminate\Database\Seeder;
use App\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'block_number' => 'A',
            'unit_number' => 'L3-28',
            'property_id' => 1,
            'profile_id' => 1
        ]);

        Unit::create([
            'block_number' => 'B',
            'unit_number' => 'L2-27',
            'property_id' => 1
        ]);

        Unit::create([
            'block_number' => 'C',
            'unit_number' => 'L1-26',
            'property_id' => 1
        ]);

        Unit::create([
            'unit_number' => '10',
            'address' => 'Jalan Austin Heights Utama 1',
            'property_id' => 2
        ]);

        Unit::create([
            'unit_number' => '20',
            'address' => 'Jalan Austin Heights Utama 2',
            'property_id' => 2
        ]);
    }
}
