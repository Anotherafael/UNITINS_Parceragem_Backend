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

    /** @test */
    public function user_should_not_register_with_wrong_provider()
    {
        $payload = [
            'name' => 'Geogrio Galvani',
            'email' => 'galvani@dev.br',
            'document_id' => '12332112333',
            'phone' => '12112344321',
            'password' => 'secret'
        ];

        $response = $this->post(route('register', ['provider' => 'wrong_provider']), $payload);
        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'Error',  
            'message' => 'Provider Not Found'
        ]);
    }

    /** @test */
    public function user_should_not_register_with_existing_user()
    {
        $user = User::factory()->create();
        $payload = [
            'name' => 'Geogrio Galvani',
            'email' => $user->email,
            'document_id' => '12332112333',
            'phone' => '12112344321',
            'password' => 'secret'
        ];

        $response = $this->post(route('register', ['provider' => 'users']), $payload);
        $response->assertStatus(500);
        $response->assertJson([
            'status' => 'Error',  
            'message' => 'User already exist'
        ]);
    }

    /** @test */
    public function user_can_register()
    {
        $this->refreshDatabase();
        $payload = [
            'name' => 'Geogrio Galvani',
            'email' => 'galvani@dev.br',
            'document_id' => '12332112333',
            'phone' => '12112344321',
            'password' => 'secret'
        ];

        $response = $this->post(route('register', ['provider' => 'users']), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',  
            'message' => 'Registered with success'
        ]);
        $response->assertJsonStructure([
            'data' => [
                'user',
            ]
        ]);
    }

    /** @test */
    public function user_should_not_login_with_wrong_provider()
    {
        $payload = [
            'email' => 'hey@you.dev',
            'password' => 'secret'
        ];

        $response = $this->post(route('login', ['provider' => 'wrong_provider']), $payload);
        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'Error',  
            'message' => 'Provider Not Found'
        ]);
    }
    
    /** @test */
    public function user_access_should_be_denied_if_not_registered()
    {
        $payload = [
            'email' => 'wrong@email.dev',
            'password' => 'secret'
        ];

        $response = $this->post(route('login', ['provider' => 'users']), $payload);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Error', 
            'message' => 'Wrong credentials'
        ]);
    }

    /** @test */
    public function user_access_should_be_denied_if_send_wrong_password()
    {
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'wrong_password'
        ];

        $response = $this->post(route('login', ['provider' => 'users']), $payload);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Error', 
            'message' => 'Wrong credentials'
        ]);

    }

    /** @test */
    public function user_can_login()
    {
        $user = Professional::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'secret'
        ];

        $response = $this->post(route('login', ['provider' => 'professionals']), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success', 
            'message' => 'Authenticated with success',
        ]);
        $response->assertJsonStructure(['data']);
    }
    
}
