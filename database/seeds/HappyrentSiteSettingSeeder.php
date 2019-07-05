<?php

use Illuminate\Database\Seeder;
use App\HappyrentSiteSetting;

class HappyrentSiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
  
    public function run()
    {
        HappyrentSiteSetting::create([
            'billplz_secret_key'        => '4448c355-c117-4f22-b99d-dfd2e9d1d6a0',
            'billplz_signature'         => 'S-KjppZBsRlfS6NUckkN7q4Q',
            'billplz_collection_id'     => 'brhwhfbr',
            'tax_amount'                => 5,
            'created_at'                => date('Y-m-d H:i:s'),
            'updated_at'                => date('Y-m-d H:i:s'),
        ]);

       
    }
}
