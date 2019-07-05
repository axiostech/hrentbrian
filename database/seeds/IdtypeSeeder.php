<?php

use Illuminate\Database\Seeder;
use App\Idtype;

class IdtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Idtype::create([
            'name' => 'NRIC',
            'value' => 'NRIC'
        ]);
        Idtype::create([
            'name' => 'OLD IC',
            'value' => 'OLD_IC'
        ]);
        Idtype::create([
            'name' => 'SSM',
            'value' => 'BUSINESS_REGISTRATION_NUMBER'
        ]);
        Idtype::create([
            'name' => 'Others',
            'value' => 'OTHERS'
        ]);
    }
}
