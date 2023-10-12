<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Tenant\Entities\Tenant;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
}
