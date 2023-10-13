<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Admin\Entities\Admin;
use Modules\Customer\Entities\Customer;
use Modules\Tenant\Entities\User;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class, RefreshDatabase::class, DatabaseMigrations::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * @throws \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedById
 */
function initializeTenancy()
{
    \Illuminate\Support\Facades\File::delete(base_path('database/tenant_foo'));
    $user = User::factory()->create();
    $tenant = $user->tenants()->create(['id' => 'foo', 'name' => 'Foo', 'english_name' => 'Foo']);
    $tenant->domains()->create(['domain' => 'foo.test']);
    \Illuminate\Support\Facades\URL::forceRootUrl('http://' . $tenant->domains[0]['domain']);

    tenancy()->initialize($tenant);
}


/**
 * Create user with login.
 *
 * @return User
 */
function tenantAdmin()
{
    $user = Admin::factory()->create();
    auth('tenant_admin')->login($user);

    return $user;
}


/**
 * Create customer with login.
 *
 * @return Customer
 */
function customer(): Customer
{
    return Customer::factory()->create();
}


/**
 * Create customer with login.
 *
 * @return User
 */
function user(): User
{
    return User::factory()->create();
}
