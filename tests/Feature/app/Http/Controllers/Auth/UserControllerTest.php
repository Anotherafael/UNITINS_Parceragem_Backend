<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotBeCreatedWithInvalidInput()
    {
        $code = 400;
        $payload = [
            'name' => 'Rafael Freitas',
            'email' => 'Wrong email',
            'phone' => 12123412341,
            'document_id' => 12312312312,
            'password' => 'secret'
        ];

        $response = $this->post(route('user_register', ['provider' => 'professionals']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Invalid inputs'
        ]);
    }

    public function testUserShouldNotBeCreatedWithWrongProvider()
    {
        $code = 422;
        $payload = [
            'name' => 'Rafael Freitas',
            'email' => 'teste@gmail.com',
            'phone' => 12123412341,
            'document_id' => 12312312312,
            'password' => 'secret'
        ];

        $response = $this->post(route('user_register', ['provider' => 'wrong provider']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Provider Not Found'
        ]);
    }

    public function testUserShouldBeCreatedIfEverythingIsOkay()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $code = 200;
        $payload = [
            'name' => 'Rafael Freitas',
            'email' => 'rafael@email.com',
            'phone' => 12123412341,
            'document_id' => 12312312312,
            'password' => 'secret'
        ];

        $response = $this->post(route('user_register', ['provider' => 'users']), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Created with success'
        ]);
    }

}
