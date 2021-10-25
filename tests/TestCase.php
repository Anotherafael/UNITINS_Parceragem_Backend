<?php

namespace Tests;

use App\Models\Auth\User;
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

    // protected function createToken($provider) {
    //     $user = User::factory()->create();
    //     $token = $user->createToken($provider);
    //     return ['Authorization' => 'Bearer'.$token->plainTextToken];
    // }
}
