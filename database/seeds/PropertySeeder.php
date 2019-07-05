<?php

use Illuminate\Database\Seeder;
use App\Property;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Property::create([
            'name' => 'One Amerin Residence',
            'address' => 'No 35, Persisiran Perling 1, Taman Perling',
            'postcode' => '81200',
            'city' => 'JB',
            'state' => 'JHR',
            'propertytype_id' => 2
        ]);

        Property::create([
            'name' => 'Aliff Avenue Residence',
            'address' => 'Jalan Tampoi, Kawasan Perindustrian Tampoi',
            'postcode' => '81200',
            'city' => 'JB',
            'state' => 'JHR',
            'propertytype_id' => 1
        ]);

        Property::create([
            'name' => 'Bora Residence',
            'address' => '2009, Lebuhraya Sultan Iskandar, Danga Bay',
            'postcode' => '80200',
            'city' => 'JB',
            'state' => 'JHR',
            'propertytype_id' => 2
        ]);
    }
}
