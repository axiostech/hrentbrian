<?php

use Illuminate\Database\Seeder;
use App\Operator;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Operator::create([
            'name' => 'TNB Bill',
            'code' => 'TN',
            'type' => 'Postpaid Utilities',
            'subscriber_id_length' => '10-11',
            'service_type' => 1
        ]);

        Operator::create([
            'name' => 'Sabah Electricity',
            'code' => 'SE',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '12-12',
            'service_type' => 1
        ]);


        Operator::create([
            'name' => 'Sarawak Energy',
            'code' => 'SO',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '10-11',
            'service_type' => 1
        ]);

        Operator::create([
            'name' => 'Digi - Broadband',
            'code' => 'DR',
            'type' => 'Prepaid Mobile',
            'subscriber_id_length' => '10-11',
            'service_type' => 3
        ]);

        Operator::create([
            'name' => 'Celcom Internet',
            'code' => 'XT',
            'type' => 'Prepaid Mobile',
            'subscriber_id_length' => '10-11',
            'service_type' => 3
        ]);

        Operator::create([
            'name' => 'Unifi Internet Bill',
            'code' => 'UN',
            'type' => 'Postpaid Utilities',
            'subscriber_id_length' => '10-10',
            'service_type' => 3
        ]);


        Operator::create([
            'name' => 'TM Bill',
            'code' => 'TB',
            'type' => 'Postpaid Utilities',
            'subscriber_id_length' => '11-13',
            'service_type' => 3
        ]);

        Operator::create([
            'name' => 'Astro',
            'code' => 'AS',
            'type' => 'Postpaid Mobile',
            'subscriber_id_length' => '10-10',
            'service_type' => 3
        ]);




        Operator::create([
            'name' => 'Air Selangor - SYABAS',
            'code' => 'SB',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '10-11',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Ranhil Air Johor',
            'code' => 'SJ',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '16-16',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air Melaka',
            'code' => 'SM',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '16-16',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air Perak',
            'code' => 'LP',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '16-17',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air Terengganu',
            'code' => 'ST',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '10-14',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air N.Sembilan',
            'code' => 'NS',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '10-16',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air Kuching',
            'code' => 'LK',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '10-10',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air Darul Aman',
            'code' => 'SD',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '8-15',
            'service_type' => 2
        ]);

        Operator::create([
            'name' => 'Air Kelantan',
            'code' => 'AK',
            'type' => 'Government Utilities',
            'subscriber_id_length' => '8-15',
            'service_type' => 2
        ]);
    }
}
