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
    
    // public function testUserShouldAddNewPassword()
    // {
    //     $code = 200;
    //     $this->refreshDatabaseOnlyMigrate();
    //     $this->artisan('passport:install');

    //     $user = User::where('name', '=', 'Serena Williams')->first();

    //     $payload = [
    //         'token' => '392af132eae5c8dde9438aca6f709c8c31f0e2ed1be13f9617eb7d9b1a827b1f',
    //         'email' => $user->email,
    //         'password' => 'secret123',
    //     ];
        
    //     $response = $this->post(route('reset_password'), $payload);
    //     $response->assertStatus($code);
    //     $response->assertJson([
    //         'status' => $code, 
    //         'success' => true, 
    //         'message' => 'Password reset successfully'
    //     ]);
    // }
}
