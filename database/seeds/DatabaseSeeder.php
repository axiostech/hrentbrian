<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProfileSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RaceSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PropertytypeSeeder::class);
        $this->call(PropertySeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(IdtypeSeeder::class);
        $this->call(TenantSeeder::class);
        $this->call(BeneficiarySeeder::class);
        $this->call(OperatorsTableSeeder::class);
        $this->call(HappyrentInsurancePlanSeeder::class);
        $this->call(HappyrentSiteSettingSeeder::class);
        
        
    }
}
