<?php

use Illuminate\Database\Seeder;
use App\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::create([
            'name' => 'brian',
            'email' => 'leehongjie91@gmail.com',
            'phone_number' => '+60167350589',
            'id_value' => '911110016095',
            'idtype_id' => 1,
            'profile_id' => 1
        ]);

        factory(Tenant::class, 1000)->create();
    }
}
