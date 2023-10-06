<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Tenant\Entities\Tenant;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
}
