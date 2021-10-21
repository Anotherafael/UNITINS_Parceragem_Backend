<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function refreshDatabaseOnlyMigrate() {
        return $this->artisan('migrate --seed');
    }

    protected function refreshDatabase() {
        return $this->artisan('migrate:fresh --seed');
    }
}
