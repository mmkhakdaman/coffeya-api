<?php

namespace Modules\Tenant\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Entities\Admin;
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

        $user = User::query()->create(
            [
                'first_name' => 'Mahdi',
                'last_name' => 'Khakdaman',
                'phone' => '9944432552',
                'password' => Hash::make('password'),
            ]
        );

        $tenant1 = $user->tenants()->create(['id' => 'entropy', 'name' => 'آنتروپی', 'english_name' => 'entropy']);
        $tenant1->domains()->create(['domain' => 'entropy.coffeeya.ir']);
        tenancy()->find('entropy')->run(function (){
            Admin::create([
                'name' => 'Mahdi Khakdaman',
                'phone' => '9944432552',
                'password' => Hash::make('password'),
            ]);
        });
    }
}
