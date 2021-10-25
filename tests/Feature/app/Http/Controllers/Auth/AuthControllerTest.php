<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Auth\User as User;
use App\Models\Auth\Professional as Professional;

class AuthControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCheckIfInputsAreValid()
    {
        $code = 400;

        $payload = [
            'email' => 'hey',
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'users']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Invalid inputs'
        ]);
    }

    public function testUserShouldNotAuthenticateWithWrongProvider()
    {
        $code = 422;
        $payload = [
            'email' => 'hey@you.dev',
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'wrong provider']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Provider Not found'
        ]);
    }
    
    public function testUserShouldBeDeniedIfNotRegistered()
    {
        $code = 401;
        $payload = [
            'email' => 'wrong@email.dev',
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'users']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Wrong credentials'
        ]);
    }

    public function testUserShouldSendWrongPassword()
    {
        $code = 401;
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'wrong_password'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'users']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Wrong credentials'
        ]);

    }

    public function testUserCanAuthenticate()
    {
        $code = 200;
        $this->artisan('passport:install');
        $user = Professional::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'professionals']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Authenticated with success',
        ]);
        $response->assertJsonStructure([
            'data' => [
                'access_token', 
                'provider'
            ],
        ]);
    }
    
}
