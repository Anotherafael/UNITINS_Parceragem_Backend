<?php

namespace Tests\Feature\Feature\app\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Auth\User as User;
use App\Models\Auth\Professional as Professional;

class AuthControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotAuthenticateWithWrongProvider()
    {
        $payload = [
            'email' => 'hey@you.dev',
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'wrong provider']), $payload);
        $response->assertStatus(422);
        $response->assertJson(['errors' => ['main' => 'Provider Not found']]);
    }
    
    public function testUserShouldBeDeniedIfNotRegistered()
    {
        $payload = [
            'email' => 'wrong@email.dev',
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'users']), $payload);
        $response->assertStatus(401);
        $response->assertJson(['errors' => ['main' => 'Wrong credentials']]);
    }

    public function testUserShouldSendWrongPassword()
    {

        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'wrong_password'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'users']), $payload);
        $response->assertStatus(401);
        $response->assertJson(['errors' => ['main' => 'Wrong credentials']]);

    }

    public function testUserCanAuthenticate()
    {

        $this->artisan('passport:install');
        $user = Professional::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $response = $this->post(route('authenticate', ['provider' => 'professionals']), $payload);
        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'provider']);
    }
    
}
