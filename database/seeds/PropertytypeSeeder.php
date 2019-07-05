<?php

use Illuminate\Database\Seeder;
use App\Propertytype;

class PropertytypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Propertytype::create([
            'name' => 'Apartment'
        ]);
        Propertytype::create([
            'name' => 'Condominium'
        ]);
        Propertytype::create([
            'name' => 'Serviced Apartment'
        ]);
        Propertytype::create([
            'name' => 'Flat'
        ]);
        Propertytype::create([
            'name' => 'Townhouse'
        ]);
        Propertytype::create([
            'name' => 'Stratified Landed'
        ]);
        Propertytype::create([
            'name' => 'Landed'
        ]);
        Propertytype::create([
            'name' => 'SOHO or SOVO'
        ]);
        Propertytype::create([
            'name' => 'Office Tower'
        ]);
        Propertytype::create([
            'name' => 'Stratified Commercial'
        ]);
        Propertytype::create([
            'name' => 'Stratified Retail Lot'
        ]);
        Propertytype::create([
            'name' => 'Stratified Shop Houses'
        ]);
    }
}
