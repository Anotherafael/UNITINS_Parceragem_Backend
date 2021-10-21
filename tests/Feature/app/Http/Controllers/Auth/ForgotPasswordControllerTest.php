<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Auth\User;

class ForgotPasswordControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }
    
    public function testUserShouldSendAValidEmail()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');
        
        $user = User::factory()->create();
        
        $payload = [
            'email' => $user->email,
        ];
        
        $response = $this->post(route('forgot_password'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'We have emailed your password reset link!']);
    }

}
