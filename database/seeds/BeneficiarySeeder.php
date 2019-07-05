<?php

use Illuminate\Database\Seeder;
use App\Beneficiary;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory( Beneficiary::class, 500)->create();
    }
}
