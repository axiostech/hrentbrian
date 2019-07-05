<?php

use Illuminate\Database\Seeder;
use App\HappyrentInsurancePlan;

class HappyrentInsurancePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  
    public function run()
    {
        HappyrentInsurancePlan::create([
            'insurance_plan_name'       => 'Plan A',
            'insurance_plan_price'      => '10 RM',
            'insurance_plan_duration'   => 'Monthly'
        ]);

        HappyrentInsurancePlan::create([
            'insurance_plan_name'       => 'Plan A',
            'insurance_plan_price'      => '20 RM',
            'insurance_plan_duration'   => 'Monthly'
        ]);

       
    }
}
