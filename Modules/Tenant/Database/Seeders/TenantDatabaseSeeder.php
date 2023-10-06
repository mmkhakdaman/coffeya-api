<?php

namespace Modules\Tenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Tenant\Entities\Tenant;
use Modules\Tenant\Entities\User;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user = User::factory()->create();

        $tenant1 = $user->tenants()->create(['id' => 'foo', 'name' => 'Foo', 'english_name' => 'Foo']);
        $tenant1->domains()->create(['domain' => 'foo.coffeya-api.test']);
    }
}
