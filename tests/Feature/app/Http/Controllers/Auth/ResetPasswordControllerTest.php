<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordControllerTest extends TestCase
{
    
    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function testUserShouldAddNewPassword()
    {
        $this->refreshDatabaseOnlyMigrate();
        $this->artisan('passport:install');

        $user = User::where('name', '=', 'Serena Williams')->first();

        $payload = [
            'token' => '3cb467bd233e3b871f65ff3fb9f402631a654f57eb78bb1f0962aac560650c93',
            'email' => $user->email,
            'password' => 'secret123',
        ];
        
        $response = $this->post(route('reset_password'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['message'=> 'Password reset successfully']);
    }
}
